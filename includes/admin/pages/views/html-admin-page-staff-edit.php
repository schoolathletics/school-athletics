<?php
/**
 * Admin View: Page - Status
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if(is_object($staff)){
	$id = $staff->ID;
	$name = $staff->post_title;
	$content = $staff->post_content;
}else{
	$id = '';
	$name = '';
	$content = '';
}

?>
<h1 class="wp-heading-inline"><?php _e('Staff','school-athletics'); ?></h1>
<a class="page-title-action" href=""><?php _e('Add New', 'school-athletics');?></a>
<p></p>
<script>
	var autocomplete = [ <?php echo $autocomplete; ?> ];
</script>
<form method="POST">
<table class="wp-list-table widefat striped pages">
<tbody>
	<tr>
		<th>Debug</th>
		<td>
			<?php \SchoolAthletics\Debug::content('Staff ID = '.$id); ?>
			<p><code><?php echo get_permalink($id); ?></code></p>
			<input type="hidden" name="ID" value="<?php echo $staff->ID; ?>" />
		</td>
	</tr>
	<tr>
		<th>Photo</th>
		<td>
			<div class="photo <?php echo (get_post_thumbnail_id( $staff->ID )) ? 'yes' : 'no';?>">
				<div class="thumbnail">
					<?php echo ($id) ? wp_get_attachment_image( get_post_thumbnail_id( $id ),'medium') : ''; ?>
				</div>
				<div class="thumbnail-placeholder">
					<span class="dashicons dashicons-format-image"></span>
				</div>
				<a href="#" class="add-photo"><?php _e('Add Team Photo','school-athletics'); ?></a>
				<a href="#" class="remove-photo"><?php _e('Delete Team Photo','school-athletics'); ?></a>
				<input class="photo-id" type="hidden" name="photo" size="4" value="<?php echo get_post_thumbnail_id( $id ); ?>" >
			</div>
		</td>
	</tr>
	<tr>
		<th>Name</th>
		<td><input class="autocomplete" type="text" name="name" value="<?php echo $name; ?>"></td>
	</tr>
	<tr>
		<th>Job Title</th>
		<td><input type="text" name="job_title" value="<?php echo get_post_meta( $id, 'sa_job_title', true ) ?>"></td>
	</tr>
	<tr>
		<th>Sports</th>
		<td>
			<?php 
				$args = array(
					'selected_cats'         => true,
					'taxonomy'              => 'sa_sport',
					'checked_ontop'         => true
				);
				wp_terms_checklist( $id, $args );
			?>
		</td>
	</tr>
	<tr>
		<th>Bio</th>
		<td><?php wp_editor( $content, 'content', array('teeny'=>1,'media_buttons'=> 0) ); ?></td>
	</tr>
</tbody>
</table>
<?php wp_nonce_field( 'schoolathletics-edit-staff' ); ?>
<p class="submit"><input name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','school-athletics'); ?>" type="submit"></p>
</form>

<?php \SchoolAthletics\Debug::file_path('includes/admin/pages/views/html-admin-page-staff-edit.php'); ?>
<?php \SchoolAthletics\Debug::content($staff); ?>
<?php \SchoolAthletics\Debug::content($_POST); ?>