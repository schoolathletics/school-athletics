<?php
/**
 * Debug class.
 *
 * @class    Debug
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
 * SA_Install Class.
 */
class Debug {

	/**
	 * Hook in methods.
	 */
	public function __construct() {
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

	public static function file_path($path){
		if(self::status()){
			echo '<p>Included From : <code>'.$path.'</code></p>';
		}else{
			return;
		}
	}

	public static function content($content){
		if(self::status()){
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
