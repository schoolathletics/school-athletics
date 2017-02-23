<?php
/**
 * Admin View: Page - Status
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$opponents = get_terms( array(
    'taxonomy' => 'sa_opponent',
    'hide_empty' => false,
) );
?>

<h1 class="wp-heading-inline"><?php _e('Opponents', 'school-athletics') ; ?></h1>
<a class="page-title-action" href="<?php echo admin_url('admin.php?page=sa-opponents'); ?>&action=edit"><?php _e('Add New','school-athletics'); ?></a>
<p><?php _e('Opponents can be schools, groups, or even tournaments. They can link to events and show information about the opponent.','school-athletics');?></p>
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
foreach ($opponents as $opponent) {
	//$options = \SchoolAthletics\Admin\Pages\Sports::options($opponent->term_id);
	$sa_opponent_options = get_term_meta($opponent->term_taxonomy_id, 'sa_opponent_options', true );
	$edit = admin_url('admin.php?page=sa-opponents').'&action=edit&opponent='.$opponent->term_taxonomy_id;
	$delete = wp_nonce_url(admin_url('admin.php?page=sa-opponents').'&action=delete&opponent='.$opponent->term_taxonomy_id, 'schoolathletics-delete-opponent');
	?>
	<tr>
		<td>
			<div class="thumbnail-placeholder">
				<?php echo (isset($sa_opponent_options['logo'])) ? wp_get_attachment_image( $sa_opponent_options['logo'],'thumbnail') : '<span class="dashicons dashicons-format-image"></span>'; ?>
			</div>
		</td>
		<td>
			<span class="row-title"><?php echo $opponent->name; ?></span>
			<div class="row-actions">
				<span class="options"><a href="<?php echo $edit; ?>"><?php _e('Edit', 'school-athletics') ; ?></a> | <a href="<?php echo $delete; ?>"><?php _e('Delete', 'school-athletics') ; ?></a></span>
			</div>
		</td>
	</tr>
<?php } ?>
</tbody>
</table>

<?php \SchoolAthletics\Debug::file_path(SA__PLUGIN_DIR .'includes/admin/views/html-admin-page-opponents-list.php'); ?>
<?php \SchoolAthletics\Debug::content($_REQUEST); ?>