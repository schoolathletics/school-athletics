<?php
/**
 * Roster Template
 * 
 */
$roster = new \SchoolAthletics\Roster($post);
$athletes = $roster->get_athletes();

?>
<h1>
	<?php echo $roster->title; ?>
</h1>
<div class="roster dropdown">
	<?php $roster->dropdown(); ?>
</div>
<div class="team">
	<?php echo ($roster->thumbnail) ? wp_get_attachment_image( $roster->thumbnail,'full') : '' ; ?>
</div>
<div class="content">
	<?php echo $roster->content; ?>
</div>
<table class="table table-striped table-responsive table-condensed roster">
<thead>
	<tr>
		<th><?php _e( 'No.', 'school-athletics' ); ?></th>
		<th><?php _e( 'Name', 'school-athletics' ); ?></th>
		<th><?php _e( 'Ht.', 'school-athletics' ); ?></th>
		<th><?php _e( 'Wt.', 'school-athletics' ); ?></th>
		<th><?php _e( 'Year', 'school-athletics' ); ?></th>
	</tr>
</thead>
<tbody>
	<?php
	foreach ($athletes as $athlete) {
	?>
	<tr>
		<td><?php echo $athlete['jersey']; ?></td>
		<td><?php echo $athlete['name']; ?></td>
		<td><?php echo $athlete['height']; ?></td>
		<td><?php echo $athlete['weight']; ?></td>
		<td><?php echo $athlete['status']; ?></td>
	</tr>
	<?php
	}
	?>
</tbody>
</table>

<?php 
	\SchoolAthletics\Debug::content($roster); 
?>
