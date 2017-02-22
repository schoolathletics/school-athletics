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
	$description = $organization->description;
	$term_id = $organization->term_id;
	$photo = get_term_meta($term_id, 'sa_mascot', true );
	$mascot = get_term_meta($term_id, 'sa_mascot', true );
	$website = get_term_meta($term_id, 'sa_website', true );
	$primary_color = get_term_meta($term_id, 'sa_primary_color', true );
	$secondary_color = get_term_meta($term_id, 'sa_secondary_color', true );
	$organization_thumbnail = false;
}else{
	$title = __( 'New Organization', 'school-athletics' );
	$organization = '';
	$description = '';
	$slug ='';
	$name = '';
	$organization_thumbnail = false;
	$photo = '';
	$mascot = '';
	$website = '';
	$primary_color = '';
	$secondary_color = '';
}
?>
<div class="wrap">
	<h1 ><?php _e( 'Your School', 'school-athletics' ); ?></h1>
	<form method="POST" action="options.php">
	<table class="wp-list-table widefat striped pages">
		<tr>
			<th><label for="name"><?php _e( 'Name', 'school-athletics' ); ?></th>
			<td>
				<p><input type="text" name="name"></p>
			</td>
		</tr>
		<tr>
			<th><label for="mascot"><?php _e( 'Mascot', 'school-athletics' ); ?></th>
			<td>
				<p><input type="text" name="mascot"></p>
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
		<tr>
			<th><label for="primary-color"><?php _e( 'Primary Color', 'school-athletics' ); ?></th>
			<td>
				<p><div class="swatch"></div><input class="iris" name="primary" type="text" ><label for="primary-color"></p>
			</td>
		</tr>
		<tr>
			<th><label for="secondary-color"><?php _e( 'Secondary Color', 'school-athletics' ); ?></th>
			<td>
				<p><div class="swatch"></div><input class="iris" name="secondary" type="text" ></p>
			</td>
		</tr>
		<tr>
			<th><label for="primary-color"><?php _e( 'Address', 'school-athletics' ); ?></th>
			<td>
				<p><input type="text" ></p>
			</td>
		</tr>
		<tr>
			<th><label for="primary-color"><?php _e( 'Website', 'school-athletics' ); ?></th>
			<td>
				<p><input type="text" ></p>
			</td>
		</tr>
	</table>
	<p class="submit">
		<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save changes', 'school-athletics' ) ?>" />
	</p>
	</form>

</div>

<?php \SchoolAthletics\Debug::file_path(SA__PLUGIN_DIR .'includes/admin/views/html-admin-page-settings.php'); ?>
<?php \SchoolAthletics\Debug::content($_REQUEST); ?>