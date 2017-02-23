<?php 
/**
 * Schedule Page.
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
 * ScheduleAdmin admin page class.
 */
class Schedule extends Page{

	public function __construct(){
		parent::__construct();
		self::output();
	}

	/**
	 * Handles output of the settings page in admin.
	 */
	public static function output() {
		$import = '';
		if($_POST){
			if(isset($_POST['action']) && $_POST['action'] == 'import'){
				$import = \SchoolAthletics\Admin\Page::parse_csv($_POST['csv']);
				\SchoolAthletics\Admin\Notice::warning(__( 'Nothing has been saved yet. First, make sure your import looks right and then click <i>Save Changes</i> to add events to the schedule. If things look wrong try updating your csv code and reimporting.', 'school-athletics' ));
			}else{
				self::save();
			}

		}

		$autocomplete = self::autocomplete();
		include_once( 'views/html-admin-page-schedule.php' );
	}

	/**
	 * Autocomplete
	 */
	public static function autocomplete(){
		//Defaults for athletes
		$options = get_terms( array(
			'taxonomy' => 'sa_opponent',
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
		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'schoolathletics-save-schedule' ) ) {
			\SchoolAthletics\Admin\Notice::error(__( 'Failed to save schedule. Please refresh the page and retry.', 'school-athletics' ));
			return false;
		}

		if(!empty($_REQUEST['season']) && !empty($_REQUEST['sport'])){
			$schedule_id = self::save_schedule($_REQUEST['sport'],$_REQUEST['season'],$_POST);
			$events = $_POST['event'];
			foreach ($events as $event) {
				$event_id = self::add_event($_REQUEST['sport'],$_REQUEST['season'],$event);
			}
			if(isset($_POST['deleteObjects'])){
				self::delete_events($_POST['deleteObjects']);
			}

			\SchoolAthletics\Admin\Notice::success('Schedule has been successfully saved.');
		}

	}

	/**
	 * Create schedule page.
	 */
	public static function save_schedule($sport,$season,$data){
		if(!is_object($sport)){
			$sport = get_term($sport);
		}
		if(!is_object($season)){
			$season = get_term($season);
		}

		$schedule = array(
			'ID' => $data['ID'],
			//'_thumbnail_id'=>$data['photo'] ,//Not documented but nice to know
			'post_content' => $data['schedule_content'],
			'post_title' => $season->name .' '. $sport->name .' Schedule',
			'post_type' => 'sa_schedule',
			'post_status' => 'publish',//publish
			'tax_input' => array(
				'sa_sport' => $sport->name,
				'sa_season' => $season->name,
			),
		);

		return wp_insert_post($schedule);
	}

	/**
	 * Add events to roster.
	 */
	public static function add_event($sport,$season,$event){
		if(!is_object($sport)){
			$sport = get_term($sport);
		}
		if(!is_object($season)){
			$season = get_term($season);
		}
		if(!is_array($event)){
			return;
		}
		if(!$event['name']){
			return;
		}

		$event = array(
			'ID' => $event['ID'],
			//'_thumbnail_id'=>$event['photo'] ,//Not documented but nice to know
			'post_content' => 'Bio goes here.',
			'post_title' => $event['name'],
			'post_type' => 'sa_event',
			'post_status' => 'publish',//publish
			'tax_input' => array(
				'sa_sport' => $sport->name,
				'sa_season' => $season->name,
				'sa_event_type' => 'game',
				'sa_game_type' => $event['game_type'],
				'sa_location' => $event['location'],
				'sa_outcome' => $event['outcome'],
			),
			'meta_input'   => array(
				'sa_start' => $event['date'],
				'sa_name' => $event['name'],
				'sa_result' => $event['result'],
				'sa_order' => $event['order'],
			),	
		);
		return wp_insert_post($event);

	}

	/**
	 * Delete members from roster.
	 */
	public static function delete_events($events){
		foreach ($events as $event) {
			wp_delete_post( $event, true);
		}
	}

}
