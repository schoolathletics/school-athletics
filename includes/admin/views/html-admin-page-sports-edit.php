<?php //Short Codes

$term_id = $_GET['sport'];
$term = get_term($term_id);
//print_r($term);
if (isset($_POST['submit'])){
	//sa_notice('Sport Updated.');
	//activate_sa_sport($term);
}
?>

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
		<td><p>Page ID: <?php echo get_term_meta( $term->term_id, 'sa_sport_home_id', true ); ?></p>
			<code><?php echo get_permalink(get_term_meta( $term->term_id, 'sa_sport_home_id', true )); ?></code>
		</td>
	</tr>
	<tr>
		<th><label>Status</label></th>
		<td><?php echo get_term_meta( $term->term_id, 'sa_sport_status', true ); ?></td>
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
			<p>Page ID : <?php echo get_term_meta( $term->term_id, 'sa_sport_roster_id', true ); ?></p>
			<code><?php echo get_permalink(get_term_meta( $term->term_id, 'sa_sport_roster_id', true )); ?></code>
		</td>
	</tr>
	<tr>
		<th><label for="name">Has Roster</label></th>
		<td>
			<p><label><input type="checkbox" class="checkbox" name="enable_roster" value="1" <?php //checked( '1', $options['debug_mode'] ); ?> /> <?php _e( 'Enabled', 'school-athletics' ); ?></label></p>
			<p><span class="description"><?php _e( 'This add a roster page to the sport.', 'school-athletics' ); ?></span></p>
		</td>
	</tr>
	<tr>
		<th><label for="options">Options</label></th>
		<td>
			<ul>
				<li><input type="checkbox" class="checkbox" name="enable_roster" value="1" <?php //checked( '1', $options['debug_mode'] ); ?> /> <?php _e( 'Number', 'school-athletics' ); ?></li>
				<li><input type="checkbox" class="checkbox" name="enable_roster" value="1" <?php //checked( '1', $options['debug_mode'] ); ?> /> <?php _e( 'Position', 'school-athletics' ); ?></li>
				<li><input type="checkbox" class="checkbox" name="enable_roster" value="1" <?php //checked( '1', $options['debug_mode'] ); ?> /> <?php _e( 'Height', 'school-athletics' ); ?></li>
				<li><input type="checkbox" class="checkbox" name="enable_roster" value="1" <?php //checked( '1', $options['debug_mode'] ); ?> /> <?php _e( 'Weight', 'school-athletics' ); ?></li>
				<li><input type="checkbox" class="checkbox" name="enable_roster" value="1" <?php //checked( '1', $options['debug_mode'] ); ?> /> <?php _e( 'Year', 'school-athletics' ); ?></li>
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
			<p>Page ID : <?php echo get_term_meta( $term->term_id, 'sa_sport_schedule_id', true ); ?></p>
			<code><?php echo get_permalink(get_term_meta( $term->term_id, 'sa_sport_schedule_id', true )); ?></code>
		</td>
	</tr>
	<tr>
		<th><label for="name">Has Schedule</label></th>
		<td>
			<p><label><input type="checkbox" class="checkbox" name="enable_schedule" value="1" <?php //checked( '1', $options['debug_mode'] ); ?> /> <?php _e( 'Enabled', 'school-athletics' ); ?></label></p>
			<p><span class="description"><?php _e( 'This add a roster page to the sport.', 'school-athletics' ); ?></span></p>
		</td>
	</tr>
	<tr>
		<th><label for="options">Options</label></th>
		<td>
			<ul>
				<li><input type="checkbox" class="checkbox" name="enable_roster" value="1" <?php //checked( '1', $options['debug_mode'] ); ?> /> <?php _e( 'Has Oponents', 'school-athletics' ); ?></li>
				<li><input type="checkbox" class="checkbox" name="enable_roster" value="1" <?php //checked( '1', $options['debug_mode'] ); ?> /> <?php _e( 'Has Conference', 'school-athletics' ); ?></li>
				<li><input type="checkbox" class="checkbox" name="enable_roster" value="1" <?php //checked( '1', $options['debug_mode'] ); ?> /> <?php _e( 'Keep Record', 'school-athletics' ); ?></li>
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
			<p>Page ID : <?php echo get_term_meta( $term->term_id, 'sa_sport_staff_id', true ); ?></p>
			<code><?php echo get_permalink(get_term_meta( $term->term_id, 'sa_sport_staff_id', true )); ?></code>
		</td>
	</tr>
	<tr>
		<th><label for="name">Has Staff</label></th>
		<td>
			<p><label><input type="checkbox" class="checkbox" name="enable_schedule" value="1" <?php //checked( '1', $options['debug_mode'] ); ?> /> <?php _e( 'Enabled', 'school-athletics' ); ?></label></p>
			<p><span class="description"><?php _e( 'This add a roster page to the sport.', 'school-athletics' ); ?></span></p>
		</td>
	</tr>
	</tbody>
	</table>

	<p class="submit"><input name="submit" id="submit" class="button button-primary" value="Save Changes" type="submit"></p>
	<?php wp_nonce_field( 'schoolathletics-edit-sport' ); ?>

</form>

<?php SchoolAthletics::debug_file_path(SA__PLUGIN_DIR .'includes/admin/views/html-admin-sports-edit.php'); ?>