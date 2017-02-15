<?php 
/**
 * Main roster class.
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
 * Roster Class.
 */
class Roster {

	/**
	 * Hook in methods.
	 */
	public function __construct() {

	}

	/**
	 * Get Roster Object
	 */
	public static function getMembers($sport,$season){
		$args = array(
			'posts_per_page' => -1,
			'post_type' => 'sa_roster_member',
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
            'order' => 'ASC'
		);
		return get_posts($args);
	}

}