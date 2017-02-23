<?php 
/**
 * Main schedule class.
 *
 * 
 * @author   Dwayne Parton
 * @category Class
 * @package  SchoolAthletics
 * @version  0.0.1
 */

namespace SchoolAthletics;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Schedule Class.
 */
class Schedule {

	/** @var int post ID for roster page. */
	public $ID = '';

	/** @var string post ID for roster page. */
	public $title = '';

	/** @var string post ID for roster page. */
	public $content = '';

	/** @var object sport that roster belongs to. */
	public $sport = array();

	/** @var object season that roster belongs to. */
	public $season = array();

	/** @var array options */
	public $options = array();

	/** @var array schedule page object */
	public $schedule = array();

	/** @var array List of sport schedules */
	public $schedules = array();

	/** @var array Contains schedule events */
	public $events = array(
		array(
					'ID'	 => 0,
					'date'  => '',
					'name'   => '',
					'result' => '',
					'location' => '',
					'game_type' => '',
					'outcome' => '',
					'order' => '',
				)
		);

	/** @var array Contains events errors */
	public $errors = array();

	/**
	 * Hook in methods.
	 */
	public function __construct($post = null) {
		if(!$post){
			$post = $this->get_schedule();
		}

		if(is_object($post)){
			$sports = get_the_terms($post,'sa_sport');
			$sport = $sports[0];
			$seasons = get_the_terms($post,'sa_season');
			$season = $seasons[0];
			$this->ID = $post->ID;
			$this->title = $post->post_title;
			$this->content = $post->post_content;
			$this->schedule = $post;
			$this->sport = $sport;
			$this->season = $season;
			$this->events = $this->get_events($sport->term_id,$season->term_id);
			$this->schedules = $this->get_schedules($sport->term_id);
		}
	}

	/**
	 * Get schedule
	 */
	public function get_schedule(){
		$sport = $_REQUEST['sport'];
		$season = $_REQUEST['season'];

		$args = array(
			'posts_per_page' => 1,
			'post_type' => 'sa_schedule',
			'tax_query' => array(
				array(
					'taxonomy' => 'sa_sport',
					'field' => 'id',
					'terms' => $sport, // Where term_id of Term 1 is "1".
				),
				array(
					'taxonomy' => 'sa_season',
					'field' => 'id',
					'terms' => $season,
				)
			),
	    );
		$roster = get_posts($args);
		return $roster[0];
	}

	/**
	 * Get Schedules
	 */
	public static function get_schedules($sport){
		$args = array(
			'posts_per_page' => -1,
			'post_type' => 'sa_schedule',
			'tax_query' => array(
				array(
					'taxonomy' => 'sa_sport',
					'field' => 'id',
					'terms' => $sport,
				),
			),
			'orderby' => 'taxonomy_sa_season',
			'order'   => 'ASC',
		);
		return get_posts($args);
	}

	/**
	 * Get Schedule Events
	 */
	public static function get_events($sport,$season){
		
		$args = array(
			'post_type' => 'sa_event',
			'numberposts' => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'sa_sport',
					'field' => 'id',
					'terms' => $sport, // Where term_id of Term 1 is "1".
				),
				array(
					'taxonomy' => 'sa_season',
					'field' => 'id',
					'terms' => $season,
				)
			),
			'meta_key' => 'sa_order',
			'orderby' => 'meta_value_num',
			'order' => 'ASC',
		);
		$_events = get_posts($args);

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

		return $events;

	}

}