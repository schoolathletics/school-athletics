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
				\SchoolAthletics\Admin\Pages\Opponents::edit();
			break;
			default :
				\SchoolAthletics\Admin\Pages\Opponents::get_list();
			break;
		}
	?>
</div>

<?php 

\SchoolAthletics\Debug::file_path('includes/admin/pages/views/html-admin-page-sports.php');

?>