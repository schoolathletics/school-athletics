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
	$title = $season->name.' '.$sport->name.' '.__('Schedule','school-athletics');
	$events = get_posts(
		array(
			'post_type' => 'sa_event',
			'numberposts' => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'sa_sport',
					'field' => 'id',
					'terms' => $_GET['sport'], // Where term_id of Term 1 is "1".
				),
				array(
					'taxonomy' => 'sa_season',
					'field' => 'id',
					'terms' => $_GET['season'],
				)
			),
		)
	);

?>	
	<h1 class="wp-heading-inline"><?php echo $title ; ?></h1>
	<a class="page-title-action" href="">Add New</a>
	<p></p>
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
				<p>Options</p>
			</td>
		</tr>
	</tbody>
	</table>
	<p></p>
	<h2>Events</h2>
	<table class="wp-list-table widefat striped pages">
	<thead>
		<tr>
			<th>ID</th>
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
			foreach ($events as $event) {
				$location = get_the_terms($event,'sa_location');
				$location = (is_array($location)) ? array_pop($location) : null;
				$location = ($location) ? $location->name : '- - -';

				$game_type = get_the_terms($event,'sa_game_type');
				$game_type = (is_array($game_type)) ? array_pop($game_type) : null;
				$game_type = ($game_type) ? $game_type->name : '- - -';

				$outcome = get_the_terms($event,'sa_outcome');
				$outcome = (is_array($outcome)) ? array_pop($outcome) : null;
				$outcome = ($outcome) ? $outcome->name : '- - -';
			?>
			<tr>
				<td><?php echo $event->ID; ?></td>
				<td>Date</td>
				<td><?php echo $event->post_title; ?></td>
				<td><?php echo $location; ?></td>
				<td><?php echo $game_type; ?></td>
				<td><?php echo $outcome; ?></td>
				<td>Result</td>
				<td>
					<span class="dashicons dashicons-edit"></span> | 
					<span class="dashicons dashicons-dismiss"></span>
				</td>
			</tr>
			<?php
			}
		?>
		<tr class="ui-sortable-handle">
			<td></td>
			<td><input type="text" name="Date" value="" size="4"></td>
			<td><input type="text" name="name" value=""></td>
			<td>
				<?php wp_dropdown_categories(array(
						'name'             => 'sa_location',
						'show_option_none' => __( '- - -' ),
						'show_count'       => 0,
						'orderby'          => 'name',
						'option_none_value'=> '',
						'taxonomy'         => 'sa_location',
						'hide_empty'       => 0,
						'value_field'      => 'term_id',
						'selected'         => '',
						'echo'             => 1,
					)); 
				?>
			</td>
			<td>
				<?php wp_dropdown_categories(array(
						'name'             => 'sa_game_type',
						'show_option_none' => __( '- - -' ),
						'show_count'       => 0,
						'orderby'          => 'name',
						'option_none_value'=> '',
						'taxonomy'         => 'sa_game_type',
						'hide_empty'       => 0,
						'value_field'      => 'term_id',
						'selected'         => '',
						'echo'             => 1,
					)); 
				?>
			</td>
			<td>
				<?php wp_dropdown_categories(array(
						'name'             => 'sa_outcome',
						'show_option_none' => __( '- - -' ),
						'show_count'       => 0,
						'orderby'          => 'name',
						'order'            => 'DESC',
						'option_none_value'=> '',
						'taxonomy'         => 'sa_outcome',
						'hide_empty'       => 0,
						'value_field'      => 'term_id',
						'selected'         => '',
						'echo'             => 1,
					)); 
				?>
			</td>
			<td>
				<input type="text" name="result" value="" size="4">
			</td>
			<td>
				<span class="dashicons dashicons-plus-alt"></span>
			</td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<th>ID</th>
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
			<th>
				<label for="description">Export</label>
			</th>
			<td>
				<button class="button">Export</button>
			</td>
		</tr>
		<tr>
			<th><label for="csv">Import</label></th>
			<td>
				<textarea name="csv"></textarea>
				<p><span class="description"><?php _e( 'Upload a CSV file, but make sure it\'s properly formated', 'school-athletics' ); ?></span></p>
				<button class="button">Import</button>
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