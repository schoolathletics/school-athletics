<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin View: Staff
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$action = ! empty( $_REQUEST['action'] ) ? sanitize_title( $_REQUEST['action'] ) : 'action';
?>

<div class="wrap">
	<?php
		switch ( $action ) {
			case "edit" :
				\SchoolAthletics\Admin\Staff::edit();
			break;
			default :
				\SchoolAthletics\Admin\Staff::get_list();
			break;
		}
	?>
</div>

<?php \SchoolAthletics\Debug::file_path(SA__PLUGIN_DIR .'includes/admin/views/html-admin-page-staff.php'); ?>