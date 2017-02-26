<?php
/**
 * School Athletics shortcode class.
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
 * School Athletics shortcode class.
 */
class Shortcodes {

	/**
	 * Init shortcodes.
	 */
	public static function init() {
		$shortcodes = array(
			'schoolathletics' => __CLASS__ . '::schoolathletics',
		);

		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( $shortcode, $function );
		}

	}

	/**
	 * News shortcode.
	 *
	 * @param mixed $atts
	 * @return string
	 */
	public static function schoolathletics($atts){
		$atts = shortcode_atts( array(
				'page' => '',
			), $atts );
		switch ($atts['page']) {
			case 'home':
				self::news();
				break;

			case 'roster':
				self::roster();
				break;

			case 'schedule':
				self::schedule();
				break;
			
			case 'staff':
				self::staff();
				break;

			default:
				# code...
				break;
		}

	}

	public static function roster(){
		global $post;

		$sport = \SchoolAthletics::get_sport($post);
		//$season = \SchoolAthletics::get_season($post);
		$args = array(
					  'post_type' => 'sa_roster',
					  'posts_per_page' => 1,
					  'tax_query' => array(
					    array(
					      'taxonomy' => 'sa_sport',
					      'field' => 'id',
					      'terms' => $sport->term_id, // Where term_id of Term 1 is "1".
					    )
					  ),
					  'orderby' => 'taxonomy_sa_season',
					  'order'   => 'DESC',
					);
		query_posts($args);
		
		while ( have_posts() ) : the_post();

			include(SA__PLUGIN_DIR .'templates/loop/roster.php');

		endwhile; // end of the loop. 

		 wp_reset_query();

	}

	public static function schedule(){
		global $post;

		$sport = \SchoolAthletics::get_sport($post);
		//$season = \SchoolAthletics::get_season($post);
		$args = array(
					  'post_type' => 'sa_schedule',
					  'posts_per_page' => 1,
					  'tax_query' => array(
					    array(
					      'taxonomy' => 'sa_sport',
					      'field' => 'id',
					      'terms' => $sport->term_id, // Where term_id of Term 1 is "1".
					    )
					  ),
					  'orderby' => 'taxonomy_sa_season',
					  'order'   => 'ASC',
					);
		query_posts($args);
		
		while ( have_posts() ) : the_post();

			include(SA__PLUGIN_DIR .'templates/loop/schedule.php');

		endwhile; // end of the loop. 

		 wp_reset_query();

	}

	public static function staff(){
		global $post;

		$sport = \SchoolAthletics::get_sport($post);
		$args = array(
					  'post_type' => 'sa_staff',
					  'posts_per_page' => 1,
					  'tax_query' => array(
					    array(
					      'taxonomy' => 'sa_sport',
					      'field' => 'id',
					      'terms' => $sport->term_id, // Where term_id of Term 1 is "1".
					    )
					  ),
					  'order'   => 'ASC',
					);
		query_posts($args);
		
		while ( have_posts() ) : the_post();

			include(SA__PLUGIN_DIR .'templates/loop/staff.php');

		endwhile; // end of the loop. 

		 wp_reset_query();

	}

	/**
	 * News shortcode.
	 *
	 * @param mixed $atts
	 * @return string
	 */
	public static function news(){
		global $post;

		$sport = \SchoolAthletics::get_sport($post);
		$args = array(
			'post_type' 		=> 'post',
			'posts_per_page'    => '5',
			'tax_query' 		=> array(
									array(
										'taxonomy' => 'sa_sport',
										'field' => 'id',
										'terms' => $sport->term_id, // Where term_id of Term 1 is "1".
									),
									),
			'post_status'         => 'publish',
			'order'               => 'DESC',
		);
		
		//ob_start();
		query_posts($args);
   
		if ( have_posts() ) : while ( have_posts() ) : the_post();
		
			include(SA__PLUGIN_DIR .'templates/loop/news.php');

		endwhile; else:
   
			echo "Nothing found.";
      
		endif;
		
		wp_reset_query();
		//return ob_get_clean();
	}



}
