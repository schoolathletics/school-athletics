<?php
/**
 * Adds installation data. Default term values, and content.
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
 * Install class.
 */
class Install {

	/**
	 * Hook in methods.
	 */
	public function __construct() {
	}

	/**
	 * Add the default terms for School Athletics. Modify this at your own risk.
	 */
	private static function create_terms() {
		$taxonomies = array(
			'sa_sport' => array(
				'Baseball',
				'Men\'s Basketball',
				'Men\'s Cross Country',
				'Football',
				'Golf',
				'Men\'s Soccer',
				'Men\'s Track & Field',
				'Wrestling',
				'Cheer',
				'Band',
				'Women\'s Basketball',
				'Women\'s Cross Country',
				'Women\'s Soccer',
				'Softball',
				'Women\'s Track & Field',
				'Volleyball',
			),
			'sa_roster_member_type' => array(
				'Athlete',
				'Coach',
			)
			'sa_season' => array(
				'1999-00',
				'2000-01',
				'2001-02',
				'2002-03',
				'2003-04',
				'2004-05',
				'2005-06',
				'2006-07',
				'2007-08',
				'2008-09',
				'2009-10',
				'2010-11',
				'2011-12',
				'2012-13',
				'2013-14',
				'2014-15',
				'2015-16',
				'2016-17',
				'2017-18',
			),
			'sa_athlete_status' => array(
				'Freshman',
				'Sophomore',
				'Junior',
				'Senior',
			),
			'sa_event_type' => array(
				'Game',
			),
			'sa_game_type' => array(
				'Scrimmage',
				'Non-Conference',
				'Conference',
				'Playoff',
				'Tournament',
			),
			'sa_outcome' => array(
				'Win',
				'Loss',
				'Tie',
			)
			'sa_location' => array(
				'Home',
				'Away',
				'Nuetral',
			)

		);

		foreach ( $taxonomies as $taxonomy => $terms ) {
			foreach ( $terms as $term ) {
				if ( ! get_term_by( 'slug', sanitize_title( $term ), $taxonomy ) ) {
					wp_insert_term( $term, $taxonomy );
				}
			}
		}
	}


}
