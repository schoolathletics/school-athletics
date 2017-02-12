<?php
/**
 * Debug class.
 *
 *
 * @author   Dwayne Parton
 * @category Class
 * @package  SchoolAthletics
 * @version  0.0.1
 */

namespace SchoolAthletics; 


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Debug Class.
 */
class Debug {

	/**
	 * Hook in methods.
	 */
	public function __construct() {
		if(self::status()){
			
		}
	}

	/**
	 * Is Debugging On
	 */
	public static function status(){
		$status_options = get_option( 'schoolathletics_settings_options', array() );
		if ( ! empty( $status_options['debug_mode'] ) ) {
			return true;
		}else{
			return false;
		}
	}

	/**
	 * Is Debugging On
	 */
	public static function file_path($path){
		if(self::status()){
			echo '<p>Included From : <code>'.$path.'</code></p>';
		}else{
			return;
		}
	}

	/**
	 * Is Debugging On
	 */
	public static function content($data){
		if(self::status()){
			echo '<p><code>';
			if(is_array($data)){
				var_dump($data);
			}else{
				echo $data;
			}
			echo '</code></p>';
		}else{
			return;
		}
	}

	/**
	 * Print errors to console log
	 */
	public static function console_log( $data ){
		echo '<script>';
		echo 'console.log('. json_encode( $data ) .')';
		echo '</script>';
	}

}
