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
	}

	/**
	 * Register admin menus.
	 */
	public function register_menus(){
		add_menu_page( 'School Athletics', 'School Athletics', 'manage_options', 'school-athletics', array( $this, 'school_athletics_page' ), plugins_url( 'school-athletics/assets/images/icon.png' ), 30 );
		if(\SchoolAthletics\Admin\Admin::advanced_mode()){
			add_submenu_page( 'school-athletics', 'Pages', 'Pages', 'manage_options', 'edit.php?post_type=sa_page');
		}
		add_submenu_page( 'school-athletics', 'Your School', 'Your School', 'manage_options', 'sa-your-school', array( $this, 'your_school_page' ));
		add_submenu_page( 'school-athletics', 'Organizations', 'Organizations', 'manage_options', 'sa-organizations', array( $this, 'organizations_page' ));
		add_submenu_page( 'school-athletics', 'Settings', 'Settings', 'manage_options', 'sa-settings', array( $this, 'settings_page' ));
		if(\SchoolAthletics\Admin\Admin::advanced_mode()){
			add_submenu_page( 'school-athletics', 'Tools', 'Tools', 'manage_options', 'sa-tools', array( $this, 'tools_page' ));
		}

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
		add_menu_page( 'Debug', 'Debug', 'manage_options', 'sa_debug', array( $this, 'settings_page' ), 'dashicons-admin-generic', 35 );
		add_submenu_page( 'sa_debug', 'Pages', 'Pages', 'manage_options', 'edit.php?post_type=sa_page');
		add_submenu_page( 'sa_debug', 'Sport Terms', 'Sport Terms', 'manage_options', 'edit-tags.php?taxonomy=sa_sport');
		add_submenu_page( 'sa_debug', 'Season Terms', 'Season Terms', 'manage_options', 'edit-tags.php?taxonomy=sa_season');
		add_submenu_page( 'sa_debug', 'Rosters', 'Rosters', 'manage_options', 'edit.php?post_type=sa_roster');
		add_submenu_page( 'sa_debug', 'Roster Members', 'Roster Members', 'manage_options', 'edit.php?post_type=sa_roster_member');
		add_submenu_page( 'sa_debug', 'Staff', 'Staff', 'manage_options', 'edit.php?post_type=sa_staff');
		add_submenu_page( 'sa_debug', 'Athlete Terms', 'Person Terms', 'manage_options', 'edit-tags.php?taxonomy=sa_person');
		add_submenu_page( 'sa_debug', 'Athlete Status', 'Athlete Status', 'manage_options', 'edit-tags.php?taxonomy=sa_athlete_status');
		add_submenu_page( 'sa_debug', 'Schedules', 'Schedules', 'manage_options', 'edit.php?post_type=sa_schedule');
		add_submenu_page( 'sa_debug', 'Events', 'Events', 'manage_options', 'edit.php?post_type=sa_event');
		add_submenu_page( 'sa_debug', 'Event Types', 'Event Types', 'manage_options', 'edit-tags.php?taxonomy=sa_event_type');
		add_submenu_page( 'sa_debug', 'Game Types', 'Game Types', 'manage_options', 'edit-tags.php?taxonomy=sa_game_type');
		add_submenu_page( 'sa_debug', 'Outcomes', 'Outcomes', 'manage_options', 'edit-tags.php?taxonomy=sa_outcome');
		add_submenu_page( 'sa_debug', 'Locations', 'Locations', 'manage_options', 'edit-tags.php?taxonomy=sa_location');
		add_submenu_page( 'sa_debug', 'Organizations', 'Organizations', 'manage_options', 'edit-tags.php?taxonomy=sa_organization');
	}

	/**
	 * Create the School Athletics page
	 */
	public function school_athletics_page() {
		return new \SchoolAthletics\Admin\Pages\SchoolAthletics();
	}

	/**
	 * Create the your school page
	 */
	public function your_school_page() {
		return new \SchoolAthletics\Admin\Pages\YourSchool();
	}

	/**
	 * Create the opponents page
	 */
	public function organizations_page() {
		return new \SchoolAthletics\Admin\Pages\Organizations();
	}

	/**
	 * Create the settings page
	 */
	public function settings_page() {
		return new \SchoolAthletics\Admin\Pages\Settings();
	}

	/**
	 * Create the Tools page
	 */
	public function tools_page() {
		return new \SchoolAthletics\Admin\Pages\Tools();
	}

	/**
	 * Create the sports page
	 */
	public function sports_page() {
		return new \SchoolAthletics\Admin\Pages\Sports();
	}

	/**
	 * Create the roster page
	 */
	public function roster_page() {
		return new \SchoolAthletics\Admin\Pages\Roster();
	}

	/**
	 * Create the staff page
	 */
	public function staff_page() {
		return new \SchoolAthletics\Admin\Pages\Staff();
	}

	/**
	 * Create the schedule page
	 */
	public function schedule_page() {
		return new \SchoolAthletics\Admin\Pages\Schedule();
	}

}