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
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script( 'school-athletics', SA__PLUGIN_URL.'assets/js/ui.js');
	}

	/**
	 * Handles output of the settings page in admin.
	 */
	public static function output() {
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
					'date'  => get_post_meta( $event->ID, 'sa_date', true ),
					'name'   => get_post_meta( $event->ID, 'sa_name', true ),
					'result' => get_post_meta( $event->ID, 'sa_result', true ),
					'location' => $location,
					'game_type' => $game_type,
					'outcome' => $outcome,
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

}
