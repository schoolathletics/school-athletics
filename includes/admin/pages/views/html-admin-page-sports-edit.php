<?php //Short Codes
if(isset($_GET['sport'])){
	$term_id = $_GET['sport'];
	$term = get_term($term_id);
	$options = \SchoolAthletics\Admin\Pages\Sports::options($term->term_id);
}else{
	$term = '';
	$options = \SchoolAthletics\Admin\Pages\Sports::options('');
}

if(is_object($term)){
	$title = $term->name;
	$term_name = $term->name;
	$term_slug = $term->slug;
	$permalink = get_permalink($options['page_id']);
}else{
	$title = __('Add New Sport','school-athletics');
	$term_name = '';
	$term_slug = '';
	$permalink = '';
}
//print_r($term);
?>
<h1><?php echo $title; ?></h1>
<form method="POST">

	<table class="wp-list-table widefat fixed striped pages">
	<thead>
	<tr>
		<th colspan="2">
			<strong><?php _e('Settings', 'school-athletics'); ?></strong>
		</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<th><label for="name"><?php _e('Sport', 'school-athletics'); ?></label></th>
		<td><input class="regular-text" type="text" name="name" value="<?php echo $term_name; ?>" ></td>
	</tr>
	<tr>
		<th><label for="slug"><?php _e('Slug', 'school-athletics'); ?></label></th>
		<td><input class="regular-text"  type="text" name="slug" value="<?php echo $term_slug; ?>" ></td>
	</tr>
	</tbody>
	</table>

	<p></p>

	<table class="wp-list-table fixed widefat striped pages">
	<thead>
	<tr>
		<th colspan="2">
			<strong><?php _e('Roster Options','school-athletics'); ?></strong>
		</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<th><label for="name"><?php _e('Has Roster','school-athletics'); ?></label></th>
		<td>
			<p><label><input type="checkbox" class="checkbox" name="sa_sport_options[roster]" value="1" <?php checked( '1', $options['roster'] ); ?> /> <?php _e( 'Enabled', 'school-athletics' ); ?></label></p>
			<p><span class="description"><?php _e( 'This add a roster page to the sport.', 'school-athletics' ); ?></span></p>
		</td>
	</tr>
	<tr>
		<th><label for="options"><?php _e('Options', 'school-athletics'); ?></label></th>
		<td>
			<ul>
				<li><input type="checkbox" class="checkbox" name="sa_sport_options[roster_has_number]" value="1" <?php checked( '1', $options['roster_has_number'] ); ?> /> <?php _e( 'Number', 'school-athletics' ); ?></li>
				<li><input type="checkbox" class="checkbox" name="sa_sport_options[roster_has_position]" value="1" <?php checked( '1', $options['roster_has_position'] ); ?> /> <?php _e( 'Position', 'school-athletics' ); ?></li>
				<li><input type="checkbox" class="checkbox" name="sa_sport_options[roster_has_height]" value="1" <?php checked( '1', $options['roster_has_height'] ); ?> /> <?php _e( 'Height', 'school-athletics' ); ?></li>
				<li><input type="checkbox" class="checkbox" name="sa_sport_options[roster_has_weight]" value="1" <?php checked( '1', $options['roster_has_weight'] ); ?> /> <?php _e( 'Weight', 'school-athletics' ); ?></li>
				<li><input type="checkbox" class="checkbox" name="sa_sport_options[roster_has_year]" value="1" <?php checked( '1', $options['roster_has_year'] ); ?> /> <?php _e( 'Year', 'school-athletics' ); ?></li>
			</ul>
		</td>
	</tr>
	</tbody>
	</table>

	<p></p>

	<table class="wp-list-table fixed widefat striped pages">
	<thead>
	<tr>
		<th colspan="2">
			<strong><?php _e('Schedule Options', 'school-athletics'); ?></strong>
		</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<th><label for="name"><?php _e('Has Schedule', 'school-athletics'); ?></label></th>
		<td>
			<p><label><input type="checkbox" class="checkbox" name="sa_sport_options[schedule]" value="1" <?php checked( '1', $options['schedule'] ); ?> /> <?php _e( 'Enabled', 'school-athletics' ); ?></label></p>
			<p><span class="description"><?php _e( 'This add a roster page to the sport.', 'school-athletics' ); ?></span></p>
		</td>
	</tr>
	<tr>
		<th><label for="options"><?php _e('Options', 'school-athletics'); ?></label></th>
		<td>
			<ul>
				<li><input type="checkbox" class="checkbox" name="sa_sport_options[schedule_has_opponents]" value="1" <?php checked( '1', $options['schedule_has_opponents'] ); ?> /> <?php _e( 'Has Oponents', 'school-athletics' ); ?></li>
				<li><input type="checkbox" class="checkbox" name="sa_sport_options[schedule_has_game_types]" value="1" <?php checked( '1', $options['schedule_has_game_types'] ); ?> /> <?php _e( 'Has Conference', 'school-athletics' ); ?></li>
				<li><input type="checkbox" class="checkbox" name="sa_sport_options[schedule_keep_record]" value="1" <?php checked( '1', $options['schedule_keep_record'] ); ?> /> <?php _e( 'Keep Record', 'school-athletics' ); ?></li>
			</ul>
		</td>
	</tr>
	</tbody>
	</table>

	<p></p>

	<table class="wp-list-table fixed widefat striped pages">
	<thead>
	<tr>
		<th colspan="2">
			<strong><?php _e('Staff Options', 'school-athletics'); ?></strong>
		</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<th><label for="name"><?php _e('Has Staff', 'school-athletics'); ?></label></th>
		<td>
			<p><label><input type="checkbox" class="checkbox" name="sa_sport_options[staff]" value="1" <?php checked( '1', $options['staff'] ); ?> /> <?php _e( 'Enabled', 'school-athletics' ); ?></label></p>
			<p><span class="description"><?php _e( 'This add a roster page to the sport.', 'school-athletics' ); ?></span></p>
		</td>
	</tr>
	</tbody>
	</table>

	<?php wp_nonce_field( 'schoolathletics-edit-sport' ); ?>
	<p class="submit"><input name="submit" id="submit" class="button button-primary" value="Save Changes" type="submit"></p>

</form>

	<p></p>

<?php if(\SchoolAthletics\Debug::status() && isset($_GET['sport'])) : ?>
<?php $sport = $_GET['sport']; ?>

	<table class="wp-list-table fixed widefat striped pages">
	<thead>
	<tr>
		<th colspan="2">
			<strong><?php _e('Debug Pages', 'school-athletics'); ?></strong>
		</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<th><label><?php _e('Sport', 'school-athletics'); ?></label></th>
		<td><p><?php _e('Page ID:','school-athletics'); ?> <?php echo \SchoolAthletics\Sport::get_sport_page_id($sport,'home'); ?></p>
			<code><?php echo get_permalink(\SchoolAthletics\Sport::get_sport_page_id($sport,'home')); ?></code>
		</td>
	</tr>
	<tr>
		<th><label><?php _e('Roster', 'school-athletics'); ?></label></th>
		<td>
			<p><?php _e('Page ID:','school-athletics'); ?> <?php echo \SchoolAthletics\Sport::get_sport_page_id($sport,'roster'); ?></p>
			<code><?php echo get_permalink(\SchoolAthletics\Sport::get_sport_page_id($sport,'roster')); ?></code>
		</td>
	</tr>
	<tr>
		<th><label><?php _e('Schedule', 'school-athletics'); ?></label></th>
		<td>
			<p><?php _e('Page ID:','school-athletics'); ?> <?php echo \SchoolAthletics\Sport::get_sport_page_id($sport,'schedule'); ?></p>
			<code><?php echo get_permalink(\SchoolAthletics\Sport::get_sport_page_id($sport,'schedule')); ?></code>
		</td>
	</tr>
	<tr>
		<th><label><?php _e('Staff', 'school-athletics'); ?></label></th>
		<td>
			<p><?php _e('Page ID:','school-athletics'); ?> <?php echo \SchoolAthletics\Sport::get_sport_page_id($sport,'staff'); ?></p>
			<code><?php echo get_permalink(\SchoolAthletics\Sport::get_sport_page_id($sport,'staff')); ?></code>
		</td>
	</tr>
	</tbody>
	</table>
<?php endif; ?>
<?php \SchoolAthletics\Debug::file_path('includes/admin/pages/views/html-admin-sports-edit.php'); ?>
<?php \SchoolAthletics\Debug::content($options);  ?>
