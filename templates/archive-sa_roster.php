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

			<h1>
			<?php the_title(); ?>
			</h1>
			<?php the_content(); ?>

			<?php 
				$sport = SchoolAthletics::get_sport($post);
				$season = SchoolAthletics::get_season($post);
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
					  'order'   => 'ASC',
					);
				query_posts($args);
			?>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php include(SA__PLUGIN_DIR .'templates/loop/roster.php'); ?>

			<?php endwhile; // end of the loop. ?>

			<?php wp_reset_query(); ?>

		<?php endwhile; // end of the loop. ?>

		<?php 
			\SchoolAthletics\Debug::file_path(SA__PLUGIN_DIR .'includes/templates/single-sa_page-roster.php');
			\SchoolAthletics\Debug::content($_REQUEST); 
		?>

<?php get_footer(); ?>
