<?php //Short Codes
/*
ADD META
sa_sport_options
*/

$terms = get_terms( array(
    'taxonomy' => 'sa_sport',
    'hide_empty' => false,
) );
//print_r($terms);
if(isset($_GET['action'])){
	$action = $_GET['action'];
}else{
	$action = false;
}


if($action == 'publish'){
	$term_id = $_GET['sport'];
	update_term_meta($term_id, 'sa_sport_status', 'publish');
	sa_notice('Sport Activated.');
	activate_sa_sport($term_id);
}

if($action == 'unpublish'){
	$term_id = $_GET['sport'];
	update_term_meta($term_id, 'sa_sport_status', 'unpublish');
	sa_notice('Sport Deactivated.');
}



?>
<h1 class="wp-heading-inline"><?php _e('Sports', 'school-athletics') ; ?></h1>

<?php //if(\SchoolAthletics\Admin\Admin::advanced_mode()) : ?>
<a class="page-title-action" href="<?php echo admin_url('admin.php?page=sports'); ?>&action=edit">Add New</a>
<?php //endif; ?>

<p></p>
<table class="wp-list-table widefat striped pages">
<thead>
	<tr>
		<th><?php _e('Sport','school-athletics'); ?></th>
		<th class="border-left"><?php _e('Rosters','school-athletics'); ?></th>
		<th class="border-left"><?php _e('Schedules','school-athletics'); ?></th>
		<th class="border-left"><?php _e('Staff','school-athletics'); ?></th>
	</tr>
</thead>
<tbody id="the-list">
<?php


foreach ($terms as $term) {
	$options = \SchoolAthletics\Admin\Pages\Sports::options($term->term_id);
	$edit = admin_url('admin.php?page=sports').'&action=edit&sport='.$term->term_taxonomy_id;
	?>
	<tr>
		<td>
			<span class="row-title"><?php echo $term->name; ?></span><div class="row-actions"><span class="options"><a href="<?php echo get_permalink($options['page_id']); ?>" >View</a> | <a href="<?php echo $edit; ?>">Edit</a> | <a href="<?php echo $edit; ?>">Delete</a></span></div>
		</td>
	<?php if(!empty($options['roster']) && !empty($options['status'])) : ?>
		<td class="border-left">
			<?php echo \SchoolAthletics\Admin\Pages\Sports::get_current_roster($term); ?>
			<div class="row-actions">
			<span class="options">
				<a href="?page=roster&sport=<?php echo $term->term_id; ?>"><span class="dashicons dashicons-plus-alt"></span> Roster</a>
			</span>	
			</div>	
		</td>
	<?php else: ?>
		<td class="border-left">
			<span class="dashicons dashicons-lock"></span>
		</td>
	<?php endif; ?>
	<?php if(!empty($options['schedule']) && !empty($options['status'])) : ?>
		<td class="border-left">
			<?php echo \SchoolAthletics\Admin\Pages\Sports::get_current_schedule($term); ?>
			<div class="row-actions">
			<span class="options">
				<a href="?page=schedule&sport=<?php echo $term->term_id; ?>"><span class="dashicons dashicons-plus-alt"></span> Schedule</a>
			</span>	
			</div>		
		</td>
	<?php else: ?>
		<td class="border-left">
			<span class="dashicons dashicons-lock"></span>
		</td>
	<?php endif; ?>
	<?php if(!empty($options['staff']) && !empty($options['status'])) : ?>
		<td class="border-left">
			<?php echo \SchoolAthletics\Admin\Pages\Sports::get_current_staff($term); ?>
			<div class="row-actions">
			<span class="options">
				<a href="?page=staff&sport=<?php echo $term->term_id; ?>"><span class="dashicons dashicons-plus-alt"></span> Staff</a>
			</span>	
			</div>
		</td>
	<?php else: ?>
		<td class="border-left">
			<span class="dashicons dashicons-lock"></span>
		</td>
	<?php endif; ?>
	</tr>
	<?php
}
?>
</tbody>
</table>

<?php 
/**
 * Debug.
 */
\SchoolAthletics\Debug::file_path('includes/admin/pages/views/html-admin-page-sports-default.php'); 

?>