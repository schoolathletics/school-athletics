<?php 
/**
 * Setup admin menus.
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
 * Menus class.
 */
class Menus {

	/**
	 * Hook in methods.
	 */
	public function __construct(){
		add_action( 'admin_menu', array( $this, 'register_menus' ), 5 );
		if(\SchoolAthletics\Debug::status()){
			add_action( 'admin_menu', array( $this, 'register_debug_menus' ), 5 );
		}
		$this->includes();
	}

	/**
	 * Include any classes we need within admin.
	 */
	public function includes(){
		//include_once( 'class-page.php');
		//include_once( 'class-sports.php');
		//include_once( 'class-roster.php');
		//include_once( 'class-schedule.php');
		//include_once( 'class-staff.php');
		//include_once( 'class-settings.php');
	}

	/**
	 * Register admin menus.
	 */
	public function register_menus(){
		add_menu_page( 'School Athletics', 'School Athletics', 'manage_options', 'school-athletics', '', plugins_url( 'school-athletics/assets/images/icon.png' ), 30 );
		add_submenu_page( 'school-athletics', 'Settings', 'Settings', 'manage_options', 'sports_settings', array( $this, 'settings_page' ));
		add_menu_page( 'Sports', 'Sports', 'manage_options', 'sports', array( $this, 'sports_page' ), plugins_url( 'school-athletics/assets/images/icon.png' ), 30 );
		add_submenu_page( 'sports', 'Rosters', 'Rosters', 'manage_options', 'roster', array( $this, 'roster_page' ));
		add_submenu_page( 'sports', 'Schedules', 'Schedules', 'manage_options', 'schedule', array( $this, 'schedule_page' ));
		register_setting( 'schoolathletics_settings_fields', 'schoolathletics_settings_options' );
		add_submenu_page( 'sports', 'Staff', 'Staff', 'manage_options', 'staff', array( $this, 'staff_page' ));
	}

	/**
	 * Register debug menus.
	 */
	public function register_debug_menus(){
		add_menu_page( 'Debug', 'Debug', 'manage_options', 'sa_debug', '', 'dashicons-admin-generic', 35 );
		add_submenu_page( 'sa_debug', 'Pages', 'Pages', 'manage_options', 'edit.php?post_type=sa_page');
		add_submenu_page( 'sa_debug', 'Sport Terms', 'Sport Terms', 'manage_options', 'edit-tags.php?taxonomy=sa_sport');
		add_submenu_page( 'sa_debug', 'Season Terms', 'Season Terms', 'manage_options', 'edit-tags.php?taxonomy=sa_season');
		add_submenu_page( 'sa_debug', 'Rosters', 'Rosters', 'manage_options', 'edit.php?post_type=sa_roster');
		add_submenu_page( 'sa_debug', 'Persons', 'Persons', 'manage_options', 'edit.php?post_type=sa_person');
		add_submenu_page( 'sa_debug', 'Person Types', 'Person Types', 'manage_options', 'edit-tags.php?taxonomy=sa_person_type');
		add_submenu_page( 'sa_debug', 'Athlete Terms', 'Person Terms', 'manage_options', 'edit-tags.php?taxonomy=sa_person');
		add_submenu_page( 'sa_debug', 'Athlete Status', 'Athlete Status', 'manage_options', 'edit-tags.php?taxonomy=sa_athlete_status');
		add_submenu_page( 'sa_debug', 'Schedules', 'Schedules', 'manage_options', 'edit.php?post_type=sa_schedule');
		add_submenu_page( 'sa_debug', 'Events', 'Events', 'manage_options', 'edit.php?post_type=sa_event');
		add_submenu_page( 'sa_debug', 'Event Types', 'Event Types', 'manage_options', 'edit-tags.php?taxonomy=sa_event_type');
		add_submenu_page( 'sa_debug', 'Game Types', 'Game Types', 'manage_options', 'edit-tags.php?taxonomy=sa_game_type');
		add_submenu_page( 'sa_debug', 'Outcomes', 'Outcomes', 'manage_options', 'edit-tags.php?taxonomy=sa_outcome');
		add_submenu_page( 'sa_debug', 'Locations', 'Locations', 'manage_options', 'edit-tags.php?taxonomy=sa_location');
	}

	/**
	 * Create the sports page
	 */
	public function sports_page() {
		return new \SchoolAthletics\Admin\Sports();
	}

	/**
	 * Create the roster page
	 */
	public function roster_page() {
		return new \SchoolAthletics\Admin\Roster();
	}

	/**
	 * Create the staff page
	 */
	public function staff_page() {
		return new \SchoolAthletics\Admin\Staff();
	}

	/**
	 * Create the schedule page
	 */
	public function schedule_page() {
		return new \SchoolAthletics\Admin\Schedule();
	}

	/**
	 * Create the settings page
	 */
	public function settings_page() {
		return new \SchoolAthletics\Admin\Settings();
	}

}