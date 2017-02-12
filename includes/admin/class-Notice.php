<?php 
/**
 * Admin Notices.
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
 * Notice Class.
 */
class Notice {

	/**
	 * Returns a dismissible success notice
	 */
	public static function success($message){
		return self::view('success', $message);
	}

	/**
	 * Returns a dismissible success notice
	 */
	public static function warning($message){
		return self::view('warning', $message);
	}

	/**
	 * Returns a dismissible success notice
	 */
	public static function error($message){
		return self::view('error', $message);
	}

	/**
	 * The notice view
	 */
	private static function view($type,$message){
		?>
		<div class="notice notice-<?php echo $type; ?> is-dismissible"> 
			<p><strong><?php echo $message; ?></strong></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text"><?php _e('Dismiss this notice.', 'school-athletics'); ?></span>
			</button>
		</div>
		<?php
	}

}