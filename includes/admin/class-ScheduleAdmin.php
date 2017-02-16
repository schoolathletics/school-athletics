<?php 
/**
 * Schedule Page.
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
 * ScheduleAdmin Class.
 */
class ScheduleAdmin extends Page{

	public function __construct(){
		parent::__construct();
		self::output();
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-sortable');
		wp_register_style('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
		wp_enqueue_style( 'jquery-ui' );
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-slider');
		wp_enqueue_script( 'school-athletics', SA__PLUGIN_URL.'assets/js/ui.js');
		wp_enqueue_script( 'jquery-ui-timepicker-addon', SA__PLUGIN_URL.'assets/js/jquery-ui-timepicker-addon.js');
		wp_enqueue_style( 'jquery-ui-timepicker-addon', SA__PLUGIN_URL.'assets/css/jquery-ui-timepicker-addon.css');
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
		include_once( 'views/html-admin-page-schedule.php' );
	}

	/**
	 * Get members for admin
	 */
	public static function getSchedule($sport,$season){
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
			'post_type' => 'sa_schedule',
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
		$schedule = get_posts($args);
		return $schedule;
	}

	/**
	 * Event default array
	 */
	public static function eventDefaults(){
		//Defaults for athletes
		$defaults = array(
				'ID'     => 0,
				'date'  => '',
				'name'   => '',
				'result' => '',
				'location' => '',
				'game_type' => '',
				'outcome' => '',
				'order' => '',
			);
		return $defaults;
	}

	/**
	 * Get members for admin
	 */
	public static function getEvents($sport,$season,$import = null){
		//Accept sport as an object or ID
		if(is_object($sport)){
			$sport = $sport->term_id;
		}
		//Accept season as an object or ID
		if(is_object($season)){
			$season = $season->term_id;
		}

		$_events = \SchoolAthletics\Schedule::getEvents($sport,$season);

		$events = array();
		foreach ($_events as $event) {
			$location = get_the_terms($event,'sa_location');
			$location = (is_array($location)) ? array_pop($location) : null;
			$location = ($location) ? $location->name : '- - -';

			$game_type = get_the_terms($event,'sa_game_type');
			$game_type = (is_array($game_type)) ? array_pop($game_type) : null;
			$game_type = ($game_type) ? $game_type->name : '- - -';

			$outcome = get_the_terms($event,'sa_outcome');
			$outcome = (is_array($outcome)) ? array_pop($outcome) : null;
			$outcome = ($outcome) ? $outcome->name : '- - -';

			$events[] = array(
					'ID'	 => $event->ID,
					'date'  => get_post_meta( $event->ID, 'sa_start', true ),
					'name'   => get_post_meta( $event->ID, 'sa_name', true ),
					'result' => get_post_meta( $event->ID, 'sa_result', true ),
					'location' => $location,
					'game_type' => $game_type,
					'outcome' => $outcome,
					'order' => get_post_meta( $event->ID, 'sa_order', true ),
				);
		}
		//Defaults for athletes
		$defaults = self::eventDefaults();
		if(!empty($import) && is_array($import)){
			foreach ($import as $key => $value) {
				//$import[$key]['ID'] = '';
				$import[$key] = wp_parse_args($import[$key],$defaults);
			}
			$events = array_merge($events,$import);
		}
		if(empty($events)){
			//Adds a new row to the bottom
			$events[] = $defaults;
		}
		return $events;
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
	 * Create roster page.
	 */
	public static function save_schedule($sport,$season,$data){
		if(!is_object($sport)){
			$sport = get_term($sport);
		}
		if(!is_object($season)){
			$season = get_term($season);
		}

		$roster = array(
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

		return wp_insert_post($roster);
	}

	/**
	 * Add members for roster.
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
