<?php
/**
 * Initiate school athletics.
 *
 * @author   Dwayne Parton
 * @category Class
 * @package  SchoolAthletics
 * @version  0.0.1
 */


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
		if(is_admin()){
			new \SchoolAthletics\Admin\Admin();
		}
		new \SchoolAthletics\Debug();
		\SchoolAthletics\TemplateLoader::init();
		\SchoolAthletics\Shortcodes::init();
	}

	/**
	 * Get Sport
	 */
	public static function get_sport($post){
		$terms = wp_get_post_terms( $post->ID, 'sa_sport');
		return get_term( $terms[0]->term_id);
	}


}
