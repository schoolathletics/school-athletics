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

	/**
	 * Hook in methods.
	 */
	public function __construct() {

	}

	/**
	 * Get Schedule Events
	 */
	public static function getEvents($sport,$season){
		$args = array(
			'post_type' => 'sa_event',
			'numberposts' => -1,
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
			'meta_key' => 'sa_date',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
		);
		return get_posts($args);
	}

}