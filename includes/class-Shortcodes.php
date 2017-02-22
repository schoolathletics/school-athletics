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
			'schoolathletics_news' => __CLASS__ . '::news',
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
	public static function news($atts){
		$atts = shortcode_atts( array(
				'sport' => '',
				'posts_per_page'=> '-5',
			), $atts );

		$args = array(
			'post_type' 		=> 'post',
			'posts_per_page'    => $atts['posts_per_page'],
			'tax_query' 		=> array(
									array(
										'taxonomy' => 'sa_sport',
										'field' => 'name',
										'terms' => $atts['sport'], // Where term_id of Term 1 is "1".
									),
									),
			'post_status'         => 'publish',
			'order'               => 'DESC',
		);
		
		//$news = get_posts($args);
		ob_start();
		query_posts($args);
   
		// Reset and setup variables
		$output = '';
		$temp_title = '';
		$temp_link = '';
		if ( have_posts() ) : while ( have_posts() ) : the_post();
		global $post;
		?>
			<div>
				<h2 href='<?php echo get_permalink($post->ID); ?>'><?php the_title(); ?></h2>
				<div><?php the_content(); ?></div>
			</div>
			<div class="clearfix"></div>

        <?php  
		endwhile; else:
   
			$output .= "nothing found.";
      
		endif;
		
		wp_reset_query();
		return ob_get_clean();
	}



}
