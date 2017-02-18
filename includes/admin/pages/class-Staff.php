<?php 
/**
 * Staff Page.
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
 * Staff admin page class.
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

	/**
	 * List view
	 */
	public static function get_list() {
		include_once( 'views/html-admin-page-staff-list.php' );
	}

	/**
	 * Edit view
	 */
	public static function edit() {
		if ( $_POST ) {
			self::save();
			\SchoolAthletics\Admin\Notice::success('Staff member has been successfully saved.');
		}
		
		if(!empty($_GET['staff'])){
			$staff = self::getSingle($_GET['staff']);
		}else{
			$staff = '';
		}

		$autocomplete = self::autocomplete();

		include_once( 'views/html-admin-page-staff-edit.php' );
	}

	/**
	 * Autocomplete
	 */
	public static function autocomplete(){
		//Defaults for athletes
		$options = get_terms( array(
			'taxonomy' => 'sa_person',
			'hide_empty' => false,
		));
		$autocomplete = '';
		if (!empty($options)) {
			foreach ($options as $option) {
				if(isset($i)){
					$autocomplete .= ',';
				}
				$autocomplete .= '"'.addslashes ($option->name).'"';
				$i = true;
			}
		}
		return $autocomplete;
	}

	/**
	 * Save staff
	 */
	public static function save(){

		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'schoolathletics-edit-staff' ) ) {
			\SchoolAthletics\Admin\Notice::error(__( 'Failed to save staff. Please refresh the page and retry.', 'school-athletics' ));
			return false;
		}

		$staff = array(
			'ID' => $_REQUEST['ID'],
			'_thumbnail_id'=>$_REQUEST['photo'] ,//Not documented but nice to know
			'post_content' => $_REQUEST['content'],
			'post_title' => $_REQUEST['name'],
			'post_type' => 'sa_staff',
			'post_status' => 'publish',//publish
			'tax_input' => array(
					'sa_person' => $_REQUEST['name'],
				),
			'meta_input' => array(
					'sa_job_title' => $_REQUEST['job_title'],
				),
		);
		$post_id = wp_insert_post($staff);
		$terms = array_map('intval', $_REQUEST['tax_input']['sa_sport']);
		wp_set_object_terms( $post_id, $terms, 'sa_sport' );
		return $_GET['staff'] = $post_id;
	}

	/**
	 * Staff defaults
	 */
	public static function staffDefaults(){
		//Defaults for staff members
		$defaults = array(
				'ID'     => 0,
				'name'   => '',
				'job_title' => '',
				'sport' => '',
				'bio' => '',
			);
		return $defaults;
	}

	public static function getSingle($id) {
		
		$staff = get_post($id);
		return $staff;

	}

}