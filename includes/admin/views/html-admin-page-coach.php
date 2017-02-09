<?php
/**
 * Admin View: Page - Status
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$action = ! empty( $_REQUEST['action'] ) ? sanitize_title( $_REQUEST['action'] ) : 'action';
global $title;
?>

<div class="wrap">
	<h1 class="screen-reader-text">$title</h1>
	<?php
		switch ( $action ) {
			case "edit" :
				echo 'add action';
			break;
			default :
				echo 'Default = SA_Admin_Coach::default()';
			break;
		}
	?>
</div>

<?php SchoolAthletics::debug_file_path(SA__PLUGIN_DIR .'includes/admin/views/html-admin-page-coach.php'); ?>