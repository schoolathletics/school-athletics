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
	 * @return boolean
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
	 * If Debug on pring content.
	 * @param mixed $data
	 * @return string
	 */
	public static function content($data){
		if(self::status()){
			echo '<pre class="debug">';
			if(is_array($data) || is_object($data)){
				var_dump($data);
			}else{
				echo $data;
			}
			echo '</pre>';
		}else{
			return;
		}
	}

	/**
	 * Adds Included From message to file path. Really not needed.
	 */
	public static function file_path($path){
		echo self::content('Included From : <code>'.$path.'</code>');
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
