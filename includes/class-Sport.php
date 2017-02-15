<?php 
/**
 * Main sport class.
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
 * Sport Class.
 */
class Sport {

	/**
	 * Hook in methods.
	 */
	public function __construct() {
	}

	public function getSport($sport){
		if(!is_object($sport)){
			$sport = get_term($sport);
		}
		return $sport;
	}

	/*
	 * Get Sport ID form the Sport Object
	 */
	public function getSportID($sport){
		if(is_object($sport)){
			$sport = $sport->term_id;
		}
		return $sport;
	}

}