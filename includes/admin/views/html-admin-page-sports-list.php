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
<h1>Sports</h1>

<table class="wp-list-table widefat striped pages">
<thead>
	<tr>
		<th colspan="2"><?php _e('Sport','school-athletics'); ?></th>
		<th class="border-left"><?php _e('Rosters','school-athletics'); ?></th>
		<th class="border-left"><?php _e('Schedules','school-athletics'); ?></th>
		<th class="border-left"><?php _e('Staff','school-athletics'); ?></th>
	</tr>
</thead>
<tbody id="the-list">
<?php


foreach ($terms as $term) {
	$options = \SchoolAthletics\Admin\SportsAdmin::options($term->term_id);
	$edit = admin_url('admin.php?page=sports').'&action=edit&sport='.$term->term_taxonomy_id;
	?>
	<tr>
		<td>
	<?php if(empty($options['status'])) : ?>
			<a id="publish" class="status unpublish" href="<?php echo wp_nonce_url(admin_url('admin.php?page=sports').'&task=publish&sport='.$term->term_taxonomy_id, 'schoolathletics-publish-sport');?>"></a>
	<?php else: ?>
			<a id="unpublish" class="status publish" href="<?php echo wp_nonce_url(admin_url('admin.php?page=sports').'&task=unpublish&sport='.$term->term_taxonomy_id, 'schoolathletics-unpublish-sport');?>"></a>
	<?php endif; ?>
		</td>
		<td>
			<span class="row-title"><?php echo $term->name; ?></span><div class="row-actions"><span class="options"><a href="<?php echo get_permalink($options['page_id']); ?>" >View</a> | </span> <a href="<?php echo $edit; ?>">Options</a></div>
		</td>
	<?php if(!empty($options['roster']) && !empty($options['status'])) : ?>
		<td class="border-left">
			<?php echo \SchoolAthletics\Admin\SportsAdmin::get_current_roster($term); ?>
			<div class="row-actions">
			<span class="options">
				<a href="?page=roster&sport=<?php echo $term->term_id; ?>"> New Roster</a>
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
			<?php echo \SchoolAthletics\Admin\SportsAdmin::get_current_schedule($term); ?>
			<div class="row-actions">
			<span class="options">
				<a href="?page=schedule&sport=<?php echo $term->term_id; ?>">New Schedule</a>
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
			<?php echo \SchoolAthletics\Admin\SportsAdmin::get_current_staff($term); ?>
			<div class="row-actions">
			<span class="options">
				<a href="?page=staff&sport=<?php echo $term->term_id; ?>">Add Staff</a>
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
\SchoolAthletics\Debug::file_path(SA__PLUGIN_DIR .'includes/admin/views/html-admin-page-sports-default.php'); 

?>