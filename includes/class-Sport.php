<?php 
/**
 * Main sport class.
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
 * Sport Class.
 */
class Sport {

	/** @var int post ID for roster page. */
	public $ID = '';

	/** @var string post ID for roster page. */
	public $title = '';

	/** @var object sport that roster belongs to. */
	public $sport = array();

	/** @var array of roster objects. */
	public $rosters = array();

	/** @var array of schedule objects. */
	public $schedule = array();

	/** @var array of staff objects. */
	public $staff = array();

	/**
	 * Hook in methods.
	 */
	public function __construct() {
	}

	/*
	 * Get Sports
	 */
	public static function get_sports(){
		$sports = get_terms( array(
			'taxonomy' => 'sa_sport',
			'hide_empty' => false,
		) );
		return $sports;
	}

	/*
	 * Get Single sport
	 */
	public static function get_sport($sport){
		if(!is_object($sport)){
			$sport = get_term($sport);
		}
		return $sport;
	}

	/*
	 * Get Sport ID form the Sport Object
	 */
	public function get_sport_id($sport){
		if(is_object($sport)){
			$sport = $sport->term_id;
		}
		return $sport;
	}

	/*
	 *  Sport defaults
	 */
	public static function sport_default_options(){
		$defaults = array(
					'roster'                  => 0,
					'roster_has_number'       => 0, 
					'roster_has_position'     => 0,
					'roster_has_height'       => 0,
					'roster_has_weight'       => 0, 
					'roster_has_year'         => 0,
					'schedule'                => 0,
					'schedule_has_opponents'  => 0, 
					'schedule_has_game_types' => 0,
					'schedule_keep_record'    => 0,
					'staff'                   => 0,
				);
		return $defaults;
	}

	/*
	 *  Get sport options
	 */
	public static function get_sport_options($sport){
		if(is_object($sport)){
			$sport = $sport->term_id;
		}
		$options = get_term_meta( $sport, 'sa_sport_options', true);
		$defaults = self::sport_default_options();
		$options = wp_parse_args( $options,$defaults );
		return $options;
	}

	/*
	 *  Get sport page id
	 */
	public static function get_sport_page_id($sport,$page){
		$sport = self::get_sport($sport);
		$page_id = get_term_meta( $sport->term_id, 'sa_sport_'.$page.'_id', true);
		return $page_id;
	}

	/**
	 * Get Rosters
	 */
	/*public static function get_rosters($sport){
		$args = array(
			'posts_per_page' => -1,
			'post_type' => 'sa_roster',
			'tax_query' => array(
				array(
					'taxonomy' => 'sa_sport',
					'field' => 'id',
					'terms' => $sport,
				),
			),
			'orderby' => 'taxonomy_sa_season',
			'order'   => 'DESC',
		);
		$posts = get_posts($args);
		$rosters = array();
		foreach ($posts as $post) {
			$rosters[] = new \SchoolAthletics\Roster($post);
		}
		return $rosters;
	}*/


}