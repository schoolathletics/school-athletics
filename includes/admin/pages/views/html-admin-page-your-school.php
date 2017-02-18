<?php
/**
 * Admin View: Page - Status
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
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
				<p><input type="text" ></p>
				<p><span class="description"><?php _e( 'Upload your school logo.', 'school-athletics' ); ?></span></p>
			</td>
		</tr>
		<tr>
			<th><label for="primary-color"><?php _e( 'School Colors', 'school-athletics' ); ?></th>
			<td>
				<p><input type="text" ><label for="primary-color"><?php _e( 'Primary Color', 'school-athletics' ); ?></p>
				<p><input type="text" ></p>
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