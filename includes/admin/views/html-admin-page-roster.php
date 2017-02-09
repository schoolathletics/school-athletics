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
	$title = $season->name.' '.$sport->name.' '.__('Roster','school-athletics');

?>	
	<h1><?php echo $title ; ?></h1>
	<form method="POST">
	<table class="form-table">
	<tr>
		<th><label for="csv">Import</label></th>
		<td>File</td>
	</tr>
	</table>
	<table class="wp-list-table widefat striped pages">
	<thead>
		<tr>
			<th>ID</th>
			<th>Photo</th>
			<th>No.</th>
			<th>Name</th>
			<th>Ht.</th>
			<th>Wt.</th>
			<th>Year</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody class="ui-sortable">
		<tr class="ui-sortable-handle">
			<td></td>
			<td></td>
			<td></td>
			<td><input type="text" name="name" value="" ></td>
			<td></td>
			<td></td>
			<td></td>
			<td><span class="dashicons dashicons-plus-alt"></span> <span class="dashicons dashicons-dismiss"></span></td>
		</tr>
	</tbody>
	</table>
	<p class="submit"><input name="submit" id="submit" class="button button-primary" value="Save Changes" type="submit"></p>
	</form>

</div>

<?php 

}

SchoolAthletics::debug_file_path(SA__PLUGIN_DIR .'includes/admin/views/html-admin-page-roster.php');
SchoolAthletics::debug_content($_REQUEST); 