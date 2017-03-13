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

	public $page_type = 'schedule';

	/** @var int post ID for roster page. */
	public $ID = '';

	/** @var string post ID for roster page. */
	public $title = '';

	/** @var string post ID for roster page. */
	//public $content = '';

	/** @var int thumbnail ID. */
	//public $thumbnail = '';

	/** @var string sport that roster belongs to. */
	public $sport = '';

	/** @var int sport that roster belongs to. */
	public $sport_id = '';

	/** @var object season that roster belongs to. */
	public $season = '';

	/** @var object season that roster belongs to. */
	public $season_id = '';

	/** @var object season that roster belongs to. */
	public $permalink = '';

	/** @var array options */
	public $options = array();

	/** @var array Contains roster errors */
	public $errors = array();

	/**
	 * Hook in methods.
	 */
	public function __construct($post = null) {
		$season = null;

		if(is_object($post)){
			$sports = get_the_terms($post,'sa_sport');
			$sport = $sports[0]->term_id;
		}else{
			$sport = $_REQUEST['sport'];
			$season = $_REQUEST['season'];
		}

		if(is_object($post) && has_term('schedule','sa_page_type')){
			$seasons = get_the_terms($post,'sa_season');
			$season = $seasons[0]->term_id;
		}
		
		$this->get_schedule($sport, $season);
	}

	/**
	 * Get schedule
	 */
	public function get_schedule($sport, $season = null){
		$args = array();
		$args['posts_per_page'] = 1;
		$args['post_type'] = 'sa_page';
		$args['orderby'] = 'title';
		$args['order'] = 'DESC';
		$args['tax_query'] = array();
		$args['tax_query'][] = array(
								'taxonomy' => 'sa_sport',
								'field' => 'id',
								'terms' => $sport, // Where term_id of Term 1 is "1".
							);
		$args['tax_query'][] = array(
								'taxonomy' => 'sa_page_type',
								'field' => 'name',
								'terms' => 'Schedule',
							);
		if(isset($season)){
		$args['tax_query'][] = array(
								'taxonomy' => 'sa_season',
								'field' => 'id',
								'terms' => $season, // Where term_id of Term 1 is "1".
							);
		}
		$schedules = get_posts($args);
		foreach ($schedules as $schedule) {
			if(is_object($schedule)){
				$sports = get_the_terms($schedule,'sa_sport');
				$sport = $sports[0];
				$seasons = get_the_terms($schedule,'sa_season');
				$season = $seasons[0];
				$this->ID = $schedule->ID;
				$this->title = $schedule->post_title.' '.$sport->name.' '.__('Schedule', 'school-athletics');
				//$this->content = $roster->post_content;
				$this->season = $season->name;
				$this->season_id = $season->term_id;
				$this->sport = $sport->name;
				$this->sport_id = $sport->term_id;
				//$this->thumbnail = get_post_thumbnail_id($schedule->ID);
				$this->permalink = get_permalink($schedule->ID);
				return true;
			}else{
				return false;
			}
		}
		$sport = get_term($sport);
		$season = get_term($season);
		$this->title = $season->name.' '.$sport->name.' '.__('Schedule', 'school-athletics');
		$this->season = $season->name;
		$this->season_id = $season->term_id;
		$this->sport = $sport->name;
		$this->sport_id = $sport->term_id;
	}

	/**
	 * Get Schedules
	 */
	public static function get_schedules($sport){
		$args = array(
			'posts_per_page' => -1,
			'post_type' => 'sa_page',
			'tax_query' => array(
				array(
					'taxonomy' => 'sa_sport',
					'field' => 'id',
					'terms' => $sport,
				),
				array(
					'taxonomy' => 'sa_page_type',
					'field' => 'name',
					'terms' => 'Schedule',
				),
			),
			'orderby' => 'title',
			'order'   => 'DESC',
		);
		$posts = get_posts($args);
		$schedules = array();
		foreach ($posts as $post) {
			$schedule = array();
			$schedule['ID'] = $post->ID;
			$season = get_the_terms( $post, 'sa_season' );
			$schedule['season'] = $season[0]->slug;
			$schedule['title'] = $post->post_title;
			$schedule['permalink'] = get_permalink($post->ID);
			$schedules[] = $schedule;
		}
		return $schedules;
	}

	/**
	 * Get Schedule Events
	 */
	public function get_events(){
		
		$args = array(
			'post_type' => 'sa_event',
			'numberposts' => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'sa_sport',
					'field' => 'id',
					'terms' => $this->sport_id, // Where term_id of Term 1 is "1".
				),
				array(
					'taxonomy' => 'sa_season',
					'field' => 'id',
					'terms' => $this->season_id,
				)
			),
			'meta_key' => 'sa_start',
			'orderby' => 'meta_value',
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
		if(empty($events)){
			return array(
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
		}else{
			return $events;
		}

	}

	/**
	 * Creates a schedule list from the schedules object.
	 */
	public function dropdown(){

		if(is_object($this)){
			$schedules = $this::get_schedules($this->sport_id);
			echo '<select class="select" onChange="window.location.href=this.value">';
				foreach ($schedules as $schedule) {
					if($this->ID == $schedule['ID']){
						$selected = 'selected';
					}else{
						$selected = '';
					}
					echo '<option value="'.$schedule['permalink'].'" '.$selected.'>'.$schedule['season'].'</option>';
				}
			echo '</select>';
		}
	}

}