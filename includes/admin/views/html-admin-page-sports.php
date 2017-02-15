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
	<?php
		switch ( $action ) {
			case "edit" :
				\SchoolAthletics\Admin\SportsAdmin::edit();
			break;
			default :
				\SchoolAthletics\Admin\SportsAdmin::get_list();
			break;
		}
	?>
</div>

<?php 

\SchoolAthletics\Debug::file_path(SA__PLUGIN_DIR .'includes/admin/views/html-admin-page-sports.php');

?>