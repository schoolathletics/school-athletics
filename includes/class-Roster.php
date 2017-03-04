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
	public $rosters = array(
			array(
				'ID' => '',
				'season' => '', 
				'title' => '', 
				'permalink' => '',
			),
		);

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
		}else{
			$this->sport = get_term($_REQUEST['sport']);
			$this->season =  get_term($_REQUEST['season']);
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
			'post_type' => 'sa_page',
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
		$rosters = get_posts($args);
		foreach ($rosters as $roster) {
			if(is_object($roster)){
				return $roster;
			}else{
				return false;
			}
		}
	}

	/**
	 * Get Rosters
	 */
	public static function get_rosters($sport){
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
					'taxonomy' => 'sa_page_type',
					'field' => 'name',
					'terms' => 'Roster',
				),
			),
			'orderby' => 'title',
			'order'   => 'DESC',
		);
		$posts = get_posts($args);
		$rosters = array();
		foreach ($posts as $post) {
			$roster = array();
			$roster['ID'] = $post->ID;
			$season = get_the_terms( $post, 'sa_season' );
			$roster['season'] = $season[0]->slug;
			$roster['title'] = $post->post_title;
			$roster['permalink'] = get_permalink($post->ID);
			$rosters[] = $roster;
		}
		return $rosters;
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
		if(empty($athletes)){
			return array(
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
		}else{
			return $athletes;
		}
	}

	/**
	 * Get Coaches
	 */
	public static function get_coaches($sport,$season){

	}

	/**
	 * Creates a roster list from the roster object.
	 */
	public function dropdown(){

		if(is_object($this)){

			echo '<select class="select" onChange="window.location.href=this.value">';
				foreach ($this->rosters as $roster) {
					if($this->ID == $roster['ID']){
						$selected = 'selected';
					}else{
						$selected = '';
					}
					echo '<option value="'.$roster['permalink'].'" '.$selected.'>'.$roster['season'].'</option>';
				}
			echo '</select>';
		}
	}

}