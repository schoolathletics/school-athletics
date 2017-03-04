<?php 
/**
 * Installs the foundation terms, post types, and post status
 *
 * @author   Dwayne Parton
 * @category Class
 * @package  SchoolAthletics
 * @version  0.0.1
 */

namespace SchoolAthletics;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Install WordPress objects class.
 */
class InstallWpObjects {

	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_taxonomies' ), 5 );
		add_action( 'init', array( __CLASS__, 'register_post_types' ), 5 );
		add_action( 'init', array( __CLASS__, 'register_post_status' ), 9 );
	
		add_filter( 'post_type_link', array( __CLASS__, 'post_type_link' ), 10, 2);
		add_action('init', array( __CLASS__, 'rewrite_tags'), 10, 0);
		add_filter('term_link', array( __CLASS__, 'term_link_filter'), 10, 3);
	}


	/**
	 * Registers core taxonomies: Sport, Person, Season;
	 */
	public static function register_taxonomies() {
		if ( taxonomy_exists( 'sa_sport' ) ) {
			return;
		}

		// Register Sport Taxonomy
		register_taxonomy( 'sa_sport',
			array(
				'post',
				'sa_page',
				'sa_staff',
				'sa_event',
				//'sa_roster',
				'sa_roster_member',
				//'sa_schedule'
			),
			array(
			'hierarchical'          => false,
			'labels'                => array(
						'name'              => __( 'Sports', 'school-athletics' ),
						'singular_name'     => __( 'Sport', 'school-athletics' ),
						'menu_name'         => __( 'Sports', 'school-athletics' ),
						'search_items'      => __( 'Search Sports', 'school-athletics' ),
						'all_items'         => __( 'All Sports', 'school-athletics' ),
						'edit_item'         => __( 'Edit Sport', 'school-athletics' ),
						'update_item'       => __( 'Update Sports', 'school-athletics' ),
						'add_new_item'      => __( 'Add New Sport', 'school-athletics' ),
						'new_item_name'     => __( 'New Sport Name', 'school-athletics' ),
						'not_found'         => __( 'No Sport found', 'school-athletics' ),
			),
			'show_ui'               => true,
			'show_in_menu'          => false,
			'show_in_nav_menus'     => false,
			'show_admin_column'     => true,
			'show_tagcloud'         => false,
			'show_in_quick_edit'    => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => is_admin(),
			'public'                => true,
	        'rewrite'               => false,
			)
		);
		register_taxonomy_for_object_type( 'sa_sport', 'post' );
		register_taxonomy_for_object_type( 'sa_sport', 'sa_page' );
		register_taxonomy_for_object_type( 'sa_sport', 'sa_staff' );
		register_taxonomy_for_object_type( 'sa_sport', 'sa_event' );
		//register_taxonomy_for_object_type( 'sa_sport', 'sa_roster' );
		//register_taxonomy_for_object_type( 'sa_sport', 'sa_schedule' );
		register_taxonomy_for_object_type( 'sa_sport', 'sa_roster_member' );

		// Register People Taxonomy
		register_taxonomy( 'sa_page_type',
			array(
				'sa_page',
			),
			array(
			'hierarchical'          => false,
			'labels'                => array(
						'name'              => __( 'Page Types', 'school-athletics' ),
						'singular_name'     => __( 'Page Type', 'school-athletics' ),
						'menu_name'         => __( 'Page Type', 'school-athletics' ),
						'search_items'      => __( 'Search Page Types', 'school-athletics' ),
						'all_items'         => __( 'All Page Types', 'school-athletics' ),
						'edit_item'         => __( 'Edit Page Type', 'school-athletics' ),
						'update_item'       => __( 'Update Page Type', 'school-athletics' ),
						'add_new_item'      => __( 'Add New Page Type', 'school-athletics' ),
						'new_item_name'     => __( 'New Page Type Name', 'school-athletics' ),
						'not_found'         => __( 'No Page Type found', 'school-athletics' ),
			),
			'show_ui'               => \SchoolAthletics\Debug::status(),
			'show_in_menu'          => false,
			'show_in_nav_menus'     => false,
			'show_admin_column'     => true,
			'show_tagcloud'         => false,
			'show_in_quick_edit'    => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => false,
			'public'                => false,
	        'rewrite'               => false,
			)
		);
		register_taxonomy_for_object_type( 'sa_page_type', 'sa_page' );

		// Register People Taxonomy
		register_taxonomy( 'sa_athlete_status',
			array(
				'sa_roster_member',
			),
			array(
			'hierarchical'          => false,
			'labels'                => array(
						'name'              => __( 'Athlete Status', 'school-athletics' ),
						'singular_name'     => __( 'Athlete Status', 'school-athletics' ),
						'menu_name'         => __( 'Athlete Status', 'school-athletics' ),
						'search_items'      => __( 'Search Athlete Status', 'school-athletics' ),
						'all_items'         => __( 'All Athlete Status', 'school-athletics' ),
						'edit_item'         => __( 'Edit Athlete Status', 'school-athletics' ),
						'update_item'       => __( 'Update Athlete Status', 'school-athletics' ),
						'add_new_item'      => __( 'Add New Athlete Status', 'school-athletics' ),
						'new_item_name'     => __( 'New Athlete Status Name', 'school-athletics' ),
						'not_found'         => __( 'No Athlete Status found', 'school-athletics' ),
			),
			'show_ui'               => \SchoolAthletics\Debug::status(),
			'show_in_menu'          => false,
			'show_in_nav_menus'     => false,
			'show_admin_column'     => true,
			'show_tagcloud'         => false,
			'show_in_quick_edit'    => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => false,
			'public'                => false,
	        'rewrite'               => false,
			)
		);
		register_taxonomy_for_object_type( 'sa_athlete_status', 'sa_roster_member' );

		// Register People Taxonomy
		register_taxonomy( 'sa_person',
			array(
				'post',
				'sa_staff',
				//'sa_roster',
				'sa_page',
				'sa_roster_member',
			),
			array(
			'hierarchical'          => false,
			'labels'                => array(
						'name'              => __( 'Persons', 'school-athletics' ),
						'singular_name'     => __( 'Person', 'school-athletics' ),
						'menu_name'         => __( 'Persons', 'school-athletics' ),
						'search_items'      => __( 'Search Persons', 'school-athletics' ),
						'all_items'         => __( 'All Persons', 'school-athletics' ),
						'edit_item'         => __( 'Edit Person', 'school-athletics' ),
						'update_item'       => __( 'Update Person', 'school-athletics' ),
						'add_new_item'      => __( 'Add New Person', 'school-athletics' ),
						'new_item_name'     => __( 'New Person Name', 'school-athletics' ),
						'not_found'         => __( 'No Persons found', 'school-athletics' ),
			),
			'show_ui'               => true,
			'show_in_menu'          => false,
			'show_in_nav_menus'     => false,
			'show_admin_column'     => true,
			'show_tagcloud'         => false,
			'show_in_quick_edit'    => false,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'public'                => true,
	        'rewrite'               => false,
			)
		);
		register_taxonomy_for_object_type( 'sa_person', 'post' );
		register_taxonomy_for_object_type( 'sa_person', 'sa_staff' );
		register_taxonomy_for_object_type( 'sa_person', 'sa_roster_member' );
		register_taxonomy_for_object_type( 'sa_person', 'sa_page' );
		//register_taxonomy_for_object_type( 'sa_person', 'sa_roster' );

		// Register Season Taxonomy
		register_taxonomy( 'sa_season',
			array(
				//'sa_schedule',
				'sa_page',
				//'sa_roster',
				'sa_roster_member',
				'sa_event',
			),
			array(
			'hierarchical'          => false,
			'labels'                => array(
						'name'              => __( 'Seasons', 'school-athletics' ),
						'singular_name'     => __( 'Season', 'school-athletics' ),
						'menu_name'         => __( 'Seasons', 'school-athletics' ),
						'search_items'      => __( 'Search Season', 'school-athletics' ),
						'all_items'         => __( 'All Seasons', 'school-athletics' ),
						'edit_item'         => __( 'Edit Season', 'school-athletics' ),
						'update_item'       => __( 'Update Season', 'school-athletics' ),
						'add_new_item'      => __( 'Add New Season', 'school-athletics' ),
						'new_item_name'     => __( 'New Season Name', 'school-athletics' ),
						'not_found'         => __( 'No Seasons found', 'school-athletics' ),
			),
			'show_ui'               => \SchoolAthletics\Debug::status(),
			'show_in_menu'          => false,
			'show_in_nav_menus'     => false,
			'show_admin_column'     => true,
			'show_tagcloud'         => false,
			'show_in_quick_edit'    => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => false,
			'public'                => false,
	        'rewrite'               => false,
			)
		);
		register_taxonomy_for_object_type( 'sa_season', 'sa_roster_member' );
		register_taxonomy_for_object_type( 'sa_season', 'sa_event' );
		//register_taxonomy_for_object_type( 'sa_season', 'sa_roster' );
		register_taxonomy_for_object_type( 'sa_season', 'sa_page' );
		//register_taxonomy_for_object_type( 'sa_season', 'sa_schedule' );

		// Register Event Type Taxonomy
		register_taxonomy( 'sa_event_type',
			array(
				'sa_event',
			),
			array(
			'hierarchical'          => false,
			'labels'                => array(
						'name'              => __( 'Event Types', 'school-athletics' ),
						'singular_name'     => __( 'Event Type', 'school-athletics' ),
						'menu_name'         => __( 'Event Types', 'school-athletics' ),
						'search_items'      => __( 'Search Event Types', 'school-athletics' ),
						'all_items'         => __( 'All Event Types', 'school-athletics' ),
						'edit_item'         => __( 'Edit Event Types', 'school-athletics' ),
						'update_item'       => __( 'Update Event Types', 'school-athletics' ),
						'add_new_item'      => __( 'Add New Event Types', 'school-athletics' ),
						'new_item_name'     => __( 'New Event Type Name', 'school-athletics' ),
						'not_found'         => __( 'No Event Types found', 'school-athletics' ),
			),
			'show_ui'               => \SchoolAthletics\Debug::status(),
			'show_in_menu'          => 'sa_debug',
			'show_in_nav_menus'     => false,
			'show_admin_column'     => true,
			'show_tagcloud'         => false,
			'show_in_quick_edit'    => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => false,
			'public'                => false,
	        'rewrite'               => false,
			)
		);
		register_taxonomy_for_object_type( 'sa_event_type', 'sa_event' );

		// Register Event Type Taxonomy
		register_taxonomy( 'sa_game_type',
			array(
				'sa_event',
			),
			array(
			'hierarchical'          => false,
			'labels'                => array(
						'name'              => __( 'Game Types', 'school-athletics' ),
						'singular_name'     => __( 'Game Type', 'school-athletics' ),
						'menu_name'         => __( 'Game Types', 'school-athletics' ),
						'search_items'      => __( 'Search Game Types', 'school-athletics' ),
						'all_items'         => __( 'All Game Types', 'school-athletics' ),
						'edit_item'         => __( 'Edit Game Types', 'school-athletics' ),
						'update_item'       => __( 'Update Game Types', 'school-athletics' ),
						'add_new_item'      => __( 'Add New Game Types', 'school-athletics' ),
						'new_item_name'     => __( 'New Game Type Name', 'school-athletics' ),
						'not_found'         => __( 'No Game Types found', 'school-athletics' ),
			),
			'show_ui'               => \SchoolAthletics\Debug::status(),
			'show_in_menu'          => 'sa_debug',
			'show_in_nav_menus'     => false,
			'show_admin_column'     => false,
			'show_tagcloud'         => false,
			'show_in_quick_edit'    => false,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => false,
			'public'                => false,
	        'rewrite'               => false,
			)
		);
		register_taxonomy_for_object_type( 'sa_game_type', 'sa_event' );

		// Register Event Type Taxonomy
		register_taxonomy( 'sa_outcome',
			array(
				'sa_event',
			),
			array(
			'hierarchical'          => false,
			'labels'                => array(
						'name'              => __( 'Outcomes', 'school-athletics' ),
						'singular_name'     => __( 'Outcome', 'school-athletics' ),
						'menu_name'         => __( 'Outcomes', 'school-athletics' ),
						'search_items'      => __( 'Search Outcomes', 'school-athletics' ),
						'all_items'         => __( 'All Outcomes', 'school-athletics' ),
						'edit_item'         => __( 'Edit Outcomes', 'school-athletics' ),
						'update_item'       => __( 'Update Outcomes', 'school-athletics' ),
						'add_new_item'      => __( 'Add New Outcomes', 'school-athletics' ),
						'new_item_name'     => __( 'New Outcome Name', 'school-athletics' ),
						'not_found'         => __( 'No Outcomes found', 'school-athletics' ),
			),
			'show_ui'               => \SchoolAthletics\Debug::status(),
			'show_in_menu'          => 'sa_debug',
			'show_in_nav_menus'     => false,
			'show_admin_column'     => false,
			'show_tagcloud'         => false,
			'show_in_quick_edit'    => false,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => false,
			'public'                => false,
	        'rewrite'               => false,
			)
		);
		register_taxonomy_for_object_type( 'sa_outcome', 'sa_event' );

		// Register Organization Type Taxonomy
		register_taxonomy( 'sa_opponent',
			array(
				'sa_event',
			),
			array(
			'hierarchical'          => false,
			'labels'                => array(
						'name'              => __( 'Opponents', 'school-athletics' ),
						'singular_name'     => __( 'Opponent', 'school-athletics' ),
						'menu_name'         => __( 'Opponents', 'school-athletics' ),
						'search_items'      => __( 'Search Opponents', 'school-athletics' ),
						'all_items'         => __( 'All Opponents', 'school-athletics' ),
						'edit_item'         => __( 'Edit Opponents', 'school-athletics' ),
						'update_item'       => __( 'Update Opponents', 'school-athletics' ),
						'add_new_item'      => __( 'Add New Opponents', 'school-athletics' ),
						'new_item_name'     => __( 'New Opponents Name', 'school-athletics' ),
						'not_found'         => __( 'No Opponents found', 'school-athletics' ),
			),
			'show_ui'               => \SchoolAthletics\Debug::status(),
			'show_in_menu'          => 'sa_debug',
			'show_in_nav_menus'     => false,
			'show_admin_column'     => false,
			'show_tagcloud'         => false,
			'show_in_quick_edit'    => false,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => false,
			'public'                => false,
	        'rewrite'               => false,
			)
		);
		register_taxonomy_for_object_type( 'sa_opponent', 'sa_event' );

		// Register Event Type Taxonomy
		register_taxonomy( 'sa_location',
			array(
				'sa_event',
			),
			array(
			'hierarchical'          => false,
			'labels'                => array(
						'name'              => __( 'Locations', 'school-athletics' ),
						'singular_name'     => __( 'Location', 'school-athletics' ),
						'menu_name'         => __( 'Locations', 'school-athletics' ),
						'search_items'      => __( 'Search Locations', 'school-athletics' ),
						'all_items'         => __( 'All Locations', 'school-athletics' ),
						'edit_item'         => __( 'Edit Locations', 'school-athletics' ),
						'update_item'       => __( 'Update Locations', 'school-athletics' ),
						'add_new_item'      => __( 'Add New Locations', 'school-athletics' ),
						'new_item_name'     => __( 'New Location Name', 'school-athletics' ),
						'not_found'         => __( 'No Locations found', 'school-athletics' ),
			),
			'show_ui'               => \SchoolAthletics\Debug::status(),
			'show_in_menu'          => 'sa_debug',
			'show_in_nav_menus'     => false,
			'show_admin_column'     => false,
			'show_tagcloud'         => false,
			'show_in_quick_edit'    => false,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => false,
			'public'                => false,
	        'rewrite'               => false,
			)
		);
		register_taxonomy_for_object_type( 'sa_location', 'sa_event' );

	}

	/**
	 * Register core post types.
	 * Pages, Athletes, Staff, Events, Schedules, Rosters
	 */
	public static function register_post_types() {
		if ( post_type_exists('sa_page') ) {
			return;
		}

		$permalinks = get_option( 'schoolathletics_permalinks' );
		$page_permalink = empty( $permalinks['base'] ) ? _x( 'sports', 'slug', 'school-athletics' ) : $permalinks['base'];
		// Register Sport Pages
		register_post_type( 'sa_page',
			array(
				'label'               => __( 'sa_page', 'school-athletics' ),
				'description'         => __( 'Manages Sports Pro Pages.', 'school-athletics' ),
				'labels'              => array(
							'name'                => __( 'Sport Pages', 'school-athletics' ),
							'singular_name'       => __( 'Page', 'school-athletics' ),
							'menu_name'           => __( 'Sport Pages', 'school-athletics' ),
							'parent_item_colon'   => __( 'Parent Item:', 'school-athletics' ),
							'all_items'           => __( 'Sport Pages', 'school-athletics' ),
							'view_item'           => __( 'View Sports Pro', 'school-athletics' ),
							'add_new_item'        => __( 'Add New Page', 'school-athletics' ),
							'add_new'             => __( 'Add New Page', 'school-athletics' ),
							'edit_item'           => __( 'Edit Page', 'school-athletics' ),
							'update_item'         => __( 'Update Page', 'school-athletics' ),
							'search_items'        => __( 'Search Pages', 'school-athletics' ),
							'not_found'           => __( 'Not found', 'school-athletics' ),
							'not_found_in_trash'  => __( 'Not found in Trash', 'school-athletics' ),
				),
				'supports'            => array( 'title', 'thumbnail', 'editor', 'page-attributes','post-formats'),
				'hierarchical'        => true,
				'public'              => true,
				'show_ui'             => \SchoolAthletics\Admin\Admin::advanced_mode(),
				'show_in_menu'        => false,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => false,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'query_var'           => true,
				'public'              => true,
		        'rewrite'             => $page_permalink ? array( 'slug' => untrailingslashit( $page_permalink ), 'with_front' => false, 'feeds' => true ) : false,
				'capability_type'     => 'page',
			)
		);

		// Register Roster
		$roster_permalink = empty( $permalinks['roster_base'] ) ? untrailingslashit( $page_permalink ).'/%sa_sport%/'._x( 'roster', 'slug', 'school-athletics' ) : $permalinks['roster_base'];
		/*register_post_type( 'sa_roster',
			array(
				'label'               => __( 'roster', 'school-athletics' ),
				'description'         => __( 'Manage Rosters.', 'school-athletics' ),
				'labels'              => array(
							'name'                => __( 'Rosters', 'school-athletics' ),
							'singular_name'       => __( 'Roster', 'school-athletics' ),
							'menu_name'           => __( 'Rosters', 'school-athletics' ),
							'parent_item_colon'   => __( 'Parent Item:', 'school-athletics' ),
							'all_items'           => __( 'Rosters', 'school-athletics' ),
							'view_item'           => __( 'View Roster', 'school-athletics' ),
							'add_new_item'        => __( 'Add New Roster', 'school-athletics' ),
							'add_new'             => __( 'Add New Roster', 'school-athletics' ),
							'edit_item'           => __( 'Edit Roster', 'school-athletics' ),
							'update_item'         => __( 'Update Roster', 'school-athletics' ),
							'search_items'        => __( 'Search Rosters', 'school-athletics' ),
							'not_found'           => __( 'Not found', 'school-athletics' ),
							'not_found_in_trash'  => __( 'Not found in Trash', 'school-athletics' ),
				 ),
		        'supports'            => array( 'title','editor','thumbnail'),
		        'hierarchical'        => false,
		        'public'              => true,
		        'show_ui'             => \SchoolAthletics\Debug::status(),
		        'show_in_menu'        => false,
		        'show_in_nav_menus'   => false,
		        'show_in_admin_bar'   => false,
		        'can_export'          => true,
		        'has_archive'         => false,
		        'exclude_from_search' => false,
		        'publicly_queryable'  => true,
		        'rewrite'             => $roster_permalink ? array( 'slug' => untrailingslashit( $roster_permalink ), 'with_front' => false, 'feeds' => false, 'pages' => false ) : false,
		        'query_var'           => true,
		        'capability_type'     => 'post',
			)
		);*/

		// Register Roster
		$member_permalink = empty( $permalinks['roster_base'] ) ? untrailingslashit($roster_permalink).'/%sa_season%/' : $permalinks['roster_base'];
		register_post_type( 'sa_roster_member',
			array(
				'label'               => __( 'sa_roster_member', 'school-athletics' ),
				'description'         => __( 'Manage Roster Members.', 'school-athletics' ),
				'labels'              => array(
							'name'                => __( 'Roster Members', 'school-athletics' ),
							'singular_name'       => __( 'Roster Member', 'school-athletics' ),
							'menu_name'           => __( 'Roster Members', 'school-athletics' ),
							'parent_item_colon'   => __( 'Parent Item:', 'school-athletics' ),
							'all_items'           => __( 'Roster Members', 'school-athletics' ),
							'view_item'           => __( 'View Roster Member', 'school-athletics' ),
							'add_new_item'        => __( 'Add New Roster Member', 'school-athletics' ),
							'add_new'             => __( 'Add New Roster Member', 'school-athletics' ),
							'edit_item'           => __( 'Edit Roster Member', 'school-athletics' ),
							'update_item'         => __( 'Update Roster Member', 'school-athletics' ),
							'search_items'        => __( 'Search Rosters Member', 'school-athletics' ),
							'not_found'           => __( 'Not found', 'school-athletics' ),
							'not_found_in_trash'  => __( 'Not found in Trash', 'school-athletics' ),
				 ),
		        'supports'            => array( 'title','editor', 'thumbnail', 'custom-fields'),
		        'hierarchical'        => false,
		        'public'              => true,
		        'show_ui'             => \SchoolAthletics\Debug::status(),
		        'show_in_menu'        => false,
		        'show_in_nav_menus'   => false,
		        'show_in_admin_bar'   => false,
		        'can_export'          => true,
		        'has_archive'         => false,
		        'exclude_from_search' => false,
		        'publicly_queryable'  => true,
		        'rewrite'             => $member_permalink ? array( 'slug' => untrailingslashit( $member_permalink ), 'with_front' => false, 'feeds' => false, 'pages' => false ) : false,
		        'query_var'           => true,
		        'capability_type'     => 'post',
			)
		);

		// Register Event
		/*$schedule_permalink = empty( $permalinks['schedule_base'] ) ? untrailingslashit( $page_permalink ).'/%sa_sport%/'._x( 'schedule', 'slug', 'school-athletics' ) : $permalinks['schedule_base'];
		register_post_type( 'sa_schedule',
			array(
		        'label'               => __( 'schedule', 'school-athletics' ),
		        'description'         => __( 'Manage Scheudles.', 'school-athletics' ),
		        'labels'              => array(
							'name'                => __( 'Schedules', 'school-athletics' ),
							'singular_name'       => __( 'Schedule', 'school-athletics' ),
							'menu_name'           => __( 'Schedules', 'school-athletics' ),
							'parent_item_colon'   => __( 'Parent Item:', 'school-athletics' ),
							'all_items'           => __( 'Schedules', 'school-athletics' ),
							'view_item'           => __( 'View Schedule', 'school-athletics' ),
							'add_new_item'        => __( 'Add New Schedule', 'school-athletics' ),
							'add_new'             => __( 'Add New Schedule', 'school-athletics' ),
							'edit_item'           => __( 'Edit Schedule', 'school-athletics' ),
							'update_item'         => __( 'Update Schedule', 'school-athletics' ),
							'search_items'        => __( 'Search Schedule', 'school-athletics' ),
							'not_found'           => __( 'Not found', 'school-athletics' ),
							'not_found_in_trash'  => __( 'Not found in Trash', 'school-athletics' ),
				 ),
		        'supports'            => array( 'title','editor'),
		        'hierarchical'        => false,
		        'public'              => true,
		        'show_ui'             => \SchoolAthletics\Debug::status(),
		        'show_in_menu'        => false,
		        'show_in_nav_menus'   => false,
		        'show_in_admin_bar'   => false,
		        'can_export'          => true,
		        'has_archive'         => false,
		        'exclude_from_search' => false,
		        'publicly_queryable'  => true,
		        'rewrite'             => $schedule_permalink ? array( 'slug' => untrailingslashit( $schedule_permalink ), 'with_front' => false, 'feeds' => false, 'pages' => false ) : false,
		        'query_var'           => true,
		        'capability_type'     => 'post',
			)
		);*/

		// Register Event
		register_post_type( 'sa_event',
			array(
		        'label'               => __( 'event', 'school-athletics' ),
		        'description'         => __( 'Manage event calendar.', 'school-athletics' ),
		        'labels'              => array(
							'name'                => __( 'Event', 'school-athletics' ),
							'singular_name'       => __( 'Event', 'school-athletics' ),
							'menu_name'           => __( 'Events', 'school-athletics' ),
							'parent_item_colon'   => __( 'Parent Item:', 'school-athletics' ),
							'all_items'           => __( 'Events', 'school-athletics' ),
							'view_item'           => __( 'View Event', 'school-athletics' ),
							'add_new_item'        => __( 'Add New Event', 'school-athletics' ),
							'add_new'             => __( 'Add New Event', 'school-athletics' ),
							'edit_item'           => __( 'Edit Event', 'school-athletics' ),
							'update_item'         => __( 'Update Event', 'school-athletics' ),
							'search_items'        => __( 'Search Events', 'school-athletics' ),
							'not_found'           => __( 'Not found', 'school-athletics' ),
							'not_found_in_trash'  => __( 'Not found in Trash', 'school-athletics' ),
				 ),
		        'supports'            => array( 'title','editor'),
		        'hierarchical'        => false,
		        'public'              => true,
		        'show_ui'             => true,
		        'show_in_menu'        => false,
		        'show_in_nav_menus'   => false,
		        'show_in_admin_bar'   => false,
		        'menu_position'       => 36,
		        'can_export'          => true,
		        'has_archive'         => false,
		        'exclude_from_search' => false,
		        'publicly_queryable'  => true,
		        'rewrite'             => false,
		        'query_var'           => false,
		        'capability_type'     => 'post',
			)
		);

		// Register Event
		$staff_permalink = empty( $permalinks['staff_base'] ) ? untrailingslashit( $page_permalink ).'/%sa_sport%/'._x( 'staff', 'slug', 'school-athletics' ) : $permalinks['staff_base'];
		register_post_type( 'sa_staff',
			array(
		        'label'               => __( 'staff', 'school-athletics' ),
		        'description'         => __( 'Manage Staff.', 'school-athletics' ),
		        'labels'              => array(
							'name'                => __( 'Staff', 'school-athletics' ),
							'singular_name'       => __( 'Staff', 'school-athletics' ),
							'menu_name'           => __( 'Staff', 'school-athletics' ),
							'parent_item_colon'   => __( 'Staff Item:', 'school-athletics' ),
							'all_items'           => __( 'Staff', 'school-athletics' ),
							'view_item'           => __( 'View Staff', 'school-athletics' ),
							'add_new_item'        => __( 'Add New Staff', 'school-athletics' ),
							'add_new'             => __( 'Add New Staff', 'school-athletics' ),
							'edit_item'           => __( 'Edit Staff', 'school-athletics' ),
							'update_item'         => __( 'Update Staff', 'school-athletics' ),
							'search_items'        => __( 'Search Staff', 'school-athletics' ),
							'not_found'           => __( 'Not found', 'school-athletics' ),
							'not_found_in_trash'  => __( 'Not found in Trash', 'school-athletics' ),
				 ),
		        'supports'            => array( 'title','editor','thumbnail'),
		        'hierarchical'        => false,
		        'public'              => true,
		        'show_ui'             => \SchoolAthletics\Debug::status(),
		        'show_in_menu'        => false,
		        'show_in_nav_menus'   => false,
		        'show_in_admin_bar'   => false,
		        'can_export'          => true,
		        'has_archive'         => false,
		        'exclude_from_search' => false,
		        'publicly_queryable'  => true,
		        'rewrite'             => $staff_permalink ? array( 'slug' => untrailingslashit( $staff_permalink ), 'with_front' => false, 'feeds' => false, 'pages' => false) : false,
		        'query_var'           => true,
		        'capability_type'     => 'post',
			)
		);


	}

	//Update the term link to the sports home page
	public static function term_link_filter( $url, $term, $taxonomy ) {
		if ($term->taxonomy == 'sa_sport'){
			$sport_home_id = get_term_meta( $term->term_id, 'sa_sport_home_id', true );
			$url = get_permalink($sport_home_id);
			return $url;
		}
	}

	/**
	 * Register core post status.
	 */
	public static function register_post_status() {

		register_post_status( 'event_for_schedule', array(
			'label'                     => _x( 'Event for Schedule', 'post', 'school-athletics'),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => false,
			'show_in_admin_status_list' => true,//if DEBUG
			'label_count'               => _n_noop( 'Event for Schedule <span class="count">(%s)</span>', 'Events for Schedule <span class="count">(%s)</span>', 'school-athletics'),
		) );

	}

	/*
	 * Post Type Link
	 */
	public static function post_type_link($permalink, $post){

		if ( $post->post_type == 'sa_schedule' || $post->post_type == 'sa_roster' || $post->post_type == 'sa_roster_member' || $post->post_type == 'sa_staff'){

			// Abort early if the placeholder rewrite tag isn't in the generated URL.
			if ( false === strpos( $permalink, '%' ) ) {
				return $permalink;
			}

			$sports = get_the_terms( $post->ID, 'sa_sport' );
			if ( ! empty( $sports ) ) {
				$permalink =  str_replace( '%sa_sport%' , $sports[0]->slug , $permalink );
			}else{
				$permalink =  str_replace( '%sa_sport%' ,'no-sport', $permalink );
			}

			$seasons = get_the_terms( $post->ID, 'sa_season' );
			if ( ! empty( $seasons ) ) {
				$permalink =  str_replace( '%sa_season%' , $seasons[0]->slug , $permalink );
			}else{
				$permalink =  str_replace( '%sa_season%' ,'no-sa_season', $permalink );
			}

		}
		return $permalink;

	}

	/*
	 * Torture
	 */
	public static function rewrite_tags() {
		add_rewrite_tag( '%sa_sport%', '([^&]+)');
		add_rewrite_tag( '%sa_season%', '([^&]+)');
		$permalinks = get_option( 'schoolathletics_permalinks' );
		//add_rewrite_rule($permalinks['base'].'/([^/]*)/'._x('schedule', 'slug', 'school-athletics').'/([^&]+)/?$','index.php?sa_schedule=$matches[2]','top');
		add_rewrite_rule($permalinks['base'].'/([^/]*)/'._x('roster', 'slug', 'school-athletics').'/([^&]+)/([^&]+)/?$','index.php?sa_roster_member=$matches[3]','top');
		//add_rewrite_rule($permalinks['base'].'/([^/]*)/'._x('roster', 'slug', 'school-athletics').'/([^&]+)/?$','index.php?sa_roster=$matches[2]','top');
		add_rewrite_rule($permalinks['base'].'/([^/]*)/'._x('staff', 'slug', 'school-athletics').'/([^&]+)/?$','index.php?sa_staff=$matches[2]','top');
	}	

}

\SChoolAthletics\InstallWpObjects::init();