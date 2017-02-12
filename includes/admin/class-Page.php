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
		add_filter( 'admin_footer_text', array( $this, 'footer_text' ), 1 );
	}

	/**
	 * Admin page header
	 */
	public static function header(){
		include_once( 'views/html-admin-elements-header.php' );
	} 

	/**
	 * Admin page foot text
	 */
	public static function footer_text(){
		$footer_text = sprintf( __( 'If you like <strong>School Athletics</strong> please leave us a %s&#9733;&#9733;&#9733;&#9733;&#9733;%s rating. A huge thanks in advance!', 'school-athletics' ), '<a href="https://wordpress.org/support/view/plugin-reviews/woocommerce?filter=5#postform" target="_blank">', '</a>' );
		return $footer_text;
	}

	/**
	 * Form to force season and sport choice.
	 */
	public static function wizard() {
		include_once( 'views/html-admin-page-wizard.php' );
	}

}