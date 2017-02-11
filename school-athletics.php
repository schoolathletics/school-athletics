<?php
/**
 * Plugin Name: School Athletics
 * Plugin URI: https://schoolathletics.org/
 * Description: High School, Collegiate, and University Athletics for Wordpress.
 * Version: 1.5
 * Author: Dwayne Parton
 * Author URI: https://dwayneparton.com
 * Requires at least: 4.4
 * Tested up to: 4.7
 *
 * Text Domain: school-athletics
 * Domain Path: 
 *
 * @package SchoolAthletics
 * @category Core
 * @author Dwayne Parton
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'SchoolAthletics' ) ) :

/**
 * Main SportsPro Class.
 *
 * @class SportsPro
 * @version	1.0
 */
final class SchoolAthletics {
	
	/**
	 * The single instance of the class.
	 *
	 * @var SportsPro
	 */
	protected static $_instance = null;


	/**
	 * Main SportsPro Instance.
	 *
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * SportsPro Constructor.
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
	}

	/**
	 * Define SportsPro Constants.
	 */
	private function define_constants() {
		define( 'SA__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		define( 'SA__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	private function includes(){
		require SA__PLUGIN_DIR . 'includes/class-sa-core.php';
		require SA__PLUGIN_DIR . 'includes/admin/class-sa-admin.php';
	}

	public static function debug(){
		$status_options = get_option( 'schoolathletics_settings_options', array() );
		if ( ! empty( $status_options['debug_mode'] ) ) {
			return true;
		}else{
			return false;
		}
	}

	public static function debug_file_path($path){
		if(self::debug()){
			echo '<p>Included From : <code>'.$path.'</code></p>';
		}else{
			return;
		}
	}

	public static function debug_content($content){
		if(self::debug()){
			echo '<p><code>';
			if(is_array($content)){
				var_dump($content);
			}else{
				echo $content;
			}
			echo '</code></p>';
		}else{
			return;
		}
	}

}

endif;

/**
 * Main instance of SportsPro.
 *
 * @return SportsPro
 */
function SchoolAthletics() {
	return SchoolAthletics::instance();
}

SchoolAthletics();
