<?php 
/**
 * Roster Page.
 *
 * @author   Dwayne Parton
 * @category Admin
 * @package  SchoolAthletics/Admin
 * @version  0.0.1
 */

namespace SchoolAthletics\Admin\Pages;
use SchoolAthletics\Admin\Page as Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Roster admin page class.
 */
class Roster extends Page{

	public function __construct(){
		parent::__construct();
		self::output();
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
		$autocomplete = self::autocomplete();
		include_once( 'views/html-admin-page-roster.php' );
	}


	/**
	 * Autocomplete
	 */
	public static function autocomplete(){
		//Defaults for athletes
		$options = get_terms( array(
			'taxonomy' => 'sa_person',
			'hide_empty' => false,
		));
		$autocomplete = '';
		if (!empty($options)) {
			foreach ($options as $option) {
				if(isset($i)){
					$autocomplete .= ',';
				}
				$autocomplete .= '"'.addslashes ($option->name).'"';
				$i = true;
			}
		}
		return $autocomplete;
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
			$athlete_terms = array();
			foreach ($athletes as $athlete) {
				$athlete_id = self::add_member($_REQUEST['sport'],$_REQUEST['season'],$athlete);
				$athlete_terms[] = $athlete['name'];
			}
			wp_set_post_terms($roster_id, $athlete_terms, 'sa_person', false );
			if(isset($_POST['deleteObjects'])){
				self::delete_members($_POST['deleteObjects']);
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