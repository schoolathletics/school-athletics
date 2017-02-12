<?php
/**
 * Initiate school athletics.
 *
 * @author   Dwayne Parton
 * @category Admin
 * @package  SchoolAthletics/Admin
 * @version  0.0.1
 */

namespace SchoolAthletics;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Main School Athletics Class.
 *
 */
class SchoolAthletics {
	
	/**
	 * School Athletics Constructor.
	 */
	public function __construct() {
		\SchoolAthletics\InstallWpObjects::init();
		new \SchoolAthletics\Admin\Admin();
		new \SchoolAthletics\Debug();	
	}

}
