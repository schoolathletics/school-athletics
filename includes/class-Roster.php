<?php 
/**
 * Main roster class.
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
 * Roster Class.
 */
class Roster {
	/** @var int post ID for roster page. */
	public $ID = '';

	/** @var string post ID for roster page. */
	public $title = '';

	/** @var string post ID for roster page. */
	public $content = '';

	/** @var object sport that roster belongs to. */
	public $sport = array();

	/** @var object season that roster belongs to. */
	public $season = array();

	/** @var array options */
	public $options = array();

	/** @var object Roster page object */
	public $roster = array();

	/** @var array List of sport Rosters */
	public $rosters = array();

	/** @var array Contains roster athletes */
	public $athletes = array(
		array(
				'ID'     => 0,
				'photo'  => '',
				'jersey' => '',
				'name'   => '',
				'height' => '',
				'weight' => '',
				'status' => '',
				'order' => '',
			),
	);

	/** @var array Contains roster coaches */
	public $coaches = array();

	/** @var array Contains roster errors */
	public $errors = array();


	/**
	 * Constructor
	 */
	public function __construct($post = null) {
		if(!$post){
			$post = $this->get_roster();
		}

		if(is_object($post)){
			$sports = get_the_terms($post,'sa_sport');
			$sport = $sports[0];
			$seasons = get_the_terms($post,'sa_season');
			$season = $seasons[0];
			$this->ID = $post->ID;
			$this->title = $post->post_title;
			$this->content = $post->post_content;
			$this->roster = $post;
			$this->sport = $sport;
			$this->season = $season;
			$this->athletes = $this->get_athletes($sport->term_id,$season->term_id);
			$this->rosters = $this->get_rosters($sport->term_id);
		}
	}

	/**
	 * Get members for admin
	 */
	public function get_roster(){
		$sport = $_REQUEST['sport'];
		$season = $_REQUEST['season'];

		$args = array(
			'posts_per_page' => 1,
			'post_type' => 'sa_roster',
			'tax_query' => array(
				array(
					'taxonomy' => 'sa_sport',
					'field' => 'id',
					'terms' => $sport, // Where term_id of Term 1 is "1".
				),
				array(
					'taxonomy' => 'sa_season',
					'field' => 'id',
					'terms' => $season,
				)
			),
	    );
		$roster = get_posts($args);
		return $roster[0];
	}

	/**
	 * Get Rosters
	 */
	public static function get_rosters($sport){
		$args = array(
			'posts_per_page' => -1,
			'post_type' => 'sa_roster',
			'tax_query' => array(
				array(
					'taxonomy' => 'sa_sport',
					'field' => 'id',
					'terms' => $sport,
				),
			),
			'orderby' => 'taxonomy_sa_season',
			'order'   => 'ASC',
		);
		return get_posts($args);
	}

	/**
	 * Get Athletes
	 */
	public static function get_athletes($sport,$season){
		$args = array(
			'posts_per_page' => -1,
			'post_type' => 'sa_roster_member',
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
					'jersey' => get_post_meta( $athlete->ID, 'sa_jersey', true ),
					'name'   => get_post_meta( $athlete->ID, 'sa_name', true ),
					'height' => get_post_meta( $athlete->ID, 'sa_height', true ),
					'weight' => get_post_meta( $athlete->ID, 'sa_weight', true ),
					'order'  => get_post_meta( $athlete->ID, 'sa_order', true ),
					'status' => $status,
				);
		}
		return $athletes;
	}

	/**
	 * Get Coaches
	 */
	public static function get_coaches($sport,$season){

	}

}