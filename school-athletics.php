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

define( 'SA__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SA__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

spl_autoload_register(function( $class) {
	//if ( strpos( $class, 'SchoolAthletics' ) ) {
	$namespaces = explode('\\', $class);
	if(in_array('SchoolAthletics', $namespaces)){
			$path = '';
			foreach ($namespaces as $namespace) {
				if($namespace == 'Admin'){
					$path = 'admin/';
				}
				if($namespace == 'Pages'){
					$path = 'admin/pages/';
				}
				$file = $namespace;
			}
			//$file = in_array('SchoolAthletics', $namespaces).$class;
		    $class_file = SA__PLUGIN_DIR . 'includes/'. $path .'class-'. $file  . '.php';
		    require_once $class_file;
	}
});

new \SchoolAthletics\SchoolAthletics();