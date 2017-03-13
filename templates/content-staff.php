<?php
/**
 * Staff Template
 * 
 */
$sport = SchoolAthletics::get_sport($post);
$staff = new \SchoolAthletics\Staff($sport);
$members = $staff->staff;
?>

<?php
	foreach ($members as $member) {
	?>
	<div class="staff">
		<div><?php echo get_the_post_thumbnail($member->ID, 'thumbnail'); ?></div>
		<h2><?php echo $member->post_title; ?></h2>
		<div><?php echo $member->post_content; ?></div>
	</div>
	<?php
	}
?>