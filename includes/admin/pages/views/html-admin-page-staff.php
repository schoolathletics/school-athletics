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
				\SchoolAthletics\Admin\Pages\Staff::edit();
			break;
			default :
				\SchoolAthletics\Admin\Pages\Staff::get_list();
			break;
		}
	?>
</div>

<?php \SchoolAthletics\Debug::file_path('includes/admin/pages/views/html-admin-page-staff.php'); ?>