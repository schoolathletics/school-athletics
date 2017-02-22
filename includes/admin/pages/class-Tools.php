<?php 
/**
 * Tools Pages.
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
 * Tools admin page class.
 */
class Tools extends Page{

	public function __construct(){
		parent::__construct();
		self::output();
	}

	/**
	 * Handles output of the settings page in admin.
	 */
	public static function output() {
		if(isset($_POST['task'])){
			switch ($_POST['task']) {
				case 'rebuild':
					self::rebuild();
					break;
				
				default:
					# code...
					break;
			}
		}

		include_once( 'views/html-admin-page-tools.php' );
	}

	/**
	 * Handles pages rebuild.
	 */
	public static function rebuild(){
		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'schoolathletics-tools-rebuild' ) ) {
			\SchoolAthletics\Admin\Notice::error(__( 'Failed to rebuild sports. Please refresh the page and retry.', 'school-athletics' ));
		}

		$sports = \SchoolAthletics\Sport::get_sports();
		$message = __('Pages successfully rebuilt:<ul>');
		foreach ($sports as $sport) {
			
			$message .= \SchoolAthletics\Admin\Pages\Sports::build($sport);

		}
		$message .= '</ul>';

		\SchoolAthletics\Admin\Notice::success($message);
	}


}
