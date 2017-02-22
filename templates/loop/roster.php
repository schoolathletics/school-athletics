<?php
/**
 * Roster Loop
 * 
 */
$sport = SchoolAthletics::get_sport($post);
$season = SchoolAthletics::get_season($post);
$members = SchoolAthletics::get_roster_members($sport, $season);

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
	foreach ($members as $member) {
	?>
	<tr>
		<td><?php echo $member->post_title; ?></td>
	</tr>
	<?php
	}
	?>
</tbody>
</table>