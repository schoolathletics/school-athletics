<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SA_Admin_Roster {

	public function __construct() {
		add_action("admin_enqueue_scripts", array($this,'enqueue_scripts') );
	}

	/**
	 * Handles output of the settings page in admin.
	 */
	public static function output() {
		include_once( 'views/html-admin-page-roster.php' );
	}

	public function enqueue_scripts(){
    	wp_enqueue_media();
	}


}

return new SA_Admin_Roster();