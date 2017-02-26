<?php
/**
 * Admin View: Page - Status
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
	<h1 ><?php _e( 'Your School', 'school-athletics' ); ?></h1>
	<form method="POST" action="options.php">
	<?php settings_fields( 'schoolathletics_your_school_fields' ); 	?>
	<?php $options = wp_parse_args( get_option( 'schoolathletics_your_school_options', array() ), array( 'name' => '', 'mascot' => '', 'logo' => '', 'primary_color' => '', 'secondary_color' => '' ) ); ?>

	<table class="wp-list-table widefat striped pages">
		<tr>
			<th><label for="schoolathletics_your_school_options[name]"><?php _e( 'Name', 'school-athletics' ); ?></th>
			<td>
				<p><input type="text" name="schoolathletics_your_school_options[name]" value="<?php echo $options['name']; ?>"></p>
			</td>
		</tr>
		<tr>
			<th><label for="schoolathletics_your_school_options[mascot]"><?php _e( 'Mascot', 'school-athletics' ); ?></th>
			<td>
				<p><input type="text" name="schoolathletics_your_school_options[mascot]" value="<?php echo $options['mascot']; ?>"></p>
			</td>
		</tr>
		<tr>
			<th><label for="schoolathletics_your_school_options[logo]"><?php _e( 'Logo', 'school-athletics' ); ?></th>
			<td>
				<div class="photo <?php echo ($options['logo']) ? 'yes' : 'no';?>">
					<div class="thumbnail">
						<?php echo ($options['logo']) ? wp_get_attachment_image( $options['logo'],'medium') : ''; ?>
					</div>
					<div class="thumbnail-placeholder">
						<span class="dashicons dashicons-format-image"></span>
					</div>
					<a href="#" class="add-photo"><?php _e( 'Add Logo', 'school-athletics' ); ?></a>
					<a href="#" class="remove-photo"><?php _e( 'Delete Logo', 'school-athletics' ); ?></a>
					<input class="photo-id" type="hidden" name="schoolathletics_your_school_options[logo]" size="4" value="<?php echo $options['logo']; ?>" >
				</div>
			</td>
		</tr>
		<tr>
			<th><label for="schoolathletics_your_school_options[primary_color]"><?php _e( 'Primary Color', 'school-athletics' ); ?></label></th>
			<td>
				<div class="swatch" style="background-color:<?php echo $options['primary_color']; ?>; "></div>
				<input class="iris" name="schoolathletics_your_school_options[primary_color]" type="text" value="<?php echo $options['primary_color']; ?>" >

		</tr>
		<tr>
			<th><label for="schoolathletics_your_school_options[secondary_color]"><?php _e( 'Secondary Color', 'school-athletics' ); ?></th>
			<td>
				<div class="swatch" style="background-color:<?php echo $options['secondary_color']; ?>; "></div>
				<input class="iris" name="schoolathletics_your_school_options[secondary_color]" type="text" value="<?php echo $options['secondary_color']; ?>">
			</td>
		</tr>
	</table>
	<p class="submit">
		<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save changes', 'school-athletics' ) ?>" />
	</p>
	</form>

</div>

<?php \SchoolAthletics\Debug::file_path('includes/admin/views/html-admin-page-your-school.php'); ?>
<?php \SchoolAthletics\Debug::content($_REQUEST); ?>