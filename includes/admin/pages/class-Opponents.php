<?php 
/**
 * Organizations Page.
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
 * Organizations admin page class.
 */
class Opponents extends Page{

	public function __construct(){
		parent::__construct();
		self::output();
	}

	/**
	 * Handles output of the settings page in admin.
	 */
	public static function output() {
		if(!empty($_GET['action']) && $_GET['action'] == 'delete'){
			self::delete();
		}
		include_once( 'views/html-admin-page-opponents.php' );
	}

	/**
	 * Handles output of the settings page in admin.
	 */
	public static function edit() {
		if($_POST){
			self::save();
		}
		include_once( 'views/html-admin-page-opponents-edit.php' );
	}

	/**
	 * Handles output of the settings page in admin.
	 */
	public static function get_list() {
		include_once( 'views/html-admin-page-opponents-list.php' );
	}

	public static function save(){

		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'schoolathletics-save-opponent' ) ) {
			\SchoolAthletics\Admin\Notice::error(__( 'Failed to save opponent. Please refresh the page and retry.', 'school-athletics' ));
			die;
		}

		if(!isset($_REQUEST['opponent'])){
			$opponent = wp_insert_term( $_REQUEST['name'], 'sa_opponent');
			$opponent = $opponent['term_id'];
		}else{
			$opponent = $_REQUEST['opponent'];
		}

		update_term_meta($opponent, 'sa_opponent_options', $_REQUEST['sa_opponent_options']);
		$_REQUEST['opponent'] = $opponent;

		\SchoolAthletics\Admin\Notice::success('Opponent has been added.');

	}

	/**
	 * Delete sport
	 */
	public static function delete() {
		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'schoolathletics-delete-opponent' ) ) {
			\SchoolAthletics\Admin\Notice::error(__( 'Failed to delete. Please refresh the page and retry.', 'school-athletics' ));
			die;
		}

		wp_delete_term( $_GET['opponent'], 'sa_opponent' );

		\SchoolAthletics\Admin\Notice::success('Opponent has been deleted.');
	}

}
