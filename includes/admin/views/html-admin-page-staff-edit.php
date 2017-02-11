<?php
/**
 * Admin View: Page - Status
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<h1 class="wp-heading-inline"><?php _e('Staff','school-athletics'); ?></h1>
<a class="page-title-action" href="">Add New</a>

<form method="POST">
<table class="wp-list-table widefat striped pages">
<thead>
	<tr>
		<th colspan="2">
			<strong>Settings</strong>
		</th>
	</tr>
</thead>
<tbody>
	<tr>
		<th>Name</th>
		<td></td>
	</tr>
	<tr>
		<th>Photo</th>
		<td></td>
	</tr>
	<tr>
		<th>Job Title</th>
		<td></td>
	</tr>
	<tr>
		<th>Sports</th>
		<td></td>
	</tr>
	<tr>
		<th>Bio</th>
		<td></td>
	</tr>
</tbody>
</table>
<?php wp_nonce_field( 'schoolathletics-edit-sport' ); ?>
<p class="submit"><input name="submit" id="submit" class="button button-primary" value="Save Changes" type="submit"></p>
</form>

<?php SchoolAthletics::debug_file_path(SA__PLUGIN_DIR .'includes/admin/views/html-admin-page-staff-edit.php'); ?>