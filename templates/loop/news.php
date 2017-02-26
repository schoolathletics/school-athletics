<?php
/**
 * Schedule Loop
 * 
 */
?>
<div class="schoolathletics news">
	<div class="thumbnail">
		<a href="<?php echo get_permalink($post->ID); ?>">
			<?php the_post_thumbnail('medium')?>
		</a>
	</div>
	<a href="<?php echo get_permalink($post->ID); ?>">
		<h2 class="title"><?php the_title(); ?></h2>
	</a>
	<div class="excerpt"><?php the_excerpt(); ?></div>
</div>
<div class="clearfix"></div>