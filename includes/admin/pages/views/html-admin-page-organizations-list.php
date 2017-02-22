<?php
/**
 * Admin View: Page - Status
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$organizations = get_terms( array(
    'taxonomy' => 'sa_organization',
    'hide_empty' => false,
) );
?>

<h1 class="wp-heading-inline"><?php _e('Organizations', 'school-athletics') ; ?></h1>
<a class="page-title-action" href="<?php echo admin_url('admin.php?page=sa-organizations'); ?>&action=edit"><?php _e('Add New','school-athletics'); ?></a>
<p><?php _e('Organizations can be schools, groups, or even tournaments. They can link to events and show information about the Organization.','school-athletics');?></p>
<table class="wp-list-table widefat striped pages">
<thead>
	<tr>
		<th><?php _e('Logo','school-athletics'); ?></th>
		<th class="border-left"><?php _e('Name','school-athletics'); ?></th>
	</tr>
</thead>
<tbody id="the-list">
<?php
/*
 * Sport Rows
 */
foreach ($organizations as $organization) {
	//$options = \SchoolAthletics\Admin\Pages\Sports::options($organization->term_id);
	$thumbnail = false;
	$edit = admin_url('admin.php?page=sa-organizations').'&action=edit&organization='.$organization->term_taxonomy_id;
	$delete = wp_nonce_url(admin_url('admin.php?page=sa-organizations').'&action=delete&organization='.$organization->term_taxonomy_id, 'schoolathletics-delete-sa-organization');
	?>
	<tr>
		<td>
			<div class="thumbnail-placeholder">
				<?php echo ($thumbnail) ? wp_get_attachment_image( get_post_thumbnail_id( $post->ID ),'thumbnail') : '<span class="dashicons dashicons-format-image"></span>'; ?>
			</div>
		</td>
		<td>
			<span class="row-title"><?php echo $organization->name; ?></span>
			<div class="row-actions">
				<span class="options"><a href="<?php echo $edit; ?>"><?php _e('Edit', 'school-athletics') ; ?></a> | <a href="<?php echo $delete; ?>"><?php _e('Delete', 'school-athletics') ; ?></a></span>
			</div>
		</td>
	</tr>
<?php } ?>
</tbody>
</table>

<?php \SchoolAthletics\Debug::file_path(SA__PLUGIN_DIR .'includes/admin/views/html-admin-page-organizations-list.php'); ?>
<?php \SchoolAthletics\Debug::content($_REQUEST); ?>