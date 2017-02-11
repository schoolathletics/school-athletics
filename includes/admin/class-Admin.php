<?php 
/**
 * Initiate the school athletics admin. It all starts here.
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
 * Admin class.
 */
class Admin {

	/**
	 * Hook in methods.
	 */
	public function __construct() {
		add_filter( 'display_post_states', array( $this, 'post_states' ), 10, 2 );
		new \SchoolAthletics\Admin\Menus();
	}

}
