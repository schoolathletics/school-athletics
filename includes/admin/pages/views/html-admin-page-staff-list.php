<?php
/**
 * Admin View: Page - Status
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<h1 class="wp-heading-inline"><?php _e('Staff','school-athletics'); ?></h1>
<a class="page-title-action" href="<?php echo admin_url('admin.php?page=staff').'&action=edit'; ?>"><?php _e('Add New'); ?></a>
<p></p>
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
<tbody id="sortable" class="ui-sortable">
<?php
$args = array(
	'posts_per_page'   => -1,
	'category'         => '',
	'category_name'    => '',
	'orderby'          => 'date',
	'order'            => 'DESC',
	'post_type'        => 'sa_staff',
	'post_status'      => 'publish',
	'suppress_filters' => true 
);
$posts = get_posts( $args ); 

foreach ($posts as $post) {
?>
	<tr>
		<td class="ui-sortable-handle handle">
			<span class="dashicons dashicons-move"></span>
			<input type="hidden" class="order" name="staff[order]" value="<?php //echo $staff['order']; ?>" >
		</td>
		<td>
			<div class="thumbnail-placeholder">
				<?php echo (get_post_thumbnail_id( $post->ID )) ? wp_get_attachment_image( get_post_thumbnail_id( $post->ID ),'thumbnail') : '<span class="dashicons dashicons-format-image"></span>'; ?>
			</div>
		</td>
		<td>
			<span class="row-title"><a href="<?php echo admin_url('admin.php?page=staff').'&action=edit&staff='.$post->ID; ?>"><?php echo $post->post_title; ?></a></span>
			<div class="row-actions"><span class="options"><a href="<?php echo get_permalink($post->ID);?>" >View</a> | </span> <a href="<?php echo admin_url('admin.php?page=staff').'&action=edit&staff='.$post->ID; ?>">Edit</a> | </span> <a href="<?php echo admin_url('admin.php?page=staff').'&action=edit&staff='.$post->ID; ?>">Delete</a></div>
		</td>
		<td><?php echo get_post_meta( $post->ID, 'sa_job_title', true ) ?></td>
		<td><?php echo get_the_term_list( $post->ID, 'sa_sport', '', ','); ?> </td>
	</tr>
<?php
}
?>
</tbody>
</table>

<?php \SchoolAthletics\Debug::file_path('includes/admin/pages/views/html-admin-page-staff-list.php'); ?>