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

			<?php wp_get_archives(array(
				'type' => 'postbypost', 
				'limit' => '5',
				'format' => 'html', 
				'before' => '',
				'after' => '', 
				'show_post_count' => false,
				'echo' => 1, 
				'order' => 'DESC',
				'post_type' => 'sa_roster'
			)); ?>

		<?php endwhile; // end of the loop. ?>

		<?php 
			\SchoolAthletics\Debug::file_path(SA__PLUGIN_DIR .'includes/templates/single-sa_page-roster.php');
			\SchoolAthletics\Debug::content($_REQUEST); 
		?>

<?php get_footer(); ?>
