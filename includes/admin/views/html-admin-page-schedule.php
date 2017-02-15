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
	$sport = get_term_by( 'id', $_GET['sport'], 'sa_sport' );
	$season = get_term_by( 'id', $_GET['season'], 'sa_season' );
	$content = (!empty($content)) ? $content : '';

	$schedule = \SchoolAthletics\Admin\ScheduleAdmin::getSchedule($sport,$season);
	$schedule_content = '';

	if(isset($schedule[0]->ID)){
		$schedule_id = $schedule[0]->ID;
		$schedule_thumbnail = get_post_thumbnail_id( $schedule[0]->ID );
		$schedule_content = $schedule[0]->post_content;
	}

	$title = $season->name.' '.$sport->name.' '.__('Schedule','school-athletics');
	$events = \SchoolAthletics\Admin\ScheduleAdmin::getEvents($sport,$season);

?>	
	<h1 class="wp-heading-inline"><?php echo $title ; ?></h1>
	<a class="page-title-action" href="">Add New</a>
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
				<?php \SchoolAthletics\Debug::content( (isset($schdeule_id)) ? 'Schedule ID = '.$schedule_id : 'Unset'); ?>
				<p><code><?php echo (isset($schdeule_id)) ? get_permalink($schedule_id) : 'No ID'; ?></code></p>
				<input type="hidden" name="ID" value="<?php echo $roster_id; ?>" />
			</td>
		</tr>
	</tbody>
	</table>
	<p><?php wp_editor( $schedule_content, 'schedule_content', array('teeny'=>1,'media_buttons'=> 0) ); ?> </p>
	<h2>Events</h2>
	<table class="wp-list-table widefat striped pages">
	<thead>
		<tr>
			<th>Date</th>
			<th>Name</th>
			<th>Location</th>
			<th>Type</th>
			<th>Outcome</th>
			<th>Result</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody class="ui-sortable">
		<?php
		$id = 0;
		foreach ($events as $event) {
		?>
		<tr class="clonable">
			<td><input type="text" name="event[<?php echo $id; ?>][date]" value="<?php echo $event['date']; ?>" size="6"></td>
			<td>
				<input type="text" name="event[<?php echo $id; ?>][name]" value="<?php echo $event['name']; ?>" >
				<input class="member_id hidden" type="text" name="event[<?php echo $id; ?>][ID]" value="<?php echo $event['ID']; ?>" >
				<div class="row-actions">
					<span class="options"><a href="<?php echo get_permalink($event['ID']);?>" >View</a> | </span> <a href="<?php echo admin_url('admin.php?page=schedule').'&action=edit&event='.$event['ID']; ?>">Edit</a></span>
				</div>
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
			<th>Date</th>
			<th>Name</th>
			<th>Location</th>
			<th>Type</th>
			<th>Outcome</th>
			<th>Result</th>
			<th>Actions</th>
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
			<th><label for="csv">Import</label></th>
			<td>
				<input type="hidden" name="action" value="import">
				<textarea name="csv" class="textarea widefat"></textarea>
				<p><span class="description"><?php _e( 'Paste CSV here, but make sure it\'s properly formated', 'school-athletics' ); ?></span>
				</p>
				<p>
				<pre>date,name,location,game_type,outcome,result<br />date,name,location,game_type,outcome,result</pre>
				</p>
				<button class="button">Import</button>
			</td>
		</tr>
		<tr>
			<th>
				<label for="description">Export</label>
			</th>
			<td>
				<pre>date,name,location,game_type,outcome,result<br /><?php 
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

\SchoolAthletics\Debug::file_path(SA__PLUGIN_DIR .'includes/admin/views/html-admin-page-schedule.php');
\SchoolAthletics\Debug::content($_REQUEST); 