<?php 
/**
 * Staff Page.
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
 * Staff page class.
 */
class Staff extends Page{

	public function __construct(){
		parent::__construct();
		self::output();
	}

	/**
	 * Handles output of the settings page in admin.
	 */
	public static function output() {
		include_once( 'views/html-admin-page-staff.php' );
	}

	public static function get_list() {
		include_once( 'views/html-admin-page-staff-list.php' );
	}

	public static function edit() {
		include_once( 'views/html-admin-page-staff-edit.php' );
	}

}