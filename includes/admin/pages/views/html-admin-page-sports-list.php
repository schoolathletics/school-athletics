<?php 

$sports = get_terms( array(
    'taxonomy' => 'sa_sport',
    'hide_empty' => false,
) );

?>
<h1 class="wp-heading-inline"><?php _e('Sports', 'school-athletics') ; ?></h1>
<a class="page-title-action" href="<?php echo admin_url('admin.php?page=sports'); ?>&action=edit"><?php _e('Add New','school-athletics'); ?></a>
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
/*
 * Sport Rows
 */
foreach ($sports as $sport) {
	$options = \SchoolAthletics\Admin\Pages\Sports::options($sport->term_id);
	$edit = admin_url('admin.php?page=sports').'&action=edit&sport='.$sport->term_taxonomy_id;
	$delete = wp_nonce_url(admin_url('admin.php?page=sports').'&action=delete&sport='.$sport->term_taxonomy_id, 'schoolathletics-delete-sport');
	?>
	<?php 
		/*
		 * Sport Column
		 */
	?>
	<tr>
		<td>
			<span class="row-title"><?php echo $sport->name; ?></span>
			<div class="row-actions">
				<span class="options"><a href="<?php echo get_permalink($options['page_id']); ?>" ><?php _e('View', 'school-athletics') ; ?></a> | <a href="<?php echo $edit; ?>"><?php _e('Edit', 'school-athletics') ; ?></a> | <a href="<?php echo $delete; ?>"><?php _e('Delete', 'school-athletics') ; ?></a></span>
			</div>
		</td>
	<?php 
		/*
		 * Roster Column
		 */
	?>
	<?php if(!empty($options['roster'])) : ?>
		<td class="border-left">
			<?php echo \SchoolAthletics\Admin\Pages\Sports::get_current_roster($sport); ?>
			<div class="row-actions">
			<span class="options">
				<a href="?page=roster&sport=<?php echo $sport->term_id; ?>"><span class="dashicons dashicons-plus-alt"></span> <?php _e('Roster', 'school-athletics') ; ?></a>
			</span>	
			</div>	
		</td>
	<?php else: ?>
		<td class="border-left">
			<span class="dashicons dashicons-lock"></span>
		</td>
	<?php endif; ?>
	<?php 
		/*
		 * Schedule Column
		 */
	?>
	<?php if(!empty($options['schedule'])) : ?>
		<td class="border-left">
			<?php echo \SchoolAthletics\Admin\Pages\Sports::get_current_schedule($sport); ?>
			<div class="row-actions">
			<span class="options">
				<a href="?page=schedule&sport=<?php echo $sport->term_id; ?>"><span class="dashicons dashicons-plus-alt"></span> <?php _e('Schedule', 'school-athletics') ; ?></a>
			</span>	
			</div>		
		</td>
	<?php else: ?>
		<td class="border-left">
			<span class="dashicons dashicons-lock"></span>
		</td>
	<?php endif; ?>
	<?php 
		/*
		 * Staff Column
		 */
	?>
	<?php if(!empty($options['staff'])) : ?>
		<td class="border-left">
			<?php echo \SchoolAthletics\Admin\Pages\Sports::get_current_staff($sport); ?>
			<div class="row-actions">
			<span class="options">
				<a href="?page=staff&sport=<?php echo $sport->term_id; ?>"><span class="dashicons dashicons-plus-alt"></span> <?php _e('Staff', 'school-athletics') ; ?></a>
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