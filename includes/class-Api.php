<?php 
/**
 * Main API class.
 *
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
 * API Class.
 */
class Api {
	/**
	 * Hook in methods.
	 */
	public function __construct() {
		add_action( 'rest_api_init', array(__CLASS__, 'register_routes') );
	}

	/**
	 * Register API Routes.
	 */
	public static function register_routes(){
		//Remove Later
		register_rest_route( 'schoolathletics', '/test', array(
		    'methods' => 'GET',
		    'callback' => array(__CLASS__, 'test'),
		) );


		register_rest_route( 'schoolathletics', '/sport/(?P<sport>\d+)', array(
		    'methods' => 'GET',
		    'callback' => array(__CLASS__, 'sport'),
		) );
		register_rest_route( 'schoolathletics', '/sports', array(
		    'methods' => 'GET',
		    'callback' => array(__CLASS__, 'sports'),
		) );
		register_rest_route( 'schoolathletics', '/roster', array(
		    'methods' => 'GET',
		    'callback' => array(__CLASS__, 'roster'),
		) );
		register_rest_route( 'schoolathletics', '/schedule', array(
		    'methods' => 'GET',
		    'callback' => array(__CLASS__, 'schedule'),
		) );
		register_rest_route( 'schoolathletics', '/news', array(
		    'methods' => 'GET',
		    'callback' => array(__CLASS__, 'news'),
		) );
		register_rest_route( 'schoolathletics', '/user', array(
		    'methods' => 'GET',
		    'callback' => array(__CLASS__, 'user'),
		) );
		register_rest_route( 'schoolathletics', '/register', array(
		    'methods' => 'GET',
		    'callback' => array(__CLASS__, 'register'),
		) );
	}

	public static function test($data){
		return $_GET;
		//You can use get to filter the response for certain types of data.
	}
	
	/**
	 * List all Sports
	 */
	public static function sports(){
		return \SchoolAthletics\Sport::get_sports();
	}

	public static function sport($data){
		return \SchoolAthletics\Sport::get_sport($data['sport']);
	}

	/**
	 * Roster API. Would like to clean it up better. Need to design roster class better.
	 */
	public static function roster(){
		$roster = array();
		if(!isset($_GET['sport'])){
			$roster['errors'] = 'No sport selected.';
			return $roster;
		}

		$args = array();
		$args['posts_per_page'] = 1;
		$args['post_type'] = 'sa_page';
		$args['orderby'] = 'title';
		$args['order'] = 'DESC';
		$args['tax_query'] = array();
		$args['tax_query'][] = array(
								'taxonomy' => 'sa_sport',
								'field' => 'slug',
								'terms' => $_GET['sport'], // Where term_id of Term 1 is "1".
							);
		$args['tax_query'][] = array(
								'taxonomy' => 'sa_page_type',
								'field' => 'name',
								'terms' => 'Roster',
							);
		$_rosters = get_posts($args);
		foreach ($_rosters as $_roster) {
			if(is_object($_roster)){
				$_sports = get_the_terms($_roster,'sa_sport');
				$_sport = $_sports[0];
				$_seasons = get_the_terms($_roster,'sa_season');
				$_season = $_seasons[0];
				$roster['ID'] = $_roster->ID;
				$roster['title'] = $_roster->post_title.' '.$_sport->name.' '.__('Roster', 'school-athletics');
				$roster['content'] = $_roster->post_content;
				$roster['season'] = $_season->name;
				$roster['season_id'] = $_season->term_id;
				$roster['sport'] = $_sport->name;
				$roster['sport_id'] = $_sport->term_id;
				$roster['thumbnail'] = get_post_thumbnail_id($_roster->ID);
				$roster['permalink'] = get_permalink($_roster->ID);
				$roster = self::get_athletes($_sport->term_id,$_season->term_id);
				return $roster;
			}else{
				$roster['errors'] = 'Failed to create roster object';
				return $roster;
			}
		}
		$roster['errors'] = 'No rosters found.';
		return $roster;
	}

	/**
	 * Get Athletes
	 */
	public static function get_athletes($sport,$season){
		$args = array(
			'posts_per_page' => -1,
			'post_type' => 'sa_page',
			'tax_query' => array(
				array(
					'taxonomy' => 'sa_sport',
					'field' => 'id',
					'terms' => $sport,
				),
				array(
					'taxonomy' => 'sa_season',
					'field' => 'id',
					'terms' => $season,
				),
				array(
					'taxonomy' => 'sa_page_type',
					'field' => 'name',
					'terms' => 'Athlete',
				)
			),
			'meta_key' => 'sa_order',
            'orderby' => 'meta_value_num',
            'order' => 'ASC'
		);
		$_athletes = get_posts($args);
		$athletes = array();
		foreach ($_athletes as $athlete) {
			$status = get_the_terms($athlete,'sa_athlete_status');
			$status = (is_array($status)) ? array_pop($status) : null;
			$status = ($status) ? $status->name : '- - -';
			$athletes[] = array(
					'ID'	 => $athlete->ID,
					'photo'  => get_post_thumbnail_id( $athlete->ID ),
					'thumbnail_url' => wp_get_attachment_image_src( get_post_thumbnail_id( $athlete->ID ), 'thumbnail'),
					'jersey' => get_post_meta( $athlete->ID, 'sa_jersey', true ),
					'name'   => get_post_meta( $athlete->ID, 'sa_name', true ),
					'height' => get_post_meta( $athlete->ID, 'sa_height', true ),
					'weight' => get_post_meta( $athlete->ID, 'sa_weight', true ),
					'order'  => get_post_meta( $athlete->ID, 'sa_order', true ),
					'status' => $status,
				);
		}
		if(empty($athletes)){
			return array('errors' => 'No athletes found.' );
		}else{
			return $athletes;
		}
	}

	public static function schedule(){
		$events = array();

		$args = array();
		$args['posts_per_page'] = 25;
		$args['post_type'] = 'sa_event';
		$args['meta_key'] = 'sa_start'; 
		$args['orderby'] = 'meta_value';
		$args['order'] = 'ASC';
		$args['tax_query'] = array();
		if(!isset($_GET['sport'])){
		$args['meta_query'] = array( 
				array(         // restrict posts based on meta values
                  'key'     => 'sa_start',  // which meta to query
                  'value'   => date("Y-m-d h:m:s"),  // value for comparison
                  'compare' => '>=',          // method of comparison
                  'type'    => 'DATE'         // datatype, we don't want to compare the string values
                ) // end meta_query array
              );
		}
		if(isset($_GET['sport'])){
			$args['tax_query'][] = array(
								'taxonomy' => 'sa_sport',
								'field' => 'slug',
								'terms' => $_GET['sport'], // Where term_id of Term 1 is "1".
							);
		}

		$_events = get_posts($args);
		foreach ($_events as $_event) {
			$sport = get_the_terms($_event,'sa_sport');
			$sport = (is_array($sport)) ? array_pop($sport) : null;
			$sport = ($sport) ? $sport->name : 'No Sport';

			$location = get_the_terms($_event,'sa_location');
			$location = (is_array($location)) ? array_pop($location) : null;
			$location = ($location) ? $location->name : '- - -';

			$game_type = get_the_terms($_event,'sa_game_type');
			$game_type = (is_array($game_type)) ? array_pop($game_type) : null;
			$game_type = ($game_type) ? $game_type->name : '- - -';

			$outcome = get_the_terms($_event,'sa_outcome');
			$outcome = (is_array($outcome)) ? array_pop($outcome) : null;
			$outcome = ($outcome) ? $outcome->name : '- - -';

			$events[] = array(
					'ID'	 => $_event->ID,
					'sport'	 => $sport,
					'date'  => get_post_meta( $_event->ID, 'sa_start', true ),
					'name'   => get_post_meta( $_event->ID, 'sa_name', true ),
					'result' => get_post_meta( $_event->ID, 'sa_result', true ),
					'location' => $location,
					'game_type' => $game_type,
					'outcome' => $outcome,
					'order' => get_post_meta( $_event->ID, 'sa_order', true ),
				);
		}
		if(empty($events)){
			$events['errors'] = 'No events found.';
			return $events;
		}else{
			return $events;
		}
	}

	public static function news(){
		$news = array();
		$args = array();
		$args['posts_per_page'] = 5;
		$args['post_type'] = 'post';
		$args['post_status'] = 'publish';
		$args['order'] = 'DESC';
		$args['tax_query'] = array();
		if(isset($_GET['sport']) && $_GET['sport'] != null){
			$args['tax_query'][] = array(
								'taxonomy' => 'sa_sport',
								'field' => 'slug',
								'terms' => $_GET['sport'], // Where term_id of Term 1 is "1".
							);
		}
		$posts = get_posts($args);
		foreach ($posts as $post) {
			$sport = get_the_terms($post,'sa_sport');
			$sport = (is_array($sport)) ? array_pop($sport) : null;
			$sport = ($sport) ? $sport->name : 'No Sport';
			$news[] = array(
					'ID'	 => $post->ID,
					'thumbnail' => wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium'),
					'title' => $post->post_title,
					'excerpt' => wp_trim_words($post->post_content, 150),
					'content' => $post->post_content,
					'date' => $post->post_post_date,
					'link' => get_permalink($post->ID),
				);
		}
		if(empty($news)){
			$news['errors'] = 'No news found.';
			return $news;
		}else{
			return $news;
		}
	}

	/**
	 * Authenticate User
	 */
	public static function user(){
		$username = $_GET['username']; //Hash this and then decode
		$password = $_GET['password']; //Hash this and then decode
		//$user = wp_signon( $creds, false );
		//wp_set_current_user($user->ID);
		$user = wp_authenticate($username,$password);
		//$cookie = wp_generate_auth_cookie($user->ID,999999,'auth');
		//return wp_parse_auth_cookie($cookie);
		return $user;
		//wp_set_auth_cookie($user->ID, 0, 0);
		//wp_validate_auth_cookie($cookie);
		//return wp_validate_auth_cookie();
		//return is_user_logged_in();
		//return wp_get_current_user();
	}

	/**
	 * Allow Registration through API
	 * Specifally used for points and the APP
	 * Needs an option to disable registration
	 */
	public static function register(){
		$userdata = array(
		    'user_login'  =>  'test',
		    'user_nicename' => 'Test',
		    'user_email' => '',
		    'first_name' => '',
		    'last_name' => '',
		    'description' => '',
		    'role' => 'subscriber',
		    'user_url'    =>  '',
		    'user_pass'   =>  'test',
		);
		return wp_insert_user( $userdata ) ;
	}

}