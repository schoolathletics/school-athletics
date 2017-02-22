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

	/**
	 * Hook in methods.
	 */
	public function __construct() {
	}

	public static function get_sports(){
		$sports = get_terms( array(
			'taxonomy' => 'sa_sport',
			'hide_empty' => false,
		) );
		return $sports;
	}

	public function getSport($sport){
		if(!is_object($sport)){
			$sport = get_term($sport);
		}
		return $sport;
	}

	public static function get_sport($sport){
		if(!is_object($sport)){
			$sport = get_term($sport);
		}
		return $sport;
	}

	public static function sport_default_options(){
		$defaults = array(
					'page_id'                 => 0,
					'roster'                  => 0,
					'roster_id'               => 0,
					'roster_has_number'       => 0, 
					'roster_has_position'     => 0,
					'roster_has_height'       => 0,
					'roster_has_weight'       => 0, 
					'roster_has_year'         => 0,
					'schedule'                => 0,
					'schedule_id'             => 0,
					'schedule_has_opponents'  => 0, 
					'schedule_has_game_types' => 0,
					'schedule_keep_record'    => 0,
					'staff'                   => 0,
					'staff_id'                => 0,
				);
		return $defaults;
	}

	public static function get_sport_options($sport){
		if(is_object($sport)){
			$sport = $sport->term_id;
		}
		$options = get_term_meta( $sport, 'sa_sport_options', true);
		$defaults = self::sport_default_options();
		$options = wp_parse_args( $options,$defaults );
		return $options;
	}

	public static function get_sport_page_id($sport,$page){
		$sport = self::get_sport($sport);
		$page_id = get_term_meta( $sport->term_id, 'sa_sport_'.$page.'_id', true);
		return $page_id;
	}

	/*
	 * Get Sport ID form the Sport Object
	 */
	public function getSportID($sport){
		if(is_object($sport)){
			$sport = $sport->term_id;
		}
		return $sport;
	}

}