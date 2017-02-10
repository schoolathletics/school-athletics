<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SA_Admin_Notice {

	public static function success($message){
		return self::html('success', $message);
	}

	public static function warning($message){
		return self::html('warning', $message);
	}

	public static function error($message){
		return self::html('error', $message);
	}

	private static function html($type,$message){
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