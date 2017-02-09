<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SA_Admin_Menus' ) ) :

class SA_Admin_Menus {

	/**
	 * Hook in methods.
	 */
	public function __construct(){
		add_action( 'admin_menu', array( $this, 'register_menus' ), 5 );
		if(SchoolAthletics::debug()){
			add_action( 'admin_menu', array( $this, 'register_debug_menus' ), 5 );
		}
		$this->includes();
	}

	/**
	 * Include any classes we need within admin.
	 */
	public function includes(){
		include_once( 'class-sa-admin-sports.php');
		include_once( 'class-sa-admin-roster.php');
		include_once( 'class-sa-admin-schedule.php');
		include_once( 'class-sa-admin-coach.php');
		include_once( 'class-sa-admin-settings.php');
	}

	public function register_menus(){
		add_menu_page( 'School Athletics', 'School Athletics', 'manage_options', 'school-athletics', '', plugins_url( 'school-athletics/assets/images/icon.png' ), 30 );
		add_submenu_page( 'school-athletics', 'Settings', 'Settings', 'manage_options', 'sports_settings', array( $this, 'settings_page' ));
		add_menu_page( 'Sports', 'Sports', 'manage_options', 'sports', array( $this, 'sports_page' ), plugins_url( 'school-athletics/assets/images/icon.png' ), 30 );
		add_submenu_page( 'sports', 'Roster', 'Roster', 'manage_options', 'roster', array( $this, 'roster_page' ));
		add_submenu_page( 'sports', 'Schedule', 'Schedule', 'manage_options', 'schedule', array( $this, 'schedule_page' ));
		register_setting( 'schoolathletics_settings_fields', 'schoolathletics_settings_options' );
		add_submenu_page( 'sports', 'Coach', 'Coach', 'manage_options', 'coach', array( $this, 'coach_page' ));
		add_menu_page( 'Directory', 'Directory', 'manage_options', 'sa_person', '', 'dashicons-groups', 40 );
		add_menu_page( 'Events', 'Events', 'manage_options', 'sa_event', '', 'dashicons-calendar-alt', 40 );
	}

	public function register_debug_menus(){
		add_menu_page( 'Debug', 'Debug', 'manage_options', 'sa_debug', '', 'dashicons-admin-generic', 35 );
		add_submenu_page( 'sa_debug', 'Sport Terms', 'Sport Terms', 'manage_options', 'edit-tags.php?taxonomy=sa_sport');
		add_submenu_page( 'sa_debug', 'Athlete Terms', 'Person Terms', 'manage_options', 'edit-tags.php?taxonomy=sa_person');
		add_submenu_page( 'sa_debug', 'Season Terms', 'Season Terms', 'manage_options', 'edit-tags.php?taxonomy=sa_season');
		add_submenu_page( 'sa_debug', 'Person Types', 'Person Types', 'manage_options', 'edit-tags.php?taxonomy=sa_person_type');
		add_submenu_page( 'sa_debug', 'Event Types', 'Event Types', 'manage_options', 'edit-tags.php?taxonomy=sa_event_type');
	}

	/**
	 * Init the status page.
	 */
	public function sports_page() {
		SA_Admin_Sports::output();
	}

	/**
	 * Init the status page.
	 */
	public function roster_page() {
		SA_Admin_Roster::output();
	}

	/**
	 * Init the status page.
	 */
	public function coach_page() {
		SA_Admin_Coach::output();
	}

		/**
	 * Init the status page.
	 */
	public function schedule_page() {
		SA_Admin_Schedule::output();
	}

	/**
	 * Init the status page.
	 */
	public function settings_page() {
		SA_Admin_Settings::output();
	}

}

endif;

return new SA_Admin_Menus();