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

	public $page_type = 'roster';

	/** @var int post ID for roster page. */
	public $ID = '';

	/** @var string post ID for roster page. */
	public $title = '';

	/** @var string post ID for roster page. */
	public $content = '';

	/** @var int thumbnail ID. */
	public $thumbnail = '';

	/** @var string sport that roster belongs to. */
	public $sport = '';

	/** @var int sport that roster belongs to. */
	public $sport_id = '';

	/** @var string season that roster belongs to. */
	public $season = '';

	/** @var int season that roster belongs to. */
	public $season_id = '';

	/** @var string season that roster belongs to. */
	public $permalink = '';

	/** @var object season that roster belongs to. */
	//public $athletes = '';

	/** @var array options */
	public $options = array();

	/** @var array Contains roster errors */
	public $errors = array();

	/**
	 * Constructor
	 */
	public function __construct($post = null) {
		$season = null;

		if(is_object($post)){
			$sports = get_the_terms($post,'sa_sport');
			$sport = $sports[0]->term_id;
		}else{
			$sport = $_REQUEST['sport'];
			$season = $_REQUEST['season'];
		}

		if(is_object($post) && has_term('roster','sa_page_type')){
			$seasons = get_the_terms($post,'sa_season');
			$season = $seasons[0]->term_id;
		}
		
		$this->get_roster($sport, $season);
	}

	/**
	 * Get members for admin
	 */
	public function get_roster($sport, $season = null){
		$args = array();
		$args['posts_per_page'] = 1;
		$args['post_type'] = 'sa_page';
		$args['orderby'] = 'title';
		$args['order'] = 'DESC';
		$args['tax_query'] = array();
		$args['tax_query'][] = array(
								'taxonomy' => 'sa_sport',
								'field' => 'id',
								'terms' => $sport, // Where term_id of Term 1 is "1".
							);
		$args['tax_query'][] = array(
								'taxonomy' => 'sa_page_type',
								'field' => 'name',
								'terms' => 'Roster',
							);
		if(isset($season)){
		$args['tax_query'][] = array(
								'taxonomy' => 'sa_season',
								'field' => 'id',
								'terms' => $season, // Where term_id of Term 1 is "1".
							);
		}
		$rosters = get_posts($args);
		foreach ($rosters as $roster) {
			if(is_object($roster)){
				$sports = get_the_terms($roster,'sa_sport');
				$sport = $sports[0];
				$seasons = get_the_terms($roster,'sa_season');
				$season = $seasons[0];
				$this->ID = $roster->ID;
				$this->title = $roster->post_title.' '.$sport->name.' '.__('Roster', 'school-athletics');
				$this->content = $roster->post_content;
				$this->season = $season->name;
				$this->season_id = $season->term_id;
				$this->sport = $sport->name;
				$this->sport_id = $sport->term_id;
				$this->thumbnail = get_post_thumbnail_id($roster->ID);
				$this->permalink = get_permalink($roster->ID);
				return true;
			}else{
				return false;
			}
		}
		$sport = get_term($sport);
		$season = get_term($season);
		$this->title = $season->name.' '.$sport->name.' '.__('Roster', 'school-athletics');
		$this->season = $season->name;
		$this->season_id = $season->term_id;
		$this->sport = $sport->name;
		$this->sport_id = $sport->term_id;
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
	public function get_athletes(){
		$args = array(
			'posts_per_page' => -1,
			'post_type' => 'sa_page',
			'tax_query' => array(
				array(
					'taxonomy' => 'sa_sport',
					'field' => 'id',
					'terms' => $this->sport_id,
				),
				array(
					'taxonomy' => 'sa_season',
					'field' => 'id',
					'terms' => $this->season_id,
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
	 * Creates a roster list from the roster object.
	 */
	public function dropdown(){

		if(is_object($this)){
			$rosters = $this::get_rosters($this->sport_id);
			echo '<select class="select" onChange="window.location.href=this.value">';
				foreach ($rosters as $roster) {
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