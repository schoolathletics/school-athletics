<?php 
/**
 * Settings Pages.
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
 * SettingsAdmin Class.
 */
class SettingsAdmin extends Page{

	public function __construct(){
		parent::__construct();
		self::output();
	}

	/**
	 * Handles output of the settings page in admin.
	 */
	public static function output() {
		include_once( 'views/html-admin-page-settings.php' );
	}

}
