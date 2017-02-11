<?php //Short Codes

$term_id = $_GET['sport'];
$term = get_term($term_id);
$options = SA_Admin_Sports::options($term->term_id);
//print_r($term);
?>
<h1><?php echo $term->name; ?></h1>
<form method="POST">

	<table class="wp-list-table widefat fixed striped pages">
	<thead>
	<tr>
		<th colspan="2">
			<strong>Settings</strong>
		</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<th><label for="name">Sport</label></th>
		<td><input class="regular-text" type="text" name="name" value="<?php echo $term->name; ?>" ></td>
	</tr>
	<tr>
		<th><label for="slug">Slug</label></th>
		<td><input class="regular-text"  type="text" name="slug" value="<?php echo $term->slug; ?>" ></td>
	</tr>
	<tr>
		<th><label>Debug</label></th>
		<td><p>Page ID: <input type="text" readonly="readonly" name="sa_sport_options[page_id]" value="<?php echo $options['page_id']; ?>"></p>
			<code><?php echo get_permalink($options['page_id']); ?></code>
		</td>
	</tr>
	<tr>
		<th><label>Status</label></th>
		<td>
			<p><label><input type="checkbox" class="checkbox" name="sa_sport_options[status]" value="1" <?php checked( '1', $options['status'] ); ?> /> <?php _e( 'Publish', 'school-athletics' ); ?></label></p>
			<p><span class="description"><?php _e( 'Publishing a sport.', 'school-athletics' ); ?></span></p>
		</td>
	</tr>
	</tbody>
	</table>

	<p></p>

	<table class="wp-list-table fixed widefat striped pages">
	<thead>
	<tr>
		<th colspan="2">
			<strong>Roster Options</strong>
		</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<th><label>Debug</label></th>
		<td>
			<p>Page ID : <input type="text" readonly="readonly" name="sa_sport_options[roster_id]" value="<?php echo $options['roster_id']; ?>"></p>
			<code><?php echo get_permalink($options['roster_id']); ?></code>
		</td>
	</tr>
	<tr>
		<th><label for="name">Has Roster</label></th>
		<td>
			<p><label><input type="checkbox" class="checkbox" name="sa_sport_options[roster]" value="1" <?php checked( '1', $options['roster'] ); ?> /> <?php _e( 'Enabled', 'school-athletics' ); ?></label></p>
			<p><span class="description"><?php _e( 'This add a roster page to the sport.', 'school-athletics' ); ?></span></p>
		</td>
	</tr>
	<tr>
		<th><label for="options">Options</label></th>
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
			<strong>Schedule Options</strong>
		</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<th><label>Debug</label></th>
		<td>
			<p>Page ID : <input type="text" readonly="readonly" name="sa_sport_options[schedule_id]" value="<?php echo $options['schedule_id']; ?>"></p>
			<code><?php echo get_permalink($options['schedule_id']); ?></code>
		</td>
	</tr>
	<tr>
		<th><label for="name">Has Schedule</label></th>
		<td>
			<p><label><input type="checkbox" class="checkbox" name="sa_sport_options[schedule]" value="1" <?php checked( '1', $options['schedule'] ); ?> /> <?php _e( 'Enabled', 'school-athletics' ); ?></label></p>
			<p><span class="description"><?php _e( 'This add a roster page to the sport.', 'school-athletics' ); ?></span></p>
		</td>
	</tr>
	<tr>
		<th><label for="options">Options</label></th>
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
			<strong>Staff Options</strong>
		</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<th><label>Debug</label></th>
		<td>
			<p>Page ID : <input type="text" readonly="readonly" name="sa_sport_options[staff_id]" value="<?php echo $options['staff_id']; ?>"></p>
			<code><?php echo get_permalink($options['staff_id']); ?></code>
		</td>
	</tr>
	<tr>
		<th><label for="name">Has Staff</label></th>
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


	<table class="wp-list-table fixed widefat striped pages">
	<thead>
	<tr>
		<th colspan="2">
			<strong>Tools</strong>
		</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<th><label>Rebuild Sport Pages</label></th>
		<td>
			<form method="POST">
			<input type="hidden" name="task" value="rebuild">
			<?php wp_nonce_field( 'schoolathletics-rebuild-sport-pages' ); ?>
			<input name="submit" id="submit" class="button" value="Rebuild" type="submit">
			</form>
			<p><span class="description"><?php _e( 'If things are looking funny. Try rebuilding the pages.', 'school-athletics' ); ?></span></p>
		</td>
	</tr>
	</tbody>
	</table>

<?php SchoolAthletics::debug_file_path(SA__PLUGIN_DIR .'includes/admin/views/html-admin-sports-edit.php'); ?>
<?php SchoolAthletics::debug_content($options);  ?>
