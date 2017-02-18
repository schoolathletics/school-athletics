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

		/*
		 * Default Template
		 */
		if ( is_single() && get_post_type() == 'sa_page' ) {
			$file 	= 'single-sa_page.php';
			$find[] = $file;
			$find[] = SA__PLUGIN_DIR . 'templates/'. $file;

		}

		/*
		 * Roster Template
		 */
		if ( is_single() && get_post_type() == 'sa_page' && get_the_title() == 'Roster') {
			$file 	= 'single-sa_page-roster.php';
			$find[] = $file;
			$find[] = SA__PLUGIN_DIR . 'templates/'. $file;
		}

		/*
		 * Schedule Template
		 */
		if ( is_single() && get_post_type() == 'sa_page' && get_the_title() == 'Schedule') {
			$file 	= 'single-sa_page-schedule.php';
			$find[] = $file;
			$find[] = SA__PLUGIN_DIR . 'templates/'. $file;
		}

		/*
		 * Staff Template
		 */
		if ( is_single() && get_post_type() == 'sa_page' && get_the_title() == 'Staff') {
			$file 	= 'single-sa_page-staff.php';
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
