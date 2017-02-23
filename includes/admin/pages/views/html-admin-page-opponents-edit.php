<?php
/**
 * Admin View: Page - Status
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if(isset($_REQUEST['opponent'])){
	$opponent = get_term( $_REQUEST['opponent']);
	$title = __( 'Edit', 'school-athletics' ).' '.$opponent->name;
	$name = $opponent->name;
	$slug = $opponent->slug;
	$term_id = $opponent->term_id;
	$sa_opponent_options = get_term_meta($term_id, 'sa_opponent_options', true );
}else{
	$title = __( 'New Opponent', 'school-athletics' );
	$opponent = '';
	$slug ='';
	$name = '';
	$sa_opponent_options = array();
	$sa_opponent_options['logo'] = false;
}
?>
<h1 ><?php echo $title; ?></h1>
<p></p>
<form method="POST">
	<table class="wp-list-table widefat striped pages">
		<tr>
			<th><label for="name"><?php _e( 'Name', 'school-athletics' ); ?></th>
			<td>
				<p><input type="text" name="name" value="<?php echo $name; ?>"></p>
			</td>
		</tr>
		<tr>
			<th><label for="sa_opponent_options[logo]"><?php _e( 'Logo', 'school-athletics' ); ?></th>
			<td>
				<div class="photo <?php echo ($sa_opponent_options['logo']) ? 'yes' : 'no';?>">
					<div class="thumbnail">
						<?php echo (isset($sa_opponent_options['logo'])) ? wp_get_attachment_image( $sa_opponent_options['logo'],'medium') : ''; ?>
					</div>
					<div class="thumbnail-placeholder">
						<span class="dashicons dashicons-format-image"></span>
					</div>
					<a href="#" class="add-photo"><?php _e( 'Add Logo', 'school-athletics' ); ?></a>
					<a href="#" class="remove-photo"><?php _e( 'Delete Logo', 'school-athletics' ); ?></a>
					<input class="photo-id" type="hidden" name="sa_opponent_options[logo]" size="4" value="<?php echo $sa_opponent_options['logo']; ?>" >
				</div>
			</td>
		</tr>
	</table>
	<?php wp_nonce_field( 'schoolathletics-save-opponent' ); ?>
	<p class="submit">
		<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save changes', 'school-athletics' ) ?>" />
	</p>
</form>

<?php \SchoolAthletics\Debug::file_path(SA__PLUGIN_DIR .'includes/admin/views/html-admin-page-settings.php'); ?>
<?php \SchoolAthletics\Debug::content($opponent); ?>
<?php \SchoolAthletics\Debug::content($_REQUEST); ?>