<?php
/**
 * The Template for displaying the sport homepage
 * 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header(); ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<h1>
			<?php the_title(); ?>
			</h1>
			<p>The Roster!</p>
			<?php the_content(); ?>
			<?php 
			\SchoolAthletics\Debug::file_path(SA__PLUGIN_DIR .'includes/templates/single-sa_page-roster.php');
			\SchoolAthletics\Debug::content($_REQUEST); 
			?>

		<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
