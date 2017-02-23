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

<?php the_content(); ?>

<table>
<thead>
	<tr>
		<th></th>
	</tr>
</thead>
<tbody>
	<?php
	foreach ($events as $event) {
	?>
	<tr>
		<td><?php echo $event->post_title; ?></td>
	</tr>
	<?php
	}
	?>
</tbody>
<tfoot>
	<tr>
		<td>Page Dropdown</td>
	</tr>
</tfoot>
</table>

<pre>
<?php print_r($schedule); ?>
</pre>