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
	$schedule = new \SchoolAthletics\Schedule();
	//$schedule_thumbnail = get_post_thumbnail_id( $schedule->ID);
	$title = $schedule->title;
	$events = $schedule->get_events();

	if(!empty($import) && is_array($import)){
		if(!empty($import) && is_array($import)){
			$events = array_merge($events,$import);
		}
	}

?>	
	<h1 class="wp-heading-inline"><?php echo $title ; ?></h1>
	<a class="page-title-action" href="<?php echo admin_url('admin.php?page=schedule').'&sport='.$_GET['sport']; ?>">Add New</a>
	<p></p>
	<script>
		var autocomplete = [ <?php echo $autocomplete; ?> ];
	</script>
	<form method="POST">
	<table class="wp-list-table widefat">
	<thead>
		<tr>
			<th><?php _e('Settings', 'school-athletics');?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				<?php \SchoolAthletics\Debug::content($schedule->ID); ?>
				<p><code><?php echo get_permalink($schedule->ID); ?></code></p>
				<input type="hidden" name="ID" value="<?php echo $schedule->ID; ?>" />
			</td>
		</tr>
	</tbody>
	</table>
	<p><?php //wp_editor( $schedule->content, 'schedule_content', array('teeny'=>1,'media_buttons'=> 0) ); ?> </p>
	<h2>Events</h2>
	<table class="wp-list-table widefat striped pages">
	<thead>
		<tr>
			<th><?php _e( 'Date', 'school-athletics' ); ?></th>
			<th><?php _e( 'Name', 'school-athletics' ); ?></th>
			<th><?php _e( 'Location', 'school-athletics' ); ?></th>
			<th><?php _e( 'Type', 'school-athletics' ); ?></th>
			<th><?php _e( 'Outcome', 'school-athletics' ); ?></th>
			<th><?php _e( 'Result', 'school-athletics' ); ?></th>
			<th><?php _e( 'Actions', 'school-athletics' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		$id = 0;
		foreach ($events as $event) {
		?>
		<tr class="clonable">
			<td>
				<input class="object_id" type="hidden" name="event[<?php echo $id; ?>][ID]" value="<?php echo $event['ID']; ?>" >
				<input class="datetime" type="text" name="event[<?php echo $id; ?>][date]" value="<?php echo $event['date']; ?>" size="16">
			</td>
			<td>
				<input class="autocomplete" type="text" name="event[<?php echo $id; ?>][name]" value="<?php echo $event['name']; ?>" size="24">
			</td>
			<td>
				<?php wp_dropdown_categories(array(
						'name'             => 'event['.$id.'][location]',
						'show_option_none' => __( '- - -' ),
						'show_count'       => 0,
						'orderby'          => 'name',
						'option_none_value'=> '',
						'taxonomy'         => 'sa_location',
						'hide_empty'       => 0,
						'value_field'      => 'name',
						'selected'         => $event['location'],
						'echo'             => 1,
					)); 
				?>
			</td>
			<td>
				<?php wp_dropdown_categories(array(
						'name'             => 'event['.$id.'][game_type]',
						'show_option_none' => __( '- - -' ),
						'show_count'       => 0,
						'orderby'          => 'name',
						'option_none_value'=> '',
						'taxonomy'         => 'sa_game_type',
						'hide_empty'       => 0,
						'value_field'      => 'name',
						'selected'         => $event['game_type'],
						'echo'             => 1,
					)); 
				?>
			</td>
			<td>
				<?php wp_dropdown_categories(array(
						'name'             => 'event['.$id.'][outcome]',
						'show_option_none' => __( '- - -' ),
						'show_count'       => 0,
						'orderby'          => 'name',
						'option_none_value'=> '',
						'taxonomy'         => 'sa_outcome',
						'hide_empty'       => 0,
						'value_field'      => 'name',
						'selected'         => $event['outcome'],
						'echo'             => 1,
					)); 
				?>
			</td>
			<td>
				<input type="text" name="event[<?php echo $id; ?>][result]" value="<?php echo $event['result']; ?>" size="4">
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
			<th><?php _e( 'Date', 'school-athletics' ); ?></th>
			<th><?php _e( 'Name', 'school-athletics' ); ?></th>
			<th><?php _e( 'Location', 'school-athletics' ); ?></th>
			<th><?php _e( 'Type', 'school-athletics' ); ?></th>
			<th><?php _e( 'Outcome', 'school-athletics' ); ?></th>
			<th><?php _e( 'Result', 'school-athletics' ); ?></th>
			<th><?php _e( 'Actions', 'school-athletics' ); ?></th>
		</tr>
	</tfoot>
	</table>
	<div id="tobedeleted"></div>
	<?php wp_nonce_field( 'schoolathletics-save-schedule' ); ?>
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
				<p><span class="description"><?php _e( 'Paste CSV here, but make sure it\'s properly formated', 'school-athletics' ); ?></span></p>
				<button class="button">Import</button>
			</td>
		</tr>
		<tr>
			<th>
				<strong><label for="description">Export</label></strong>
			</th>
			<td>
				<pre class="export">date,name,location,game_type,outcome,result<br /><?php 
					foreach ($events as $event) {
						if( array_key_exists ( 'name', $event ) ){
							echo $event['date'].','.$event['name'].','.$event['location'].','.$event['game_type'].','.$event['outcome'].','.$event['result'].'<br />';
						}
					} ?></pre>
				<p><span class="description"><?php _e( 'You may want to export your schedule in order to import it into another program, or even another schedule. That\'s what this button does.', 'school-athletics' ); ?></span>
				</p>
			</td>
		</tr>
	</tbody>
	</table>
	</form>

</div>

<?php 

}else{
	echo '<h1 class="wp-heading-inline">'.__('Select a Schedule.','school-athletics').'</h1>';
	\SchoolAthletics\Admin\Page::wizard();

}

\SchoolAthletics\Debug::file_path('includes/admin/pages/views/html-admin-page-schedule.php');
\SchoolAthletics\Debug::content($schedule); 