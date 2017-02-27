<?php
/**
 * The Template for displaying the sport homepage
 * 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header(); ?>

<?php include(SA__PLUGIN_DIR .'templates/inc/schoolathletics-menu.php'); ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php include(SA__PLUGIN_DIR .'templates/loop/schedule.php'); ?>

		<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
