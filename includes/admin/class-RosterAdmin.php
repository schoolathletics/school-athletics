<?php 
/**
 * Roster Page.
 *
 * @author   Dwayne Parton
 * @category Admin
 * @package  SchoolAthletics/Admin
 * @version  0.0.1
 */

namespace SchoolAthletics\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Roster Class.
 */
class RosterAdmin extends Page{

	public function __construct(){
		parent::__construct();
		self::output();
		wp_enqueue_media();
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script( 'school-athletics', SA__PLUGIN_URL.'assets/js/ui.js');
	}

	/**
	 * Handles output of the settings page in admin.
	 */
	public static function output() {
		if($_POST){
			if(isset($_POST['action']) && $_POST['action'] == 'import'){
				$import = \SchoolAthletics\Admin\Page::parse_csv($_POST['csv']);
				\SchoolAthletics\Admin\Notice::warning(__( 'Nothing has been saved yet. First, make sure your import looks right and then click <i>Save Changes</i> to add athletes to the roster. If things look wrong try updating your csv code and reimporting.', 'school-athletics' ));
			}else{
				self::save();
			}

		}
		include_once( 'views/html-admin-page-roster.php' );
	}

	/**
	 * Athlete default array
	 */
	public static function athleteDefaults(){
		//Defaults for athletes
		$defaults = array(
				'ID'     => 0,
				'photo'  => '',
				'jersey' => '',
				'name'   => '',
				'height' => '',
				'weight' => '',
				'status' => '',
				'order' => '',
			);
		return $defaults;
	}


	/**
	 * Get members for admin
	 */
	public static function getRoster($sport,$season){
		//Accept sport as an object or ID
		if(is_object($sport)){
			$sport = $sport->term_id;
		}
		//Accept season as an object or ID
		if(is_object($season)){
			$season = $season->term_id;
		}

		$args = array(
			'posts_per_page' => 0,
			'post_type' => 'sa_roster',
			'tax_query' => array(
				array(
					'taxonomy' => 'sa_sport',
					'field' => 'id',
					'terms' => $_GET['sport'], // Where term_id of Term 1 is "1".
				),
				array(
					'taxonomy' => 'sa_season',
					'field' => 'id',
					'terms' => $_GET['season'],
				)
			),
	    );
		$roster = get_posts($args);
		return $roster;
	}


	/**
	 * Get members for admin
	 */
	public static function getMembers($sport,$season,$import = null){
		//Accept sport as an object or ID
		if(is_object($sport)){
			$sport = $sport->term_id;
		}
		//Accept season as an object or ID
		if(is_object($season)){
			$season = $season->term_id;
		}

		$_members = \SchoolAthletics\Roster::getMembers($sport,$season);

		$members = array();
		foreach ($_members as $member) {
			$status = get_the_terms($member,'sa_athlete_status');
			$status = (is_array($status)) ? array_pop($status) : null;
			$status = ($status) ? $status->name : '- - -';
			$members[] = array(
					'ID'	 => $member->ID,
					'photo'  => get_post_thumbnail_id( $member->ID ),
					'jersey' => get_post_meta( $member->ID, 'sa_jersey', true ),
					'name'   => get_post_meta( $member->ID, 'sa_name', true ),
					'height' => get_post_meta( $member->ID, 'sa_height', true ),
					'weight' => get_post_meta( $member->ID, 'sa_weight', true ),
					'order'  => get_post_meta( $member->ID, 'sa_order', true ),
					'status' => $status,
				);
		}
		//Defaults for athletes
		$defaults = self::athleteDefaults();
		if(!empty($import) && is_array($import)){
			foreach ($import as $key => $value) {
				//$import[$key]['ID'] = '';
				$import[$key] = wp_parse_args($import[$key],$defaults);
			}
			$members = array_merge($members,$import);
		}
		if(empty($members)){
			//Adds a new row to the bottom
			$members[] = $defaults;
		}
		return $members;
	}
	


	/**
	 * Save roster
	 */
	public static function save(){
		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'schoolathletics-save-roster' ) ) {
			\SchoolAthletics\Admin\Notice::error(__( 'Failed to save roster. Please refresh the page and retry.', 'school-athletics' ));
			return false;
		}

		if(!empty($_REQUEST['season']) && !empty($_REQUEST['sport'])){
			$roster_id = self::save_roster($_REQUEST['sport'],$_REQUEST['season'],$_POST);
			$athletes = $_POST['athlete'];
			foreach ($athletes as $athlete) {
				$athlete_id = self::add_member($_REQUEST['sport'],$_REQUEST['season'],$athlete);
				wp_set_post_terms($roster_id, $athlete['name'], 'sa_person', true );
			}
			if(isset($_POST['deleteMember'])){
				self::delete_members($_POST['deleteMember']);
			}

			\SchoolAthletics\Admin\Notice::success('Roster has been successfully saved.');
		}

	}

	/**
	 * Create roster page.
	 */
	public static function save_roster($sport,$season,$data){
		if(!is_object($sport)){
			$sport = get_term($sport);
		}
		if(!is_object($season)){
			$season = get_term($season);
		}

		$roster = array(
			'ID' => $data['ID'],
			'_thumbnail_id'=>$data['photo'] ,//Not documented but nice to know
			'post_content' => $data['roster_content'],
			'post_title' => $season->name .' '. $sport->name .' Roster',
			'post_type' => 'sa_roster',
			'post_status' => 'publish',//publish
			'tax_input' => array(
				'sa_sport' => $sport->name,
				'sa_season' => $season->name,
			),
		);

		return wp_insert_post($roster);
	}

	/**
	 * Add members for roster.
	 */
	public static function add_member($sport,$season,$member){
		if(!is_object($sport)){
			$sport = get_term($sport);
		}
		if(!is_object($season)){
			$season = get_term($season);
		}
		if(!is_array($member)){
			return;
		}
		if(!$member['name']){
			return;
		}

		$member = array(
			'ID' => $member['ID'],
			'_thumbnail_id'=>$member['photo'] ,//Not documented but nice to know
			'post_content' => 'Bio goes here.',
			'post_title' => $member['name'],
			'post_type' => 'sa_roster_member',
			'post_status' => 'publish',//publish
			'tax_input' => array(
				'sa_sport' => $sport->name,
				'sa_season' => $season->name,
				'sa_person' => $member['name'],
				'sa_athlete_status' => $member['status'],
			),
			'meta_input'   => array(
				'sa_jersey' => $member['jersey'],
				'sa_name' => $member['name'],
				'sa_height' => $member['height'],
				'sa_weight' => $member['weight'],
				'sa_order' => $member['order'],
			),	
		);
		return wp_insert_post($member);

	}

	/**
	 * Delete members from roster.
	 */
	public static function delete_members($members){
		foreach ($members as $member) {
			wp_delete_post( $member, true);
		}
	}

}