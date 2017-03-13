<?php 
/**
 * Person class.
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
 * Person Class.
 */
class Person {

	/** @var object sport that roster belongs to. */
	public $ID = '';

	/** @var array options */
	public $attributes = array();

	/**
	 * Hook in methods.
	 */
	public function __construct($sport) {
		//$this->staff = $this->get_staff($sport->term_id);;
		//$this->sport = $sport;
	}



}