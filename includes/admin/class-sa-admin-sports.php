<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
	public static function list() {
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
			self::save();
		}
		include_once( 'views/html-admin-page-sports-edit.php' );
	}

	/**
	 * Save the settings.
	 */
	public static function save() {

		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'schoolathletics-edit-sport' ) ) {
			die( __( 'Failed to save. Please refresh the page and retry.', 'school-athletics' ) );
		}

		SA_Admin_Notice::success('Sport has been saved');

	}

	/**
	 * Publishes the sport and creats sub pages
	 */
	public function publish_sport($term){
		if(!is_object($term)){
			$term = get_term($term);
		}

		$sport_home_id = get_term_meta( $term->term_id, 'sa_sport_home_id', true );
		$sport_roster_id = get_term_meta( $term->term_id, 'sa_sport_roster_id', true );
		$sport_schedule_id = get_term_meta( $term->term_id, 'sa_sport_schedule_id', true );
		$user_id = get_current_user_id();

		
		$home = array(
			'ID' => $sport_home_id,
			'post_author' => $user_id,
			'post_content' => '$sa_page_content',
			'post_title' => $term->name,
			'post_name' => $term->slug,
			'post_type' => 'sa_page',
			'post_status' => 'publish',//publish
			//'tax_input' => array('sa_sport' => $_POST['_sa_sport']),	
		);
		$sport_home_id = wp_insert_post($home);
		//Set Post Meta = id
		//if($sport_home_id == ''){
			update_term_meta($term->term_id, 'sa_sport_home_id', $sport_home_id);
		//}

		//if($has_roster){
			//* Add Roster
			$title = 'Roster';
			$content = 'Roster Archive';
			$roster_id = insert_sport_subpage($sport_roster_id, $sport_home_id, $title, $content);
			update_term_meta($term->term_id, 'sa_sport_roster_id', $roster_id);
			//*
		//}

		//if($has_schedule){
			//* Add Schedule
			$title = 'Schedule';
			$content = 'Schedule Archive';
			$schedule_id = insert_sport_subpage($sport_schedule_id, $sport_home_id, $title, $content);
			update_term_meta($term->term_id, 'sa_sport_schedule_id', $schedule_id);
			//*
		//}

	}

}
