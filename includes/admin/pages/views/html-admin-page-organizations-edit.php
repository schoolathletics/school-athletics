<?php
/**
 * Admin View: Page - Status
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if(isset($_GET['organization'])){
	$organization = get_term( $_GET['organization']);
	$title = __( 'Edit', 'school-athletics' ).' '.$organization->name;
	$name = $organization->name;
	$slug = $organization->slug;
	$term_id = $organization->term_id;
	$photo = get_term_meta($term_id, 'sa_mascot', true );
	$organization_thumbnail = false;
}else{
	$title = __( 'New Organization', 'school-athletics' );
	$organization = '';
	$slug ='';
	$name = '';
	$organization_thumbnail = false;
}
?>
<h1 ><?php echo $title; ?></h1>
<p></p>
<form method="POST" action="options.php">
	<table class="wp-list-table widefat striped pages">
		<tr>
			<th><label for="name"><?php _e( 'Name', 'school-athletics' ); ?></th>
			<td>
				<p><input type="text" name="name" value="<?php echo $name; ?>"></p>
			</td>
		</tr>
		<tr>
			<th><label for="logo"><?php _e( 'Logo', 'school-athletics' ); ?></th>
			<td>
				<div class="photo <?php echo ($roster_thumbnail) ? 'yes' : 'no';?>">
					<div class="thumbnail">
						<?php echo ($organization_thumbnail) ? wp_get_attachment_image( $organization_thumbnail,'medium') : ''; ?>
					</div>
					<div class="thumbnail-placeholder">
						<span class="dashicons dashicons-format-image"></span>
					</div>
					<a href="#" class="add-photo"><?php _e( 'Add Logo', 'school-athletics' ); ?></a>
					<a href="#" class="remove-photo"><?php _e( 'Delete Logo', 'school-athletics' ); ?></a>
					<input class="photo-id" type="hidden" name="photo" size="4" value="<?php echo $roster_thumbnail; ?>" >
				</div>
			</td>
		</tr>
	</table>
	<p class="submit">
		<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save changes', 'school-athletics' ) ?>" />
	</p>
</form>

<?php \SchoolAthletics\Debug::file_path(SA__PLUGIN_DIR .'includes/admin/views/html-admin-page-settings.php'); ?>
<?php \SchoolAthletics\Debug::content($organization); ?>
<?php \SchoolAthletics\Debug::content($_REQUEST); ?>