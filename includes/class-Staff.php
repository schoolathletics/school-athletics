<?php 
/**
 * Main Staff class.
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
 * Staff Class.
 */
class Staff {

	/** @var object sport that roster belongs to. */
	public $sport = array();

	/** @var array options */
	public $options = array();

	/** @var array schedule page object */
	public $staff = array();

	/** @var array Contains events errors */
	public $errors = array();

	/**
	 * Hook in methods.
	 */
	public function __construct($sport) {
		$this->staff = $this->get_staff($sport->term_id);;
		$this->sport = $sport;
	}

	/**
	 * Get Schedules
	 */
	public static function get_staff($sport){
		$args = array(
			'posts_per_page' => -1,
			'post_type' => 'sa_staff',
			'tax_query' => array(
				array(
					'taxonomy' => 'sa_sport',
					'field' => 'id',
					'terms' => $sport,
				),
			),
			'order'   => 'ASC',
		);
		return get_posts($args);
	}


}