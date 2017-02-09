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
	<table class="form-table">
	<tr>
		<th><label for="name">Sport</label></th>
		<td><input class="regular-text" type="text" readonly="readonly" name="name" value="<?php echo $term->name; ?>" ></td>
	</tr>
	<tr>
		<th><label for="slug">Slug</label></th>
		<td><input class="regular-text"  type="text" readonly="readonly" name="slug" value="<?php echo $term->slug; ?>" ></td>
	</tr>
	<tr>
		<th><label>URL</label></th>
		<td><code><?php echo get_permalink(get_term_meta( $term->term_id, 'sa_sport_home_id', true )); ?></code></td>
	</tr>
	<tr>
		<th><label>Pages</label></th>
		<td>
			<input class="regular-text code" type="text" readonly="readonly" name="page_id" value="<?php echo get_term_meta( $term->term_id, 'sa_sport_home_id', true ); ?>" > <small>Home ID</small> <br />
			<input class="regular-text code"  type="text" readonly="readonly" name="roster_id" value="<?php echo get_term_meta( $term->term_id, 'sa_sport_roster_id', true ); ?>" > <small>Roster ID</small> <br />
			<input class="regular-text code"  type="text" readonly="readonly" name="schedule_id" value="<?php echo get_term_meta( $term->term_id, 'sa_sport_schedule_id', true ); ?>" > <small>Schedule ID</small> <br />
		</td>
	</tr>
	</table>
	<p class="submit"><input name="submit" id="submit" class="button button-primary" value="Save Changes" type="submit"></p>
</form>

<?php SchoolAthletics::debug_file_path(SA__PLUGIN_DIR .'includes/admin/views/html-admin-sports-edit.php'); ?>