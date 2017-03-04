<?php
/**
 * Upcoming Events widget class.
 *
 *
 * @author   Dwayne Parton
 * @category Class
 * @package  SchoolAthletics
 * @version  0.0.1
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Upcoming Events widget class.
 */
class SA_Upcoming_Events_Widget extends WP_Widget  {

	public function __construct() {
	$widget_options = array( 
		'classname' => 'sa_upcoming_events_widget',
		'description' => 'Upcoming Events for School Athletics',
	);
	parent::__construct( 'sa_upcoming_events', 'Upcoming Events', $widget_options );
	}

	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : ''; ?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
		<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
		</p><?php 
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
		return $instance;
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance[ 'title' ] );
		$blog_title = get_bloginfo( 'name' );
		$tagline = get_bloginfo( 'description' );
		echo $args['before_widget'] . $args['before_title'] . $title . $args['after_title'];
		self::upcoming_events();
		echo $args['after_widget'];
	}

	public static function upcoming_events(){
		global $post;

		$sport = \SchoolAthletics::get_sport($post);
		$todaysDate = date('d-m-y');
		$todaysString = strtotime($todaysDate);
		$args = array(
					  'post_type' => 'sa_event',
					  'posts_per_page' => 5,
					  'meta_query' => array(
							array(
								'key' => 'sa_start',
								'value' => date("Y-m-d"), // Set today's date (note the similar format)
								'compare' => '>=',
								'type' => 'DATE'
							)
					   ),
					  'meta_key' => 'sa_start',
					  'orderby' => 'meta_value',
					  'order' => 'ASC',
					);
		query_posts($args);
		
		if ( have_posts() ) : while ( have_posts() ) : the_post();

			include(SA__PLUGIN_DIR .'templates/loop/upcoming_events.php');

		endwhile; else:
   
			_e('No Upcoming Events ','school-athletics');
      
		endif; // end of the loop. 

		 wp_reset_query();
	}


}
