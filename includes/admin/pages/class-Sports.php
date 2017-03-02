<?php 
/**
 * Sports Page.
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
 * Sports admin page class.
 */
class Sports extends Page{

	public function __construct(){
		parent::__construct();
		self::output();
	}
	
	/**
	 * Handles output of the sports page in admin.
	 */
	public static function output() {
		include_once( 'views/html-admin-page-sports.php' );
	}

	/**
	 * Default view, and perfom tasks on submit
	 */
	public static function get_list() {
		include_once( 'views/html-admin-page-sports-list.php' );
	}

	/**
	 * Edit a single sport, and perfom tasks on submit
	 */
	public static function edit() {
		// Save settings if data has been posted
		if ( ! empty( $_POST ) ) {
				
			self::save();
		}

		include_once( 'views/html-admin-page-sports-edit.php' );
	}

	/**
	 * Delete sport
	 */
	public static function delete() {
		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'schoolathletics-delete-sport' ) ) {
			\SchoolAthletics\Admin\Notice::error(__( 'Failed to delete. Please refresh the page and retry.', 'school-athletics' ));
		}

		if(!empty($_GET['action']) && $_GET['action'] == 'delete'){
			self::delete_all_data($_GET['sport']);
			\SchoolAthletics\Admin\Notice::success('Sport has been deleted.');
		}
		self::get_list();
	}

	/**
	 * Save the settings.
	 */
	public static function save() {

		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'schoolathletics-edit-sport' ) ) {
			\SchoolAthletics\Admin\Notice::error(__( 'Failed to save. Please refresh the page and retry.', 'school-athletics' ));
		}

		if(isset($_GET['sport'])){
			$sport = $_GET['sport'];
		}else{
			$sport = wp_insert_term( $_REQUEST['name'], 'sa_sport');
			$sport = $sport['term_id'];
		}
		self::build($sport);

		if(!empty($_REQUEST['sa_sport_options'])){
			update_term_meta($sport, 'sa_sport_options', $_REQUEST['sa_sport_options']);
		}

		$_GET['sport'] = $sport;

		\SchoolAthletics\Admin\Notice::success('Sport has been saved');
	}


	/**
	 * Get sport options and parse default values.
	 * @param mixed $sport
	 * @return array
	 */
	public static function options($sport){

		if(!empty($sport)){
			$options = get_term_meta( $sport, 'sa_sport_options', true);
			$defaults = array(
					'page_id'                 => 0,
					'roster'                  => 0,
					'roster_id'               => 0,
					'roster_has_number'       => 0, 
					'roster_has_position'     => 0,
					'roster_has_height'       => 0,
					'roster_has_weight'       => 0, 
					'roster_has_year'         => 0,
					'schedule'                => 0,
					'schedule_id'             => 0,
					'schedule_has_opponents'  => 0, 
					'schedule_has_game_types' => 0,
					'schedule_keep_record'    => 0,
					'staff'                   => 0,
					'staff_id'                => 0,
				);
			$options = wp_parse_args( $options,$defaults );
			return $options;
		}else{
			return false;
		}

	}

	/**
	 * Force delete all pages in sport of sa_page post type.
	 * @param mixed $sport
	 * @return string
	 */
	public static function delete_all_data($sport){
		
		$pages = get_posts(array(
			  'post_type' => 'sa_page',
			  'numberposts' => -1,
			  'tax_query' => array(
			    array(
			      'taxonomy' => 'sa_sport',
			      'field' => 'id',
			      'terms' => $sport,
			    )
			  ),
			));

		foreach ($pages as $page) {
			if(is_object($page)){
				wp_delete_post( $page->ID, true); 
			}
		}
			
		wp_delete_term( $sport, 'sa_sport' );

	}

	/**
	 * Force delete all pages in sport of sa_page post type.
	 * @param mixed $sport
	 * @return string
	 */
	public static function delete_all($sport){
		
		$pages = get_posts(array(
			  'post_type' => 'sa_page',
			  'numberposts' => -1,
			  'tax_query' => array(
			    array(
			      'taxonomy' => 'sa_sport',
			      'field' => 'id',
			      'terms' => $sport,
			    )
			  ),
			));

		foreach ($pages as $page) {
			if(is_object($page)){
				wp_delete_post( $page->ID, true); 
			}
		}

		$options = self::options($sport);
		$options['page_id'] = null;
		$options['roster_id'] = null;
		$options['schedule_id'] = null;
		$options['staff_id'] = null;
			
		update_term_meta($sport, 'sa_sport_options', $options);

	}



	/**
	 * Build sport pages.
	 * @param mixed $sport
	 * @return string
	 */
	public static function build($sport){

		$sport = \SchoolAthletics\Sport::get_sport($sport);
		$options = \SchoolAthletics\Sport::get_sport_options($sport);
		$home_id = \SchoolAthletics\Sport::get_sport_page_id($sport,'home');
		$message = '<ul>';
		if(isset($home_id) && $home = get_post($home_id)){
			//Update Page
			self::add_sport_page($sport,'home',$home_id);
			$message .= '<li>Rebuilt '.$sport->name .' page</li>';
		}else{
			//Create Page
			self::add_sport_page($sport,'home');
			$message .= '<li>Created '.$sport->name .' page</li>';
		}

		if($options['roster']){
			$roster_id = \SchoolAthletics\Sport::get_sport_page_id($sport,'roster');
			if(isset($roster_id) && $roster = get_post($roster_id)){
				//Update Page
				self::add_sport_page($sport,'roster', $roster_id);
				$message .= '<li>Rebuilt '.$sport->name .' roster</li>';
			}else{
				//Create Page
				self::add_sport_page($sport,'roster');
				$message .= '<li>Created '.$sport->name .' roster</li>';
			}
		}

		if($options['schedule']){
			$schedule_id = \SchoolAthletics\Sport::get_sport_page_id($sport,'schedule');
			if(isset($schedule_id) && $schedule = get_post($schedule_id)){
				//Update Page
				self::add_sport_page($sport,'schedule', $schedule_id);
				$message .= '<li>Rebuilt '.$sport->name .' schedule</li>';
			}else{
				//Create Page
				self::add_sport_page($sport,'schedule');
				$message .= '<li>Created '.$sport->name .' schedule</li>';
			}
		}

		if($options['staff']){
			$staff_id = \SchoolAthletics\Sport::get_sport_page_id($sport,'staff');
			if(isset($staff_id) && $staff = get_post($staff_id)){
				//Update Page
				self::add_sport_page($sport,'staff', $staff_id);
				$message .= '<li>Rebuilt '.$sport->name .' staff</li>';
			}else{
				//Create Page
				self::add_sport_page($sport,'staff');
				$message .= '<li>Created '.$sport->name .' staff</li>';
			}
		}
		
		$message .= '</ul>';
		return $message;

	}

	/**
	 * Create sport page.
	 * @param mixed $sport
	 * @param mixed $page
	 * @param int $id
	 * @return string
	 */
	public static function add_sport_page($sport,$page,$id = null){
		if(!is_object($sport)){
			$sport = get_term($sport);
		}
		$home = \SchoolAthletics\Sport::get_sport_page_id($sport,'home');
		if(!isset($home)){
			$page = 'home';
		}

		if($page == 'home'){
			$post_name = $sport->slug;
			$post_title = $sport->name;
		}else{
			$post_name = $page;
			$post_title = ucwords($page);
		}

		$args = array();
		if($id){
			$args['ID'] = $id;
		}else{
			$args['post_name'] =  $post_name;
			$args['post_status'] =  'publish';
		}
		$args['post_content'] = '[schoolathletics page="'.$page.'"]';
		$args['post_title'] =  $post_title;
		$args['post_type'] =  'sa_page';
		if($page != 'home'){
			$args['post_parent'] = $home;
		}
		$args['tax_input'] =  array(
				'sa_sport' => $sport->name,
			);
		$args['meta_input'] = array(
				'schoolathletics_page_state' => 'Generated by School Athletics',
			);
		if($id){
			wp_update_post($args);
			$page_id = $id;
		}else{
			$page_id = wp_insert_post($args);
		}
		update_term_meta($sport->term_id, 'sa_sport_'.$page.'_id', $page_id);
		return $page_id;
	}


	/**
	 * Get the current roster with edit link.
	 */
	public static function get_current_roster($sport){
		$pages = get_posts(array(
		  'post_type' => 'sa_roster',
		  'numberposts' => -1,
		  'tax_query' => array(
		    array(
		      'taxonomy' => 'sa_sport',
		      'field' => 'id',
		      'terms' => $sport->term_id, // Where term_id of Term 1 is "1".
		    )
		  ),
		  'orderby' => 'taxonomy_sa_season',
		  'order'   => 'ASC',
		));
		$content = '';
		foreach ($pages as $page) {
			if(is_object($page)){
				if(is_object_in_term( $page->ID, 'sa_season')){
					$season = get_the_terms($page,'sa_season');
					$content .= '<a href="?page=roster&sport='.$sport->term_id.'&season='.$season[0]->term_id.'&roster_id='.$page->ID.'">'.$season[0]->name.'</a><br />';
				}
			}
		}
		$content = (!empty($content)) ? $content : '';
		return $content;
	}

	/**
	 * Get the current schedule with edit link.
	 */
	public static function get_current_schedule($sport){
		$pages = get_posts(array(
		  'post_type' => 'sa_schedule',
		  'numberposts' => -1,
		  'tax_query' => array(
		    array(
		      'taxonomy' => 'sa_sport',
		      'field' => 'id',
		      'terms' => $sport->term_id, // Where term_id of Term 1 is "1".
		    )
		  ),
		  'orderby' => 'taxonomy_sa_season',
		  'order'   => 'ASC',
		));
		$content = '';
		foreach ($pages as $page) {
			if(is_object($page)){
				if(is_object_in_term( $page->ID, 'sa_season')){
					$season = get_the_terms($page,'sa_season');
					$content .= '<a href="?page=schedule&sport='.$sport->term_id.'&season='.$season[0]->term_id.'&schedule_id='.$page->ID.'">'.$season[0]->name.'</a><br />';
				}
			}
		}
		$content = (!empty($content)) ? $content : '';
		return $content;
	}

	/**
	 * Get the sports staff with edit link. Returns all staff members of sport.
	 */
	public static function get_current_staff($sport){
		$pages = get_posts(array(
		  'post_type' => 'sa_staff',
		  'numberposts' => -1,
		  'tax_query' => array(
		    array(
		      'taxonomy' => 'sa_sport',
		      'field' => 'id',
		      'terms' => $sport->term_id,
		    ),
		  )
		));
		$content = null;
		foreach ($pages as $page) {
			if(is_object($page)){
				$content .= '<a href="?page=staff&action=edit&staff='.$page->ID.'">'.$page->post_title.' <span class="dashicons dashicons-no"></span></a><br />';
			}
		}
		return $content;
	}
}