<?php
/**
 * Admin View: Page - Status
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
<?php 
if(!empty($_GET['sport']) && !empty($_GET['season'])){

	$roster = new \SchoolAthletics\Roster();
	$roster_thumbnail = $roster->thumbnail;

	$title = $roster->title;

	$athletes = $roster->get_athletes();

	if(!empty($import) && is_array($import)){
		if(!empty($import) && is_array($import)){
			$athletes = array_merge($athletes,$import);
		}
	}

?>	

	<h1 class="wp-heading-inline"><?php echo $title ; ?></h1>
	<a class="page-title-action" href=""><?php _e('Add New','school-athletics'); ?></a>
	<p></p>
	<script>
		var autocomplete = [ <?php echo $autocomplete; ?> ];
	</script>
	<form method="POST">
	<table class="wp-list-table widefat striped">
	<thead>
		<tr>
			<th><strong><?php _e('Settings', 'school-athletics');?></strong></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				<?php \SchoolAthletics\Debug::content('Roster ID = '.$roster->ID); ?>
				<p><code><?php echo get_permalink($roster->ID); ?></code></p>
				<input type="hidden" name="ID" value="<?php echo $roster->ID; ?>" />
			</td>
		</tr>
		<tr>
			<td>
				<div class="photo <?php echo ($roster_thumbnail) ? 'yes' : 'no';?>">
					<div class="thumbnail">
						<?php echo ($roster_thumbnail) ? wp_get_attachment_image( $roster_thumbnail,'medium') : ''; ?>
					</div>
					<div class="thumbnail-placeholder">
						<span class="dashicons dashicons-format-image"></span>
					</div>
					<a href="#" class="add-photo">Add Team Photo</a>
					<a href="#" class="remove-photo">Delete Team Photo</a>
					<input class="photo-id" type="hidden" name="photo" size="4" value="<?php echo $roster_thumbnail; ?>" >
				</div>
			</td>
		</tr>
	</tbody>
	</table>
	<p><?php wp_editor( $roster->content, 'roster_content', array('teeny'=>1,'media_buttons'=> 0) ); ?> </p>
	<h2>Athletes</h2>
	<table class="wp-list-table widefat striped pages">
	<thead>
		<tr>
			<th></th>
			<th><span class="dashicons dashicons-format-image"></span></th>
			<th><?php _e( 'No.', 'school-athletics' ); ?></th>
			<th><?php _e( 'Name', 'school-athletics' ); ?></th>
			<th><?php _e( 'Ht.', 'school-athletics' ); ?></th>
			<th><?php _e( 'Wt.', 'school-athletics' ); ?></th>
			<th><?php _e( 'Year', 'school-athletics' ); ?></th>
			<th></th>
		</tr>
	</thead>
	<tbody id="sortable" class="ui-sortable">
		<?php
		$id = 0;
		foreach ($athletes as $athlete) {
		?>
		<tr class="clonable">
			<td class="ui-sortable-handle handle">
				<span class="dashicons dashicons-move"></span>
				<input type="hidden" class="order" name="athlete[<?php echo $id; ?>][order]" value="<?php echo $athlete['order']; ?>" >
				<input class="object_id" type="hidden" name="athlete[<?php echo $id; ?>][ID]" value="<?php echo $athlete['ID']; ?>" >
			</td>
			<td>
				<div class="photo <?php echo ($athlete['photo']) ? 'yes' : 'no';?>">
					<div class="sa thumbnail">
						<?php echo ($athlete['photo']) ? wp_get_attachment_image( $athlete['photo'],'thumbnail') : ''; ?>
					</div>
					<div class="thumbnail-placeholder">
						<span class="dashicons dashicons-format-image"></span>
					</div>
					<a href="#" class="add-photo">Add Photo</a>
					<a href="#" class="remove-photo">Delete Photo</a>
					<input class="photo-id" type="hidden" name="athlete[<?php echo $id; ?>][photo]" size="4" value="<?php echo $athlete['photo']; ?>" >
				</div>
			</td>
			<td><input type="text" name="athlete[<?php echo $id; ?>][jersey]" value="<?php echo $athlete['jersey']; ?>" size="2"></td>
			<td>
				<input class="autocomplete" type="text" name="athlete[<?php echo $id; ?>][name]" value="<?php echo $athlete['name']; ?>" >
				<div class="row-actions">
					<span class="options"><a href="<?php echo get_permalink($athlete['ID']);?>" >View</a> | </span> <a href="<?php echo admin_url('admin.php?page=roster').'&action=edit&athlete='.$athlete['ID']; ?>">Edit</a></span>
				</div>
			</td>
			<td>
				<input type="text" name="athlete[<?php echo $id; ?>][height]" value="<?php echo $athlete['height']; ?>" size="4">
			</td>
			<td>
				<input type="text" name="athlete[<?php echo $id; ?>][weight]" value="<?php echo $athlete['weight']; ?>" size="4">
			</td>
			<td>
				<?php wp_dropdown_categories(array(
						'name'             => 'athlete['.$id.'][status]',
						'show_option_none' => __( '- - -' ),
						'show_count'       => 0,
						'orderby'          => 'name',
						'option_none_value'=> '',
						'taxonomy'         => 'sa_athlete_status',
						'hide_empty'       => 0,
						'value_field'      => 'name',
						'selected'         => $athlete['status'],
						'echo'             => 1,
					)); 
				?>
			</td>
			<td>
				<a class="add_row"><span class="dashicons dashicons-plus-alt"></span></a> <a class="delete_row"><span class="dashicons dashicons-dismiss"></span></a>
			</td>
		</tr>
		<?php
			$id++;
		}
		?>
	</tbody>
	<tfoot>
		<tr>
			<th></th>
			<th><span class="dashicons dashicons-format-image"></span></th>
			<th><?php _e( 'No.', 'school-athletics' ); ?></th>
			<th><?php _e( 'Name', 'school-athletics' ); ?></th>
			<th><?php _e( 'Ht.', 'school-athletics' ); ?></th>
			<th><?php _e( 'Wt.', 'school-athletics' ); ?></th>
			<th><?php _e( 'Year', 'school-athletics' ); ?></th>
			<th></th>
		</tr>
	</tfoot>
	</table>
	<div id="tobedeleted"></div>
	<?php wp_nonce_field( 'schoolathletics-save-roster' ); ?>
	<p class="submit"><input name="submit" id="submit" class="button button-primary" value="Save Changes" type="submit"></p>
	</form>

	<p></p>

	<form method="POST">
	<table class="wp-list-table widefat striped pages">
	<thead>
		<tr>
			<th colspan="2"><?php _e('Import/Export', 'school-athletics');?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th><strong><label for="csv">Import</label></strong></th>
			<td>
				<input type="hidden" name="action" value="import">
				<textarea name="csv" class="textarea widefat"></textarea>
				<p><span class="description"><?php _e( 'Paste CSV here, but make sure it\'s properly formated', 'school-athletics' ); ?></span>
				</p>
				<p>
				<pre>jersey,name,height,weight,status<br />1,John Doe,5-11,145,Freshman<br />2,Jane Doe,5-4,123,Freshman</pre>
				</p>
				<button class="button">Import</button>
			</td>
		</tr>
		<tr>
			<th>
				<strong><label for="description">Export</label></strong>
			</th>
			<td>
				<pre class="export">jersey,name,height,weight,status<br /><?php 
					foreach ($athletes as $athlete) {
						if(array_key_exists ( 'name', $athlete )){
							echo $athlete['jersey'].','.$athlete['name'].','.$athlete['height'].','.$athlete['weight'].','.$athlete['status'].'<br />';
						}
					} ?></pre>
				<p><span class="description"><?php _e( 'You may want to export your roster in order to import it into another program, or even another roster. That\'s what this button does.', 'school-athletics' ); ?></span>
				</p>
			</td>
		</tr>
	</tbody>
	</table>
	</form>

</div>

<?php 
\SchoolAthletics\Debug::content($roster);

}else{
	echo '<h1 class="wp-heading-inline">'.__('Select a Roster.','school-athletics').'</h1>';
	\SchoolAthletics\Admin\Page::wizard();

}

\SchoolAthletics\Debug::file_path('includes/admin/pages/views/html-admin-page-roster.php');
\SchoolAthletics\Debug::content($_REQUEST); 
