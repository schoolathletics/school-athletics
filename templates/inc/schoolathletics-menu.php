<?php 

	$term = SchoolAthletics::get_sport($post);
	//print_r($term);
	$args = array(
		'posts_per_page' => -1,
		'post_type' => 'sa_page',
		'orderby' => 'menu_order',
		'order' => 'DESC',
		'tax_query' => array(
			array(
				'taxonomy' => 'sa_sport',
				'field' => 'id',
				'terms' => $term->term_id, // Where term_id of Term 1 is "1".
			),
		),
	);
	$pages = get_posts($args);
	//print_r($pages);

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