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
	$_athletes = get_posts(
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
	$athletes = array();
	foreach ($_athletes as $athlete) {
		$status = get_the_terms($athlete,'sa_athlete_status');
		$status = (is_array($status)) ? array_pop($status) : null;
		$status = ($status) ? $status->name : '- - -';
		$athletes[] = array(
				'ID'	 => $athlete->ID,
				'jersey' => '',
				'name'   => $athlete->post_title,
				'height' => '',
				'weight' => '',
				'status' => $status,
			);
	}

	//Defaults for athletes
	$defaults = array(
				'ID'     => '',
				'jersey' => '',
				'name'   => '',
				'height' => '',
				'weight' => '',
				'status' => '',
			);
	if(!empty($import) && is_array($import)){
		foreach ($import as $key => $value) {
			//$import[$key]['ID'] = '';
			$import[$key] = wp_parse_args($import[$key],$defaults);
		}
		$athletes = array_merge($import,$athletes);
	}
	//Adds a new row to the bottom
	$athletes[] = $defaults;

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

/*
 * Clone TR if + clicked 
 */
jQuery("a.add_new_row").live('click', function() {
    var $tr    = jQuery(this).closest('.clonable');
    var $clone = $tr.clone();
    $clone.find(':text').val('');
    $tr.after($clone);
    console.log('clone');
});

</script>
	<h1 class="wp-heading-inline"><?php echo $title ; ?></h1>
	<a class="page-title-action" href="">Add New</a>
	<p></p>
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
		$id = 0;
		foreach ($athletes as $athlete) {
		?>
		<tr class="ui-sortable-handle">
			<td class="hndle"></td>
			<td><input type="text" name="athlete[<?php echo $id; ?>][ID]" value="<?php echo $athlete['ID']; ?>" size="4"></td>
			<td><a href="#">Add Photo</a></td>
			<td><input type="text" name="athlete[<?php echo $id; ?>][jersey]" value="<?php echo $athlete['jersey']; ?>" size="4"></td>
			<td><input type="text" name="athlete[<?php echo $id; ?>][name]" value="<?php echo $athlete['name']; ?>" ></td>
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
				<span class="dashicons dashicons-no"></span>
			</td>
		</tr>
		<?php
			$id++;
		}
		?>
		<!--<tr class="ui-sortable-handle clonable">
			<td class="hndle"></td>
			<td></td>
			<td><a href="#">Add Photo</a></td>
			<td><input type="text" name="athlete[][jersey]" value="" size="4"></td>
			<td><input type="text" name="athlete[][name]" value="" ></td>
			<td>
				<input type="text" name="athlete[][height]" value="" size="4">
			</td>
			<td>
				<input type="text" name="athlete[][weight]" value="" size="4">
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
				<a class="add_new_row"><span class="dashicons dashicons-plus-alt"></span></a>
			</td>
		</tr>-->
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
				<pre>jersey,name,height,weight,status<br /><?php 
					foreach ($athletes as $athlete) {
						if($athlete['name']){
							echo $athlete['jersey'].','.$athlete['name'].','.$athlete['height'].','.$athlete['weight'].','.$athlete['status'].'<br />';
						}
					} ?></pre>
				<p><span class="description"><?php _e( 'You may want to export your roster in order to import it into another program, or even another roster. That\'s what this button does.', 'school-athletics' ); ?></span>
				</p>
			</td>
		</tr>
		<tr>
			<th><label for="csv">Import</label></th>
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
	</tbody>
	</table>
	</form>

</div>

<?php 

}else{
	echo '<h1 class="wp-heading-inline">'.__('Select a Roster.','school-athletics').'</h1>';
	\SchoolAthletics\Admin\Page::wizard();

}

\SchoolAthletics\Debug::file_path(SA__PLUGIN_DIR .'includes/admin/views/html-admin-page-roster.php');
\SchoolAthletics\Debug::content($_REQUEST); 