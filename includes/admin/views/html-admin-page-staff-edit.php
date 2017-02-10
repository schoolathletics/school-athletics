<?php
/**
 * Admin View: Page - Status
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<a class="page-title-action" href="">Add New</a>
<table class="wp-list-table widefat striped pages">
<thead>
	<tr>
		<th width="20px"></th>
		<th>Photo</th>
		<th>Name</th>
		<th>Job Title</th>
		<th>Sports</th>
	</tr>
</thead>
<tbody>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
</tbody>
</table>

<?php SchoolAthletics::debug_file_path(SA__PLUGIN_DIR .'includes/admin/views/html-admin-page-staff-edit.php'); ?>