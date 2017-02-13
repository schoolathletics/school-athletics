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
		if($_POST){
			if(isset($_POST['action']) && $_POST['action'] == 'import'){
				$import = \SchoolAthletics\Admin\Page::parse_csv($_POST['csv']);
				\SchoolAthletics\Admin\Notice::warning(__( 'Nothing has been saved yet. First, make sure your import looks right and then click <i>Save Changes</i> to add athletes to the roster. If things look wrong try updating your csv code and reimporting.', 'school-athletics' ));
			}
		}
		include_once( 'views/html-admin-page-roster.php' );
	}

	public function enqueue_scripts(){
    	wp_enqueue_media();
	}

	public function import(){

	}

}