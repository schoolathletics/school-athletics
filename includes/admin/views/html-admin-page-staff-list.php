<?php
/**
 * Admin View: Page - Status
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<a class="page-title-action" href="">Add New</a>
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
<tbody id="the-list">
<?php
$args = array(
	'posts_per_page'   => 0,
	'offset'           => 0,
	'category'         => '',
	'category_name'    => '',
	'orderby'          => 'date',
	'order'            => 'DESC',
	'tax_query' => array(
			            array(
			                'taxonomy' => 'sa_person_type',
			                'field' => 'name',
			                'terms' => 'staff',
			            )
			        ),
	'post_type'        => 'sa_person',
	'post_status'      => 'publish',
	'suppress_filters' => true 
);
$posts = get_posts( $args ); 

foreach ($posts as $post) {
?>
	<tr>
		<td></td>
		<td></td>
		<td>
			<span class="row-title"><a href="<?php echo admin_url('admin.php?page=staff').'&action=edit&staff='.$post->ID; ?>"><?php echo $post->post_title; ?></a></span>
			<div class="row-actions"><span class="options"><a href="<?php echo get_permalink($post->ID);?>" >View</a> | </span> <a href="<?php echo admin_url('admin.php?page=staff').'&action=edit&staff='.$post->ID; ?>">Edit</a> | </span> <a href="<?php echo admin_url('admin.php?page=staff').'&action=edit&staff='.$post->ID; ?>">Delete</a></div>
		</td>
		<td></td>
		<td><?php echo get_the_term_list( $post->ID, 'sa_sport'); ?> </td>
	</tr>
<?php
}
?>
</tbody>
</table>

<?php SchoolAthletics::debug_file_path(SA__PLUGIN_DIR .'includes/admin/views/html-admin-page-staff-list.php'); ?>