<?php 
/**
 * SchoolAthletics Page.
 *
 * @author   Dwayne Parton
 * @category Admin
 * @package  SchoolAthletics/Admin
 * @version  0.0.1
 */

namespace SchoolAthletics\Admin\Pages;
use SchoolAthletics\Admin\Page as Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * SchoolAthletics admin page class.
 */
class SchoolAthletics extends Page{

	public function __construct(){
		parent::__construct();
		self::output();
	}

	/**
	 * Handles output of the settings page in admin.
	 */
	public static function output() {
		include_once( 'views/html-admin-page-schoolathletics.php' );
	}

}
