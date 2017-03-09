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
		new \SchoolAthletics\Admin\MetaBoxes();

		// Don't show Rosters, Athetes, or Schedules in page list
		add_filter('pre_get_posts', array( $this, 'sa_pages_admin' ));

		// Only show parent pages when editing page  
		add_filter( 'page_attributes_dropdown_pages_args', array( $this, 'top_level_pages_only') );

		// Also perform the same filter when doing a 'Quick Edit'  
		add_filter( 'quick_edit_dropdown_pages_args', array( $this, 'top_level_pages_only') );  

		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_init', array( $this, 'permalinks') );
		add_action( 'admin_init', array( $this, 'save_permalink_settings') );
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
	 * Hide Child Pages
	 */
    public static function top_level_pages_only( $args )  
    {  
        $args['depth'] = '1';  
        return $args;  
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
		//if () {
			// Get the post type from the query
			if ( $wp_query->query['post_type'] == 'sa_page' && !\SchoolAthletics\Debug::status()) {
				$tax_query =  array(
						'relation' => 'AND',
						array(
							'taxonomy' => 'sa_page_type',
							'field'    => 'slug',
							'terms'    => 'athlete',
							'operator' => 'NOT IN'
						),
						array(
							'taxonomy' => 'sa_page_type',
							'field'    => 'slug',
							'terms'    => 'roster',
							'operator' => 'NOT IN'
						),
						array(
							'taxonomy' => 'sa_page_type',
							'field'    => 'slug',
							'terms'    => 'schedule',
							'operator' => 'NOT IN'
						),
					);
				$wp_query->set('tax_query', $tax_query);
			}
		//}
	}

	public function permalinks(){
		// Add our settings
		add_settings_field(
			'schoolathletics_sports_base',             // id
			__( 'Sports Base', 'school-athletics' ),   // setting title
			array( $this, 'schoolathletics_sports_base' ),       // display callback
			'permalink',                               // settings page
			'optional'                                 // settings section
		);
	}

	/**
	 * Show a slug input box.
	 */
	public function schoolathletics_sports_base() {
		$permalinks = get_option( 'schoolathletics_permalinks' );
		?>
		<input name="schoolathletics_sports_base" type="text" class="regular-text code" value="<?php if ( isset($permalinks['base']) ) echo esc_attr( $permalinks['base'] ); ?>" placeholder="<?php echo esc_attr_x('sports', 'slug', 'school-athletics') ?>" />
		<?php
	}

	function save_permalink_settings(){

		if( isset($_POST['permalink_structure']) && isset( $_POST['schoolathletics_sports_base'] ) ){
			$permalinks = array();
			$permalinks['base'] = wp_unslash( $_POST['schoolathletics_sports_base'] );
			update_option( 'schoolathletics_permalinks',  $permalinks );
  		} 
	}


}
