<?php
/**
 * Admin View: Page - Status
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap">
	<h1 ><?php _e( 'Settings', 'school-athletics' ); ?></h1>
	<form method="POST" action="options.php">
	<?php settings_fields( 'schoolathletics_settings_fields' ); ?>
	<?php $options = wp_parse_args( get_option( 'schoolathletics_settings_options', array() ), array( 'uninstall_data' => 0, 'debug_mode' => 0) ); ?>
	<table class="wp-list-table widefat striped pages">
		<tr>
			<th><?php _e( 'Debug Mode', 'school-athletics' ); ?></th>
			<td>
				<p><label><input type="checkbox" class="checkbox" name="schoolathletics_settings_options[debug_mode]" value="1" <?php checked( '1', $options['debug_mode'] ); ?> /> <?php _e( 'Enabled', 'school-athletics' ); ?></label></p>
				<p><span class="description"><?php _e( 'This tool will put School Athletics into debug mode. Careful, it\'s easy to break things.', 'school-athletics' ); ?></span></p>
			</td>
		</tr>
		<tr>
			<th><?php _e( 'Remove All Data', 'school-athletics' ); ?></th>
			<td>
				<p><label><input type="checkbox" class="checkbox" name="schoolathletics_settings_options[uninstall_data]" value="1" <?php checked( '1', $options['uninstall_data'] ); ?> /> <?php _e( 'Enabled', 'school-athletics' ); ?></label></p>
				<p><span class="description"><?php _e( 'This tool will remove all School Athletics data when using the "Delete" link on the plugins screen.', 'school-athletics' ); ?></span></p>
			</td>
		</tr>
	</table>
	<p class="submit">
		<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save changes', 'school-athletics' ) ?>" />
	</p>
	</form>

</div>

<?php SchoolAthletics::debug_file_path(SA__PLUGIN_DIR .'includes/admin/views/html-admin-page-settings.php'); ?>
<?php SchoolAthletics::debug_content($_REQUEST); ?>