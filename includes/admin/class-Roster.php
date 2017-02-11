<?php 
/**
 * Roster Page.
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
 * Roster Class.
 */
class Roster extends Page{

	public function __construct(){
		parent::__construct();
		self::output();
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