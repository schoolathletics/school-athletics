<?php
/**
 * Admin View: Page - Status
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php 

if(empty($_GET['sport']) && !empty($_GET['season'])){
	\SchoolAthletics\Admin\Notice::error(__('Please select a sport.'));
}
if(!empty($_GET['sport']) && empty($_GET['season'])){
	\SchoolAthletics\Admin\Notice::error(__('Please select a season.'));
}
$sport = (!empty($_GET['sport'])) ? $_GET['sport'] : '';
$season = (!empty($_GET['season'])) ? $_GET['season'] : '';
$title = $_GET['page'];
?>	
<p><?php _e('Please select a sport and season.', 'school-athletics'); ?></p>
<form method="GET">
	<input type="hidden" name="page" value="<?php echo $title; ?>">
	<table class="wp-list-table widefat striped pages">
	<tr>
		<th ><label for="sport"><?php _e('Sport','school-athletics'); ?></label></th>
		<td>
			<?php wp_dropdown_categories(array(
						'name'             => 'sport',
						'show_option_none' => __( 'Select Sport' ),
						'show_count'       => 0,
						'orderby'          => 'name',
						'option_none_value'=> '',
						'taxonomy'         => 'sa_sport',
						'hide_empty'       => 0,
						'value_field'      => 'term_id',
						'selected'         => $sport,
						'echo'             => 1,
					)); 
			?>
		</td>
	</tr>
	<tr>
		<th><label for="description"><?php _e('Season','school-athletics'); ?></label></th>
		<td>
			<?php wp_dropdown_categories(array(
						'name'             => 'season',
						'show_option_none' => __( 'Select Season' ),
						'show_count'       => 0,
						'orderby'          => 'name',
						'option_none_value'=> '',
						'order'            => 'DESC',
						'taxonomy'         => 'sa_season',
						'hide_empty'       => 0,
						'value_field'      => 'term_id',
						'selected'         => $season,
						'echo'             => 1,
					)); 
			?>
		</td>
	</tr>
	</table>
	<p class="submit"><input id="submit" class="button button-primary" value="Get <?php echo ucwords($title); ?>" type="submit"></p>
</form>

<?php \SchoolAthletics\Debug::file_path(SA__PLUGIN_DIR .'includes/admin/views/html-admin-page-sports-wizard.php'); ?>