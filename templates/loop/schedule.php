<?php
/**
 * Schedule Loop
 * 
 */

$schedule = new \SchoolAthletics\Schedule($post);
$events = $schedule->events;

?>
<h1>
	<?php the_title(); ?>
</h1>
<div class="schedule dropdown">
	<?php $schedule->dropdown(); ?>
</div>
<?php //the_content(); ?>

<table class="table table-striped table-responsive table-condensed schedule">
<thead>
	<tr>
		<th><?php _e( 'Date', 'school-athletics' ); ?></th>
		<th><?php _e( 'Time', 'school-athletics' ); ?></th>
		<th><?php _e( 'Name', 'school-athletics' ); ?></th>
		<th><?php _e( 'Location', 'school-athletics' ); ?></th>
		<th><?php _e( 'Type', 'school-athletics' ); ?></th>
		<th><?php _e( 'Outcome', 'school-athletics' ); ?></th>
		<th><?php _e( 'Result', 'school-athletics' ); ?></th>
	</tr>
</thead>
<tbody>
	<?php
	foreach ($events as $event) {
		$datetime = date_create($event['date']);
		$date = date_format($datetime,"F d, Y");
		$time = date_format($datetime,"h:i a");
	?>
	<tr class="<?php echo $event['game_type']; ?>">
		<td><?php echo $date; ?></td>
		<td><?php echo $time; ?></td>
		<td><?php echo $event['name']; ?></td>
		<td><?php echo $event['location']; ?></td>
		<td><?php echo $event['game_type']; ?></td>
		<td><?php echo $event['outcome']; ?></td>
		<td><?php echo $event['result']; ?></td>
	</tr>
	<?php
	}
	?>
</tbody>
</table>

<?php 
	\SchoolAthletics\Debug::content($schedule); 
?>