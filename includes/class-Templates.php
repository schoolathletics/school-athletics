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
 * Template class.
 */
class Templates {

	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_filter( 'template_include', array( __CLASS__, 'template_loader' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'template_enqueue_scripts' ) );
		self::do_actions();
	}

	/*
	 * Template Enqueue Styles and Scripts
	 */
	public static function template_enqueue_scripts(){
		wp_enqueue_style( 'school-athletics', SA__PLUGIN_URL.'assets/css/schoolathletics.css', array(), SA_VERSION);
	}

	/*
	 * Template Actions
	 */
	public static function do_actions(){
		/**
		 * WP Header.
		 *
		 * @see  generator_tag()
		 */
		add_action( 'get_the_generator_html', array( __CLASS__, 'generator_tag' ), 10, 2 );
		add_action( 'get_the_generator_xhtml', array( __CLASS__, 'generator_tag' ), 10, 2 );
		/**
		 * Wrapper Start
		 *
		 * @see  wrapper_start()
		 */
		add_action( 'schoolathletics_before_content', array( __CLASS__, 'wrapper_start' ), 10 );
		/**
		 * Menu
		 *
		 * @see  menu()
		 */
		add_action( 'schoolathletics_before_content', array( __CLASS__, 'menu' ), 20 );
		/**
		 * Menu
		 *
		 * @see  content()
		 */
		add_action( 'schoolathletics_content', array( __CLASS__, 'content' ), 10 );
		/**
		 * Wrapper End
		 *
		 * @see  wrapper_end()
		 */
		add_action( 'schoolathletics_after_content', array( __CLASS__, 'wrapper_end' ), 10 );
	}

	/*
	 * Wrapper Start
	 */
	public static function wrapper_start(){
		include(SA__PLUGIN_DIR .'templates/global/wrapper-start.php');
	}

	/*
	 * Menu
	 */
	public static function menu(){
		global $post;
		$term = \SchoolAthletics::get_sport($post);
		//print_r($term);
		$args = array(
			'posts_per_page' => -1,
			'post_type' => 'sa_page',
			'orderby' => 'menu_order',
			'order' => 'DESC',
			'tax_query' => array(
				array(
					'taxonomy' => 'sa_sport',
					'field' => 'id',
					'terms' => $term->term_id, // Where term_id of Term 1 is "1".
				),
				array(
					'taxonomy' => 'sa_page_type',
					'field' => 'name',
					'terms' => 'page',
				)
			),
		);
		$pages = get_posts($args);
		include(SA__PLUGIN_DIR .'templates/global/menu.php');
	}

	/*
	 * Content
	 */
	public static function content(){
		global $post;
		switch ( $post ) {
			case has_term( 'home-archive', 'sa_page_type'):
				include(SA__PLUGIN_DIR .'templates/content-home.php');
				break;
			case has_term( 'roster-archive', 'sa_page_type') || has_term( 'roster', 'sa_page_type'):
				include(SA__PLUGIN_DIR .'templates/content-roster.php');
				break;
			case has_term( 'schedule-archive', 'sa_page_type') || has_term( 'schedule', 'sa_page_type'):
				include(SA__PLUGIN_DIR .'templates/content-schedule.php');
				break;
			default:
				include(SA__PLUGIN_DIR .'templates/content-page.php');
				break;
		}
	}

	/*
	 * Wrapper End
	 */
	public static function wrapper_end(){
		include(SA__PLUGIN_DIR .'templates/global/wrapper-end.php');
	}

	/*
	 * Generator Tag add version
	 */
	public static function generator_tag( $gen, $type ) {
		switch ( $type ) {
			case 'html':
				$gen .= "\n" . '<meta name="generator" content="School Athletics '.SA_VERSION.'">';
				break;
			case 'xhtml':
				$gen .= "\n" . '<meta name="generator" content="School Athletics '.SA_VERSION.'" />';
				break;
		}
		return $gen;		
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
			//default page template
			$file 	= 'single-sa_page.php';
			$find[] = $file;
			$find[] = SA__PLUGIN_DIR . 'templates/'. $file;

		}

		/*
		 * Override Template
		 */
		if ( $file ) {
			$template  = locate_template( array_unique( $find ) );
			if ( ! $template ) {
				$template = SA__PLUGIN_DIR . 'templates/' . $file;
			}
		}

		return $template;
	}


}
