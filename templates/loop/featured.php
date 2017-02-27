<?php
/**
 * Schedule Loop
 * 
 */
?>
<div class="schoolathletics featured">
	<div class="two-thirds first">
		<a href="<?php echo get_permalink($post->ID); ?>">
			<?php the_post_thumbnail('large')?>
		</a>
	</div>
	<div class="one-third">
		<a href="<?php echo get_permalink($post->ID); ?>">
			<h2 class="title"><?php the_title(); ?></h2>
		</a>
		<div class="excerpt"><?php the_excerpt(); ?></div>
	</div>
</div>
<div class="clearfix"></div>