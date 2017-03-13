<?php
/**
 * Schedule Loop
 * 
 */

$datetime = date_create(get_post_meta( $post->ID, 'sa_start', true ));
$date = date_format($datetime,"F d, Y");
$time = date_format($datetime,"h:i a");
$location = get_the_terms($post,'sa_location');
$location = (is_array($location)) ? array_pop($location) : null;
$location = ($location) ? $location->name : '- - -';
$terms = wp_get_post_terms($post->ID, 'sa_sport' );
foreach ($terms as $term) {
	$sport_name = $term->name;
}
?>
	<div class="event">
		<ul>
			<h3><?php echo $post->post_title; ?></h3>
			<li><b>Sport:</b> <?php echo $sport_name; ?></li>
			<li><b>Date:</b> <?php echo $date; ?></li>
			<li><b>Time:</b> <?php echo $time; ?></li>
			<li><b>Location:</b> <?php echo $location; ?></li>
		</ul>
	</div>
