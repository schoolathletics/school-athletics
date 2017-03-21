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

	public $version = SA_VERSION;
	
	/**
	 * School Athletics Constructor.
	 */
	public function __construct() {
		add_action('init', array( $this, 'init' ), 0 );
		add_action( 'widgets_init', array( $this, 'widgets') );
	}

	/**
	 * Get Sport
	 */
	public static function get_sport($post){
		$terms = wp_get_post_terms( $post->ID, 'sa_sport');
		return get_term( $terms[0]->term_id);
	}

	public function init(){
		\SchoolAthletics\InstallWpObjects::init();
		if(is_admin()){
			new \SchoolAthletics\Admin\Admin();
		}
		new \SchoolAthletics\Debug();
		new \SchoolAthletics\Api();
		\SchoolAthletics\Templates::init();
		\SchoolAthletics\Shortcodes::init();
	}

	public static function widgets(){
		include(SA__PLUGIN_DIR .'includes/widgets/class-UpcomingEvents.php');
  		register_widget( 'SA_Upcoming_Events_Widget' );
	}


}
