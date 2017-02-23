<?php
/**
 * Schedule Loop
 * 
 */
$sport = SchoolAthletics::get_sport($post);
$staff = new \SchoolAthletics\Staff($sport);
$members = $staff->staff;

?>
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

<pre>
<?php print_r($staff); ?>
</pre>