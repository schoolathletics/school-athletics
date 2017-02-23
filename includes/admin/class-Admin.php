<?php 
/**
 * Initiate the school athletics admin. It all starts here.
 *
 * @author   Dwayne Parton
 * @category Admin
 * @package  SchoolAthletics/Admin
 * @version  0.0.1
 */
namespace SchoolAthletics\Admin;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin class.
 */
class Admin {

	/**
	 * Hook in methods.
	 */
	public function __construct() {
		add_filter( 'display_post_states', array( $this, 'post_states' ), 10, 2 );
		new \SchoolAthletics\Admin\Menus();
		add_filter('pre_get_posts', array( $this, 'sa_pages_admin' ));
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Displays the post state on the page post type
	 */
	public function post_states( $states, $post ) {
		if ( 'sa_page' == $post->post_type ) {
			if (! empty(get_post_meta($post->ID, 'schoolathletics_page_state', true)) ) {
				$states['schoolathletics'] = sprintf(
					'<span style="%2$s" >%1$s</span>',
					__( get_post_meta($post->ID, 'schoolathletics_page_state', true), 'schoolathletics' ),
					'background:#aaa;color:#fff;padding:1px 4px;border-radius:4px;font-size:0.8em'
				);
			}
		}

		return $states;
	}

	/**
	 * Advanced Options
	 */
	public static function advanced_mode(){
		$status_options = get_option( 'schoolathletics_settings_options', array() );
		if ( ! empty( $status_options['advanced_mode'] ) || \SchoolAthletics\Debug::status()) {
			return true;
		}else{
			return false;
		}
	}

	public function register_settings() {
		register_setting( 'schoolathletics_settings_fields', 'schoolathletics_settings_options' );
		register_setting( 'schoolathletics_your_school_fields', 'schoolathletics_your_school_options'); 
	} 

	/**
	 * Customize sa_pages Admin
	 */
	public static function sa_pages_admin(){
		global $wp_query;
		if (is_admin()) {
			// Get the post type from the query
			$post_type = $wp_query->query['post_type'];
			if ( $post_type == 'sa_page') {
				$wp_query->set('orderby', 'menu_order');
				$wp_query->set('order', 'DESC');
			}
		}
	}

}
