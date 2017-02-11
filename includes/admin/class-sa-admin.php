<?php 
/**
 * Admin page foundation.
 *
 * @author   Dwayne Parton
 * @category Admin
 * @package  SchoolAthletics/Admin
 * @version  0.0.1
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * SA_Admin Class.
 */
class SA_Admin {

	/**
	 * Hook in methods.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'includes' ) );
		add_filter( 'display_post_states', array( $this, 'post_states' ), 10, 2 );
	}

	/**
	 * Include any classes we need within admin.
	 */
	public function includes(){
		require SA__PLUGIN_DIR . 'includes/admin/class-sa-admin-notice.php';
		require SA__PLUGIN_DIR . 'includes/admin/class-sa-admin-menus.php';
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

return new SA_Admin();