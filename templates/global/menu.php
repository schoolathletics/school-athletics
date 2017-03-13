<?php do_action( 'schoolathletics_before_menu' ); ?>
<?php 
	echo '<nav class="nav-school-athletics" itemscope="" itemtype="http://schema.org/SiteNavigationElement">';
	echo '<ul class="menu"><li>';
	foreach ($pages as $page) {
		if($page->post_title == $term->name){
			echo '<a class="home" href="'.get_permalink($page).'">';
			echo $page->post_title;
			echo '</a><ul>';
		}else{
			echo '<li>';
			echo '<a href="'.get_permalink($page).'">';
			echo $page->post_title;
			echo '</a>';
			//print_r($page);
			echo '</li>';
		}
	}
	echo '</ul></li></ul>';
	echo '</nav>';
?>
<?php do_action( 'schoolathletics_after_menu' ); ?>