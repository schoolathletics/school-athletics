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
	<h1><?php echo $title; ?></h1>
	<?php
		switch ( $action ) {
			case "edit" :
				SA_Admin_Sports::edit();
			break;
			default :
				SA_Admin_Sports::list();
			break;
		}
	?>
</div>

<?php 

SchoolAthletics::debug_file_path(SA__PLUGIN_DIR .'includes/admin/views/html-admin-page-sports.php'); 

?>