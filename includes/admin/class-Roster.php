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
class Roster extends Page{

	public function __construct(){
		parent::__construct();
		self::output();
		wp_enqueue_media();
		wp_enqueue_script('jquery-ui-sortable');
		//add_action("admin_enqueue_scripts", array($this,'enqueue_scripts') );
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

	public function enqueue_scripts(){
    	wp_enqueue_media();
	}

	public function import(){

	}

	public static function save(){
		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'schoolathletics-save-roster' ) ) {
			\SchoolAthletics\Admin\Notice::error(__( 'Failed to save roster. Please refresh the page and retry.', 'school-athletics' ));
			return false;
		}

		if(!empty($_REQUEST['season']) && !empty($_REQUEST['sport'])){
			$roster_id = self::save_roster($_REQUEST['sport'],$_REQUEST['season'],$_POST);
			$athletes = $_POST['athlete'];
			$_roster_members = array();
			foreach ($athletes as $athlete) {
				$athlete_id = self::add_member($_REQUEST['sport'],$_REQUEST['season'],$athlete);
				 wp_set_post_terms($roster_id, $athlete['name'], 'sa_person', true );
				 //$roster_members[] = $athlete_id;
			}
			$_roster_members= $_POST['athletes'];
			foreach ($_roster_members as $member) {
				$roster_members[] = $member;
			}
			update_post_meta($roster_id, 'sa_roster_members', $roster_members);
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
			'post_name' => $data['roster_content'],
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
	 * Add athletes for roster.
	 */
	public static function add_member($sport,$season,$athlete){
		if(!is_object($sport)){
			$sport = get_term($sport);
		}
		if(!is_object($season)){
			$season = get_term($season);
		}
		if(!is_array($athlete)){
			return;
		}
		if(!$athlete['name']){
			return;
		}

		$athlete = array(
			'ID' => $athlete['ID'],
			'_thumbnail_id'=>$athlete['photo'] ,//Not documented but nice to know
			'post_content' => 'Bio goes here.',
			'post_title' => $athlete['name'],
			'post_name' => '',
			'post_type' => 'sa_roster_member',
			'post_status' => 'publish',//publish
			'tax_input' => array(
				'sa_sport' => $sport->name,
				'sa_season' => $season->name,
				'sa_person' => $athlete['name'],
				'sa_athlete_status' => $athlete['status'],
			),
			'meta_input'   => array(
				'sa_jersey' => $athlete['jersey'],
				'sa_name' => $athlete['name'],
				'sa_height' => $athlete['height'],
				'sa_weight' => $athlete['weight'],
			),	
		);
		return wp_insert_post($athlete);

	}

}