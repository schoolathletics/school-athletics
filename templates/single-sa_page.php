<?php
/**
 * The Template for displaying the sport homepage
 * 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<?php get_header(); ?>

<?php do_action( 'schoolathletics_before_content' ); ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php do_action( 'schoolathletics_content' ); ?>

		<?php endwhile; // end of the loop. ?>

<?php do_action( 'schoolathletics_after_content' ); ?>

<?php get_footer(); ?>