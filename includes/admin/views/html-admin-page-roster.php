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
	$title = $season->name.' '.$sport->name.' '.__('Roster','school-athletics');
	$athletes = get_posts(
		array(
			'post_type' => 'sa_person',
			'numberposts' => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'sa_sport',
					'field' => 'id',
					'terms' => $_GET['sport'], // Where term_id of Term 1 is "1".
				),
				array(
					'taxonomy' => 'sa_person_type',
					'field' => 'name',
					'terms' => 'athlete', // Where term_id of Term 1 is "1".
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
<script type="text/javascript">
	
var media_uploader = null;

function open_media_uploader_image()
{
    media_uploader = wp.media({
        frame:    "post",
        state:    "insert",
        multiple: false
    });

    media_uploader.on("insert", function(){
        var json = media_uploader.state().get("selection").first().toJSON();

        var image_url = json.url;
        var image_caption = json.caption;
        var image_title = json.title;
    });

    media_uploader.open();
}

</script>

	<h1 class="wp-heading-inline"><?php echo $title ; ?></h1>
	<a class="page-title-action" href="">Add New</a>
	<form method="POST">
	<table class="wp-list-table widefat">
	<thead>
		<tr>
			<th><strong><?php _e('Settings', 'school-athletics');?></strong></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				<a onclick="open_media_uploader_image()">Add Team Photo</a>
			</td>
		</tr>
		<tr>
			<td>
				<?php //wp_editor( $content, 'description', array('teeny'=>1,'media_buttons'=> 0) ); ?> 
			</td>
		</tr>
	</tbody>
	</table>
	<p></p>
	<h2>Athletes</h2>
	<table class="wp-list-table widefat striped pages">
	<thead>
		<tr>
			<th></th>
			<th>ID</th>
			<th><span class="dashicons dashicons-format-image"></span></th>
			<th>No.</th>
			<th>Name</th>
			<th>Ht.</th>
			<th>Wt.</th>
			<th>Year</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody class="ui-sortable">
		<?php 
			foreach ($athletes as $athlete) {
				$status = get_the_terms($athlete,'sa_athlete_status');
				$status = (is_array($status)) ? array_pop($status) : null;
				$status = ($status) ? $status->name : '- - -';
			?>
			<tr>
				<td class="hndle"></td>
				<td><?php echo $athlete->ID; ?></td>
				<td></td>
				<td></td>
				<td><?php echo $athlete->post_title; ?></td>
				<td></td>
				<td></td>
				<td><?php echo $status; ?></td>
				<td>
					<span class="dashicons dashicons-edit"></span> | 
					<span class="dashicons dashicons-dismiss"></span>
				</td>
			</tr>
			<?php
			}
		?>
		<tr class="ui-sortable-handle">
			<td class="hndle"></td>
			<td></td>
			<td><a href="#">Add Photo</a></td>
			<td><input type="text" name="jersey" value="" size="4"></td>
			<td><input type="text" name="name" value="" ></td>
			<td>
				<input type="text" name="height" value="" size="4">
			</td>
			<td>
				<input type="text" name="weight" value="" size="4">
			</td>
			<td>
				<?php wp_dropdown_categories(array(
						'name'             => 'sa_athlete_status',
						'show_option_none' => __( '- - -' ),
						'show_count'       => 0,
						'orderby'          => 'name',
						'option_none_value'=> '',
						'taxonomy'         => 'sa_athlete_status',
						'hide_empty'       => 0,
						'value_field'      => 'term_id',
						'selected'         => '',
						'echo'             => 1,
					)); 
				?>
			</td>
			<td>
				<span class="dashicons dashicons-plus-alt"></span>
			</td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<th></th>
			<th>ID</th>
			<th><span class="dashicons dashicons-format-image"></span></th>
			<th>No.</th>
			<th>Name</th>
			<th>Ht.</th>
			<th>Wt.</th>
			<th>Year</th>
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

	\SchoolAthletics\Admin\Sports::wizard();

}

\SchoolAthletics\Debug::file_path(SA__PLUGIN_DIR .'includes/admin/views/html-admin-page-roster.php');
\SchoolAthletics\Debug::content($_REQUEST); 