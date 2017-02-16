<?php 
/**
 * Sports Page.
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
 * SportsAdmin page class.
 */
class SportsAdmin extends Page{

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
		if ( ! empty( $_GET['task'] ) ) {

			if($_GET['task'] == 'publish'){
				
				self::publish($_REQUEST['sport']);

			}

			if($_GET['task'] == 'unpublish'){
				
				self::unpublish($_REQUEST['sport']);

			}

		}
		include_once( 'views/html-admin-page-sports-list.php' );
	}

	/**
	 * Edit a single sport, and perfom tasks on submit
	 */
	public static function edit() {
		// Save settings if data has been posted
		if ( ! empty( $_POST ) ) {

			if(empty($_POST['task'])){
				
				self::save();

			}elseif($_POST['task'] == 'rebuild'){
				
				self::rebuild();

			}

		}

		include_once( 'views/html-admin-page-sports-edit.php' );
	}

	/**
	 * Save the settings.
	 */
	public static function save() {

		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'schoolathletics-edit-sport' ) ) {
			\SchoolAthletics\Admin\Notice::error(__( 'Failed to save. Please refresh the page and retry.', 'school-athletics' ));
		}

		if(!empty($_REQUEST['sa_sport_options'])){
			$sport = $_REQUEST['sport'];
			update_term_meta($sport, 'sa_sport_options', $_REQUEST['sa_sport_options']);
			self::build($sport);
		}

		\SchoolAthletics\Admin\Notice::success('Sport has been saved');

	}

	/**
	 * Publish the sport setting term status to 1.
	 */
	public static function publish($sport) {

		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'schoolathletics-publish-sport' ) ) {
			\SchoolAthletics\Admin\Notice::error(__( 'Failed to save. Please refresh the page and retry.', 'school-athletics' ));
		}

		if(!empty($sport)){
			
			self::build($sport,1);
		}

		\SchoolAthletics\Admin\Notice::success('Sport has been published');

	}

	/**
	 * Unpublish the sport setting term status to 0.
	 */
	public static function unpublish($sport) {

		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'schoolathletics-unpublish-sport' ) ) {
			\SchoolAthletics\Admin\Notice::error(__( 'Failed to unpublish. Please refresh the page and retry.', 'school-athletics' ));
		}

		if(!empty($sport)){
			
			self::delete_all($sport,0);
			
		}

		\SchoolAthletics\Admin\Notice::success('Sport has been unpublished');

	}

	/**
	 * Get sport options and parse default values.
	 */
	public static function options($sport){

		if(!empty($sport)){
			$options = get_term_meta( $sport, 'sa_sport_options', true);
			$defaults = array(
					'status'                  => 0,
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
	 */
	public static function delete_all($sport, $status = null){
		
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
		if(is_int($status)){
			$options['status'] = $status;
		}
			
		update_term_meta($sport, 'sa_sport_options', $options);

	}

	/**
	 * Rebuild pages. This deletes all the pages and then readds them.
	 */
	public static function rebuild(){
		
		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'schoolathletics-rebuild-sport-pages' ) ) {
			\SchoolAthletics\Admin\Notice::error(__( 'Failed to save. Please refresh the page and retry.', 'school-athletics' ));
		}

		if(!empty($_REQUEST['task']) && !empty($_REQUEST['sport'])){
			
			self::delete_all($_REQUEST['sport']);
			self::build($_REQUEST['sport']);

			\SchoolAthletics\Admin\Notice::success('Sport has successfully been rebuilt.');
		}

	}

	/**
	 * Build sport pages.
	 */
	public static function build($sport, $status = null){

		$options = self::options($sport);
		if($options && !empty($sport)){
			
			if(is_int($status)){
				$options['status'] = $status;
			}

			if(!$options['page_id']){
				$options['page_id'] = self::build_page($sport);
			}

			if($options['roster']){
				if(!$options['roster_id']){
					$options['roster_id'] = self::build_roster_page($sport,$options['page_id']);
				}
			}else{
				if($options['roster_id']){
					wp_delete_post( $options['roster_id'], true); 
					$options['roster_id'] = 0;
				}
			}

			if($options['schedule']){
				if(!$options['schedule_id']){
					$options['schedule_id'] = self::build_schedule_page($sport,$options['page_id']);
				}
			}else{
				if($options['schedule_id']){
					wp_delete_post( $options['schedule_id'], true); 
					$options['schedule_id'] = 0;
				}
			}

			if($options['staff']){
				if(!$options['staff_id']){
					$options['staff_id'] = self::build_staff_page($sport,$options['page_id']);
				}
			}else{
				if($options['staff_id']){
					wp_delete_post( $options['staff_id'], true); 
					$options['staff_id'] = 0;
				}
			}
			
			update_term_meta($sport, 'sa_sport_options', $options);
		}

	}

	/**
	 * Create sport home page.
	 */
	public static function build_page($term){
		if(!is_object($term)){
			$term = get_term($term);
		}
		$home = array(
			'post_content' => '$sa_page_content',
			'post_title' => $term->name,
			'post_name' => $term->slug,
			'post_type' => 'sa_page',
			'post_status' => 'publish',//publish
			'tax_input' => array(
				'sa_sport' => $term->name,
			),	
		);
		return wp_insert_post($home);
	}

	/**
	 * Create sport roster page.
	 */
	public static function build_roster_page($sport, $home_id){
		$title = 'Roster';
		$content = '[roster]';
		return self::insert_sport_subpage($sport, $home_id, $title, $content);
	}

	/**
	 * Create sport schedule page.
	 */
	public static function build_schedule_page($sport, $home_id){
		$title = 'Schedule';
		$content = '[schedule]';
		return self::insert_sport_subpage($sport, $home_id, $title, $content);
	}

	/**
	 * Create sport staff page.
	 */
	public static function build_staff_page($sport, $home_id){
		$title = 'Staff';
		$content = '[staff]';
		return self::insert_sport_subpage($sport, $home_id, $title, $content);
	}

	/**
	 * Create a subpage under the sport home page
	 */
	public static function insert_sport_subpage($sport, $parentID, $title, $content){
		if(!is_object($sport)){
			$sport = get_term($sport);
		}
		$subpage = array(
						'post_content' => $content,
						'post_title' => $title,
						'post_name' => '',
						'post_parent' => $parentID,
						'post_type' => 'sa_page',
						'post_status' => 'publish',//publish
						'tax_input' => array(
							'sa_sport' => $sport->name,
						),	
					);
		$id = wp_insert_post($subpage);
		return $id;
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
				$content .= '<a href="#&roster_id='.$page->ID.'">'.$page->post_title.' <span class="dashicons dashicons-no"></span></a><br />';
			}
		}
		return $content;
	}
}