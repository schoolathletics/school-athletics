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
?>
	<div>
		<h3><?php echo $post->post_title; ?></h3>
		<p><?php echo $date; ?></p>
		<p><?php echo $time; ?></p>
		<p><?php echo $location; ?></p>
	</div>
