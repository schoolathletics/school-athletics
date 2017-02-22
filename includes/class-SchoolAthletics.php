<?php
/**
 * Initiate school athletics.
 *
 * @author   Dwayne Parton
 * @category Class
 * @package  SchoolAthletics
 * @version  0.0.1
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Main School Athletics Class.
 *
 */
class SchoolAthletics {
	
	/**
	 * School Athletics Constructor.
	 */
	public function __construct() {
		\SchoolAthletics\InstallWpObjects::init();
		if(is_admin()){
			new \SchoolAthletics\Admin\Admin();
		}
		new \SchoolAthletics\Debug();
		\SchoolAthletics\TemplateLoader::init();
		\SchoolAthletics\Shortcodes::init();
	}

	/**
	 * Get Sport
	 */
	public static function get_sport($post){
		$terms = wp_get_post_terms( $post->ID, 'sa_sport');
		return get_term( $terms[0]->term_id);
	}

	/**
	 * Get Season
	 */
	public static function get_season($post){
		$terms = wp_get_post_terms( $post->ID, 'sa_season');
		if(is_object($terms)){
			$term = get_term( $terms[0]->term_id);
		}
		if(!is_object($terms)){
			$term = self::get_current_season(self::get_sport($post));
		}
		return $term;
	}

	/**
	 * Get Schedule Events
	 */
	public static function get_schedule_events($sa_sport,$sa_season){
		if(is_object($sa_sport)){
			$sa_sport = $sa_sport->term_id;
		}
		if(is_object($sa_season)){
			$sa_season = $sa_season->term_id;
		}
		$args = array(
			'post_type' => 'sa_event',
			'numberposts' => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'sa_sport',
					'field' => 'id',
					'terms' => $sa_sport, // Where term_id of Term 1 is "1".
				),
				array(
					'taxonomy' => 'sa_season',
					'field' => 'id',
					'terms' => $sa_season,
				)
			),
			'meta_key' => 'sa_order',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
		);
		return get_posts($args);
	}

	/**
	 * Get the current roster with edit link.
	 */
	public static function get_current_season($sport){
		$pages = get_posts(array(
		  'post_type' => 'sa_roster',
		  'numberposts' => 0,
		  'tax_query' => array(
		    array(
		      'taxonomy' => 'sa_sport',
		      'field' => 'id',
		      'terms' => $sport->term_id, // Where term_id of Term 1 is "1".
		    )
		  ),
		  'orderby' => 'taxonomy_sa_season',
		  'order'   => 'ASC',
		));
		foreach ($pages as $page) {
			if(is_object($page)){
				if(is_object_in_term( $page->ID, 'sa_season')){
					$season = get_the_terms($page,'sa_season');
					$season = $season[0];
				}
			}
		}
		return $season;
	}

	/**
	 * Get Roster Members
	 */
	public static function get_roster_members($sa_sport,$sa_season){
		if(!is_object($sa_sport)){
			return;		
		}
		if(!is_object($sa_season)){
			$sa_season = self::get_current_season($sa_sport);
		}

		$args = array(
			'posts_per_page' => -1,
			'post_type' => 'sa_roster_member',
			'tax_query' => array(
				array(
					'taxonomy' => 'sa_sport',
					'field' => 'id',
					'terms' => $sa_sport->term_id, // Where term_id of Term 1 is "1".
				),
				array(
					'taxonomy' => 'sa_season',
					'field' => 'id',
					'terms' => $sa_season->term_id,
				)
			),
			'meta_key' => 'sa_order',
            'orderby' => 'meta_value_num',
            'order' => 'ASC'
		);
		return get_posts($args);
	}

}
