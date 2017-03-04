<?php
/**
 * Template Loader class.
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
 * Template Loader class.
 */
class TemplateLoader {

	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_filter( 'template_include', array( __CLASS__, 'template_loader' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'template_enqueue_scripts' ) );
	}

	public static function template_enqueue_scripts(){
		wp_enqueue_style( 'school-athletics', SA__PLUGIN_URL.'assets/css/schoolathletics.css', array(), SA_VERSION);
	}

	/**
	 * Load a template.
	 *
	 * Handles template usage so that we can use our own templates instead of the themes.
	 *
	 * Templates are in the 'templates' folder. woocommerce looks for theme.
	 * overrides in /theme/woocommerce/ by default.
	 *
	 * For beginners, it also looks for a woocommerce.php template first. If the user adds.
	 * this to the theme (containing a woocommerce() inside) this will be used for all.
	 * woocommerce templates.
	 *
	 * @param mixed $template
	 * @return string
	 */
	public static function template_loader( $template ) {
		$file = '';
		global $post;
		/*
		 * Default Template
		 */
		if ( is_single() && get_post_type() == 'sa_page' ) {
			if($post->post_parent){
				//default page template
				$file 	= 'single-sa_page.php';
				$find[] = $file;
			}else{
				//If the page is a top level link show the home template
				$file 	= 'single-sa_page-home.php';
				$find[] = $file;
			}
			$find[] = SA__PLUGIN_DIR . 'templates/'. $file;

		}

		/*
		 * Roster Template
		 */
		if ( is_single() && has_term( 'roster', 'sa_page_type')  ) {
			$file 	= 'single-sa_roster.php';
			$find[] = $file;
			$find[] = SA__PLUGIN_DIR . 'templates/'. $file;
		}

		/*
		 * Roster Archive Template
		 */
		if ( is_single() && get_post_type() == 'sa_page' && get_the_title() == 'Roster') {
			$file 	= 'archive-sa_roster.php';
			$find[] = $file;
			$find[] = SA__PLUGIN_DIR . 'templates/'. $file;
		}

		/*
		 * Schedule Template
		 */
		if ( is_single() && has_term( 'schedule', 'sa_page_type') ) {
			$file 	= 'single-sa_schedule.php';
			$find[] = $file;
			$find[] = SA__PLUGIN_DIR . 'templates/'. $file;
		}

		/*
		 * Schedule Archive Template
		 */
		if ( is_single() && get_post_type() == 'sa_page' && get_the_title() == 'Schedule' ) {
			$file 	= 'archive-sa_schedule.php';
			$find[] = $file;
			$find[] = SA__PLUGIN_DIR . 'templates/'. $file;
		}

		/*
		 * Staff Template
		 */
		if ( is_single() && get_post_type() == 'sa_staff' ) {
			$file 	= 'single-sa_staff.php';
			$find[] = $file;
			$find[] = SA__PLUGIN_DIR . 'templates/'. $file;
		}

		/*
		 * Staff Archive Template
		 */
		if ( is_single() && get_post_type() == 'sa_page' && get_the_title() == 'Staff' ) {
			$file 	= 'archive-sa_staff.php';
			$find[] = $file;
			$find[] = SA__PLUGIN_DIR . 'templates/'. $file;
		}

		/*
		 * Override Template
		 */
		if ( $file ) {
			$template       = locate_template( array_unique( $find ) );
			if ( ! $template ) {
				$template = SA__PLUGIN_DIR . 'templates/' . $file;
			}
		}

		return $template;
	}

}
