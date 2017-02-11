<?php 
/**
 * Sports Page.
 *
 * @author   Dwayne Parton
 * @category Admin
 * @package  School Athletics/Admin
 * @version  0.0.1
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * SA_Admin_Sports Class.
 */
class SA_Admin_Sports {
	/**
	 * Handles output of the settings page in admin.
	 */
	public static function output() {
		include_once( 'views/html-admin-page-sports.php' );
	}

	/**
	 * Handles output of the settings page in admin.
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
	 * Handles output of the settings page in admin.
	 */
	public static function wizard() {
		include_once( 'views/html-admin-page-sports-wizard.php' );
	}

	/**
	 * Handles output of the settings page in admin.
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
			SA_Admin_Notice::error(__( 'Failed to save. Please refresh the page and retry.', 'school-athletics' ));
		}

		if(!empty($_REQUEST['sa_sport_options'])){
			$sport = $_REQUEST['sport'];
			update_term_meta($sport, 'sa_sport_options', $_REQUEST['sa_sport_options']);
			self::build($sport);
		}

		SA_Admin_Notice::success('Sport has been saved');

	}

	/**
	 * Save the settings.
	 */
	public static function publish($sport) {

		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'schoolathletics-publish-sport' ) ) {
			SA_Admin_Notice::error(__( 'Failed to save. Please refresh the page and retry.', 'school-athletics' ));
		}

		if(!empty($sport)){
			
			self::build($sport,1);
		}

		SA_Admin_Notice::success('Sport has been published');

	}

	/**
	 * Save the settings.
	 */
	public static function unpublish($sport) {

		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'schoolathletics-unpublish-sport' ) ) {
			SA_Admin_Notice::error(__( 'Failed to unpublish. Please refresh the page and retry.', 'school-athletics' ));
		}

		if(!empty($sport)){
			
			self::delete_all($sport,0);
			
		}

		SA_Admin_Notice::success('Sport has been unpublished');

	}

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
	 * Rebuild Pages.
	 */
	public static function rebuild(){
		
		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'schoolathletics-rebuild-sport-pages' ) ) {
			SA_Admin_Notice::error(__( 'Failed to save. Please refresh the page and retry.', 'school-athletics' ));
		}

		if(!empty($_REQUEST['task']) && !empty($_REQUEST['sport'])){
			
			self::delete_all($_REQUEST['sport']);
			self::build($_REQUEST['sport']);

			SA_Admin_Notice::success('Sport has successfully been rebuilt.');
		}

		//SA_Admin_Notice::success('Sport has successfully been rebuilt.');
	}

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

	public static function build_roster_page($sport, $home_id){
		$title = 'Roster';
		$content = '[roster]';
		return self::insert_sport_subpage($sport, $home_id, $title, $content);
	}

	public static function build_schedule_page($sport, $home_id){
		$title = 'Schedule';
		$content = '[schedule]';
		return self::insert_sport_subpage($sport, $home_id, $title, $content);
	}

	public static function build_staff_page($sport, $home_id){
		$title = 'Staff';
		$content = '[staff]';
		return self::insert_sport_subpage($sport, $home_id, $title, $content);
	}

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

}

return new SA_Admin_Sports();