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
	public $events = array();

	/** @var array Contains events errors */
	public $errors = array();

	/**
	 * Hook in methods.
	 */
	public function __construct($post) {
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
		return get_posts($args);
	}

}