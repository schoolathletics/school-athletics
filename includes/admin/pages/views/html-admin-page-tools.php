<?php
/**
 * Admin View: Page - Status
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap">
	<h1 ><?php _e( 'Tools', 'school-athletics' ); ?></h1>
	<table class="wp-list-table fixed widefat striped pages">
	<tbody>
	<tr>
		<th><label><?php _e('Rebuild Sport Pages', 'school-athletics'); ?></label></th>
		<td>
			<form method="POST">
			<input type="hidden" name="task" value="rebuild">
			<?php wp_nonce_field( 'schoolathletics-tools-rebuild' ); ?>
			<input name="submit" id="submit" class="button" value="Rebuild" type="submit">
			</form>
			<p><span class="description"><?php _e( 'If things are looking funny. Try rebuilding the pages.', 'school-athletics' ); ?></span></p>
		</td>
	</tr>
	</tbody>
	</table>

</div>

<?php \SchoolAthletics\Debug::file_path('includes/admin/pages/views/html-admin-page-tools.php'); ?>
<?php \SchoolAthletics\Debug::content($_REQUEST); ?>