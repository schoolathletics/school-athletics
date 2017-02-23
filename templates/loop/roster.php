<?php
/**
 * Roster Loop
 * 
 */
//$sport = SchoolAthletics::get_sport($post);
//$season = SchoolAthletics::get_season($post);
//$members = SchoolAthletics::get_roster_members($sport, $season);
$roster = new \SchoolAthletics\Roster($post);
$athletes = $roster->athletes;
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
	foreach ($athletes as $athlete) {
	?>
	<tr>
		<td><?php echo $athlete['name']; ?></td>
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
<?php print_r($roster); ?>
</pre>