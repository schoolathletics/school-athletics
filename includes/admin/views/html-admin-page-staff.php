<?php
/**
 * Admin View: Page - Status
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$action = ! empty( $_REQUEST['action'] ) ? sanitize_title( $_REQUEST['action'] ) : 'action';
?>

<div class="wrap">
	<h1 class="wp-heading-inline"><?php _e('Staff','school-athletics'); ?></h1>
	<?php
		switch ( $action ) {
			case "edit" :
				SA_Admin_Staff::edit();
			break;
			default :
				SA_Admin_Staff::default();
			break;
		}
	?>
</div>

<?php SchoolAthletics::debug_file_path(SA__PLUGIN_DIR .'includes/admin/views/html-admin-page-staff.php'); ?>