<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SA_Admin_Staff {

	/**
	 * Handles output of the settings page in admin.
	 */
	public static function output() {
			include_once( 'views/html-admin-page-staff.php' );
	}

	public static function default() {
			include_once( 'views/html-admin-page-staff-list.php' );
	}

	public static function edit() {
			include_once( 'views/html-admin-page-staff-edit.php' );
	}

}
