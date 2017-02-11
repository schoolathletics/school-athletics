<?php 
/**
 * Schedule Page.
 *
 * @author   Dwayne Parton
 * @category Admin
 * @package  SchoolAthletics/Admin
 * @version  0.0.1
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * SA_Admin_Schedule Class.
 */
class SA_Admin_Schedule {

	/**
	 * Handles output of the settings page in admin.
	 */
	public static function output() {
		include_once( 'views/html-admin-page-schedule.php' );
	}

}
