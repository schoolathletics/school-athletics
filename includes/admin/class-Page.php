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

		wp_enqueue_media();
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('wp-jquery-ui-autocomplete');
		wp_enqueue_style('wp-jquery-ui-autocomplete');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-slider');
		wp_enqueue_script( 'jquery-ui-timepicker-addon', SA__PLUGIN_URL.'includes/admin/assets/js/jquery-ui-timepicker-addon.js');
		wp_enqueue_style( 'jquery-ui-timepicker-addon', SA__PLUGIN_URL.'includes/admin/assets/css/jquery-ui-timepicker-addon.css');
		wp_enqueue_script( 'school-athletics', SA__PLUGIN_URL.'includes/admin/assets/js/ui.js');
		wp_enqueue_style( 'school-athletics', SA__PLUGIN_URL.'includes/admin/assets/css/admin.css');
	}

	/**
	 * Admin page header
	 */
	public static function header(){
		include_once( 'views/html-admin-elements-header.php' );
	} 
	
	/**
	 * Probably should load scripts and styles with this. But it doesn't work yet, so just calling wp_enqueue_style() in the __construct.
	 */
	public function scripts(){
		//wp_enqueue_style( 'school-athletics', SA__PLUGIN_URL.'assets/css/admin.css');
	}

	/**
	 * Admin page foot text
	 */
	public static function footer_text(){
		$footer_text = sprintf( __( 'If you like <strong>School Athletics</strong> please leave us a %s&#9733;&#9733;&#9733;&#9733;&#9733;%s rating. A huge thanks in advance!', 'school-athletics' ), '<a href="https://wordpress.org/support/view/plugin-reviews/school-athletics?filter=5#postform" target="_blank">', '</a>' );
		return $footer_text;
	}

	/**
	 * Form to force season and sport choice.
	 */
	public static function wizard() {
		include_once( 'pages/views/html-admin-page-wizard.php' );
	}

	/**
	 * Parse CSV string with , delimiter and line break to end row.
	 */
	public static function parse_csv($csv) {
		$lines = explode(PHP_EOL, $csv);
		$_csv = array();
		foreach ($lines as $line) {
			$_csv[] = str_getcsv($line,",");
		}
		$all_rows = array();
		$header = null;
		foreach ($_csv as $c) {
			if ($header === null) {
				$header = $c;
				continue;
			}
			$all_rows[] = array_combine($header, $c);
		}
		return $all_rows;
	}

}