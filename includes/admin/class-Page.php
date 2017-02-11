<?php 
/**
 * Admin page foundation.
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
 * Page Class.
 */
class Page {

	public function __construct(){
		$this->header();
	}

	public function post_states( $states, $post ) {
		if ( 'page' == $post->post_type ) {
			if (! empty(get_post_meta($post->ID, 'schoolathletics_page', true)) ) {
				$url = '#';
				$states['schoolathletics'] = sprintf(
					'<a style="%2$s" href="%3$s">%1$s</a>',
					__( 'School Athletics', 'schoolathletics' ),
					'background:#aaa;color:#fff;padding:1px 4px;border-radius:4px;font-size:0.8em',
					$url
				);
			}
		}

		return $states;
	}


	public static function header(){
		include_once( 'views/html-admin-elements-header.php' );
	} 

	public static function footer(){
		include_once( 'views/html-admin-elements-footer.php' );
	} 

}