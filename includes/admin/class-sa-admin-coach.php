<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SA_Admin_Coach {

	/**
	 * Handles output of the settings page in admin.
	 */
	public static function output() {
			include_once( 'views/html-admin-page-coach.php' );
	}

}
