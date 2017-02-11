<?php //Short Codes
/*
ADD META
sa_sport_options
*/

$terms = get_terms( array(
    'taxonomy' => 'sa_sport',
    'hide_empty' => false,
) );
//print_r($terms);
if(isset($_GET['action'])){
	$action = $_GET['action'];
}else{
	$action = false;
}


if($action == 'publish'){
	$term_id = $_GET['sport'];
	update_term_meta($term_id, 'sa_sport_status', 'publish');
	sa_notice('Sport Activated.');
	activate_sa_sport($term_id);
}

if($action == 'unpublish'){
	$term_id = $_GET['sport'];
	update_term_meta($term_id, 'sa_sport_status', 'unpublish');
	sa_notice('Sport Deactivated.');
}



?>
<h1>Sports</h1>

<table class="wp-list-table widefat striped pages">
<thead>
	<tr>
		<th colspan="2"><?php _e('Sport','school-athletics'); ?></th>
		<th class="border-left"><?php _e('Rosters','school-athletics'); ?></th>
		<th class="border-left"><?php _e('Schedules','school-athletics'); ?></th>
		<th class="border-left"><?php _e('Staff','school-athletics'); ?></th>
	</tr>
</thead>
<tbody id="the-list">
<?php


foreach ($terms as $term) {
	$options = SA_Admin_Sports::options($term->term_id);
	$edit = admin_url('admin.php?page=sports').'&action=edit&sport='.$term->term_taxonomy_id;
	?>
	<tr>
		<td>
	<?php if(empty($options['status'])) : ?>
			<a id="publish" class="status unpublish" href="<?php echo wp_nonce_url(admin_url('admin.php?page=sports').'&task=publish&sport='.$term->term_taxonomy_id, 'schoolathletics-publish-sport');?>"></a>
	<?php else: ?>
			<a id="unpublish" class="status publish" href="<?php echo wp_nonce_url(admin_url('admin.php?page=sports').'&task=unpublish&sport='.$term->term_taxonomy_id, 'schoolathletics-unpublish-sport');?>"></a>
	<?php endif; ?>
		</td>
		<td>
			<span class="row-title"><?php echo $term->name; ?></span><div class="row-actions"><span class="options"><a href="<?php echo get_permalink($options['page_id']); ?>" >View</a> | </span> <a href="<?php echo $edit; ?>">Options</a></div>
		</td>
	<?php if(!empty($options['roster']) && !empty($options['status'])) : ?>
		<td class="border-left">
			<?php echo get_current_rosters($term); ?>
			<div class="row-actions">
			<span class="options">
				<a href="?page=roster&sport=<?php echo $term->term_id; ?>"> New Roster</a>
			</span>	
			</div>	
		</td>
	<?php else: ?>
		<td class="border-left">
			<span class="dashicons dashicons-lock"></span>
		</td>
	<?php endif; ?>
	<?php if(!empty($options['schedule']) && !empty($options['status'])) : ?>
		<td class="border-left">
			<?php echo get_current_schedule($term); ?>
			<div class="row-actions">
			<span class="options">
				<a href="?page=schedule&sport=<?php echo $term->term_id; ?>">New Schedule</a>
			</span>	
			</div>		
		</td>
	<?php else: ?>
		<td class="border-left">
			<span class="dashicons dashicons-lock"></span>
		</td>
	<?php endif; ?>
	<?php if(!empty($options['staff']) && !empty($options['status'])) : ?>
		<td class="border-left">
			<?php echo get_current_staff($term); ?>
			<div class="row-actions">
			<span class="options">
				<a href="?page=staff&sport=<?php echo $term->term_id; ?>">Add Staff</a>
			</span>	
			</div>
		</td>
	<?php else: ?>
		<td class="border-left">
			<span class="dashicons dashicons-lock"></span>
		</td>
	<?php endif; ?>
	</tr>
	<?php
}
?>
</tbody>
</table>

<style type="text/css">
	.true{color:#46b450;}
	.false{color: #dc3232;}
	.status{display: block; background-color: #dc3232; margin-top:3px; height:1.1em; width:1.1em; border-radius: .55em;}
	#publish.publish,#unpublish.unpublish{display:none;}
	#unpublish.publish, .status.true{display: block; background-color:#46b450;}
	#publish.unpublish{display: block;}
	td.border-left, th.border-left{border-left:1px solid #ebebeb;}
	td.border-right, th.border-right{border-right:1px solid #ebebeb;}
	li.add{background-color:#efefef;line-height:1.75em; position:relative;}
	li.add .dashicons{float:right;}
	li.add .select{display: none; background-color:#ffffff; position:absolute; top:1.75em; width: 100%;}
	li.add .select li{padding:3px 5px; border-bottom:1px solid #e4e4e4;}
	li.add:hover .select{display:block; z-index:1; height:150px; overflow: scroll;}
</style>

<?php

function activate_sa_sport($term){
	if(!is_object($term)){
		$term = get_term($term);
	}

	$sport_home_id = get_term_meta( $term->term_id, 'sa_sport_home_id', true );
	$sport_roster_id = get_term_meta( $term->term_id, 'sa_sport_roster_id', true );
	$sport_schedule_id = get_term_meta( $term->term_id, 'sa_sport_schedule_id', true );
	$user_id = get_current_user_id();

	
	$home = array(
		'ID' => $sport_home_id,
		'post_author' => $user_id,
		'post_content' => '$sa_page_content',
		'post_title' => $term->name,
		'post_name' => $term->slug,
		'post_type' => 'sa_page',
		'post_status' => 'publish',//publish
		//'tax_input' => array('sa_sport' => $_POST['_sa_sport']),	
	);
	$sport_home_id = wp_insert_post($home);
	//Set Post Meta = id
	//if($sport_home_id == ''){
		update_term_meta($term->term_id, 'sa_sport_home_id', $sport_home_id);
	//}

	//if($has_roster){
		//* Add Roster
		$title = 'Roster';
		$content = 'Roster Archive';
		$roster_id = insert_sport_subpage($sport_roster_id, $sport_home_id, $title, $content);
		update_term_meta($term->term_id, 'sa_sport_roster_id', $roster_id);
		//*
	//}

	//if($has_schedule){
		//* Add Schedule
		$title = 'Schedule';
		$content = 'Schedule Archive';
		$schedule_id = insert_sport_subpage($sport_schedule_id, $sport_home_id, $title, $content);
		update_term_meta($term->term_id, 'sa_sport_schedule_id', $schedule_id);
		//*
	//}

}

function insert_sport_subpage($post_id, $parentID, $title, $content){
	$user_id = get_current_user_id();
	$subpage = array(
					'ID' => $post_id,
					'post_author' => $user_id,
					'post_content' => $content,
					'post_title' => $title,
					'post_name' => '',
					'post_parent' => $parentID,
					'post_type' => 'sa_page',
					'post_status' => 'publish',//publish
				);
	$id = wp_insert_post($subpage);
	return $id;
}

function get_current_rosters($term){
	$pages = get_posts(array(
	  'post_type' => 'sa_roster',
	  'numberposts' => 1,
	  'tax_query' => array(
	    array(
	      'taxonomy' => 'sa_sport',
	      'field' => 'id',
	      'terms' => $term->term_id, // Where term_id of Term 1 is "1".
	    )
	  ),
	  'orderby' => 'taxonomy_sa_season',
	  'order'   => 'DESC',
	));
	foreach ($pages as $page) {
		if(is_object($page)){
			if(is_object_in_term( $page->ID, 'sa_season')){
				$season = get_the_terms($page,'sa_season');
				$content = '<a href="?page=roster&sport='.$term->term_id.'&season='.$season[0]->term_id.'&roster_id='.$page->ID.'">'.$season[0]->name.'</a>';
			}
		}
	}
	$content = (!empty($content)) ? $content : '- - -';
	return $content;
}

function get_current_schedule($term){
	$pages = get_posts(array(
	  'post_type' => 'sa_schedule',
	  'numberposts' => 1,
	  'tax_query' => array(
	    array(
	      'taxonomy' => 'sa_sport',
	      'field' => 'id',
	      'terms' => $term->term_id, // Where term_id of Term 1 is "1".
	    )
	  ),
	  'orderby' => 'taxonomy_sa_season',
	  'order'   => 'DESC',
	));
	foreach ($pages as $page) {
		if(is_object($page)){
			if(is_object_in_term( $page->ID, 'sa_season')){
				$season = get_the_terms($page,'sa_season');
				$content = '<a href="?page=schedule&sport='.$term->term_id.'&season='.$season[0]->term_id.'&schedule_id='.$page->ID.'">'.$season[0]->name.'</a>';
			}
		}
	}
	$content = (!empty($content)) ? $content : '- - -';
	return $content;
}

function get_current_staff($term){
	$pages = get_posts(array(
	  'post_type' => 'sa_person',
	  'numberposts' => -1,
	  'tax_query' => array(
	    array(
	      'taxonomy' => 'sa_sport',
	      'field' => 'id',
	      'terms' => $term->term_id,
	    ),
	   	array(
	      'taxonomy' => 'sa_person_type',
	      'field' => 'slug',
	      'terms' => 'staff', // Where term_id of Term 1 is "1".
	    )
	  )
	));
	$content = null;
	foreach ($pages as $page) {
		if(is_object($page)){
			$content .= '<a href="#&roster_id='.$page->ID.'">'.$page->post_title.' <span class="dashicons dashicons-no"></span></a><br />';
		}
	}
	$content = (!empty($content)) ? $content : '- - -';
	return $content;
}

function get_rosters($term){
	$pages = get_posts(array(
	  'post_type' => 'sa_roster',
	  'numberposts' => -1,
	  'tax_query' => array(
	    array(
	      'taxonomy' => 'sa_sport',
	      'field' => 'id',
	      'terms' => $term->term_id, // Where term_id of Term 1 is "1".
	    )
	  )
	));
	$url = admin_url('admin.php?page=roster').'&sport='.$term->term_taxonomy_id;
	$select = '<ul class="rosters">';
	$select .= '<li class="add" >Rosters <span class="dashicons dashicons-arrow-down"></span>';
	$select .= '<ul class="select">';
	$exclude = array();
	foreach ($pages as $page) {
		if(is_object($page)){
			if(is_object_in_term( $page->ID, 'sa_season')){
				$season = get_the_terms($page,'sa_season');
				$select .= '<li ><a href="'.$url.'&roster_id='.$page->ID.'">'.$season[0]->name.' <span class="dashicons dashicons-edit"></span> <a href="#'.$page->ID.'"><span class="dashicons dashicons-no"></span></a></a></li>';
				$exclude[] = $season[0]->term_id;
			}
		}
	}
	$select .= season_select('sa_season',$exclude,$url).'</ul></li>';
	$select .= '</ul>';
	return $select;
}

function get_schedules($term){
	$pages = get_posts(array(
	  'post_type' => 'sa_schedule',
	  'numberposts' => -1,
	  'tax_query' => array(
	    array(
	      'taxonomy' => 'sa_sport',
	      'field' => 'id',
	      'terms' => $term->term_id, // Where term_id of Term 1 is "1".
	    )
	  )
	));
	$url = admin_url('admin.php?page=schedule').'&sport='.$term->term_taxonomy_id;
	$select = '<ul class="schedules">';
	$select .= '<li class="add" >Schedules <span class="dashicons dashicons-arrow-down"></span>';
	$select .= '<ul class="select">';
	$exclude = array();
	foreach ($pages as $page) {
		if(is_object($page)){
			if(is_object_in_term( $page->ID, 'sa_season')){
				$season = get_the_terms($page,'sa_season');
				$select .= '<li ><a href="#'.$page->ID.'">'.$season[0]->name.' <span class="dashicons dashicons-edit"></span></a></li>';
				$exclude[] = $season[0]->term_id;
			}
		}
	}
	$select .= season_select('sa_season',$exclude,$url).'</ul></li>';
	$select .= '</ul>';
	return $select;
}

function get_staff_members($term){
	$pages = get_posts(array(
	  'post_type' => 'sa_person',
	  'numberposts' => -1,
	  'tax_query' => array(
	    array(
	      'taxonomy' => 'sa_sport',
	      'field' => 'id',
	      'terms' => $term->term_id, // Where term_id of Term 1 is "1".
	    ),
	    array(
			                'taxonomy' => 'sa_person_type',
			                'field' => 'name',
			                'terms' => 'staff',
	 	)
	  )
	));
	$select = '<ul class="schedules">';
	$exclude = array();
	foreach ($pages as $page) {
		if(is_object($page)){
			$select .= '<li >
				'.$page->post_title.'
				<a href="'.get_edit_post_link( $page->ID ) .'"><span class="dashicons dashicons-edit"></span></a>
				<a href="#'.$page->ID.'"><span class="dashicons dashicons-no"></span></a>
			</li>';
			$exclude[] = $page->ID;
		}
	}

	$select .= '<li class="add">Add New or Existing <span class="dashicons dashicons-arrow-down"></span>'.cpt_select('sa_staff',$exclude).'</li>';
	$select .= '</ul>';
	return $select;
}

function cpt_select($post_type, $exclude = null){
	$pages = get_posts(array(
	  'post_type'   => $post_type,
	  'numberposts' => -1,
	  'exclude'     => $exclude,
	));
	$select = '<ul class="select">';
	$select .= '<li>New Coach <a href="#"><span class="dashicons dashicons-plus-alt"></span></a></li>';
	foreach ($pages as $page) {
		$select .= '<li>'.$page->post_title.' <a href="#'.$page->ID.'"><span class="dashicons dashicons-plus-alt"></span></a></li>';
	}
	$select .= '</ul>';
	return $select;
}

function season_select($taxonomy, $exclude = null, $url){
	$terms = get_terms(array(
	  'taxonomy'   => $taxonomy,
	  'hide_empty' => false,
	  'exclude'     => $exclude,
	  'order' => 'DESC',
	));
	$select = '';
	foreach ($terms as $term) {
		$select .= '<li>'.$term->name.' <a href="'.$url.'&season='.$term->term_id.'"><span class="dashicons dashicons-plus-alt"></span></a></li>';
	}
	return $select;
}
?>

<?php SchoolAthletics::debug_file_path(SA__PLUGIN_DIR .'includes/admin/views/html-admin-page-sports-default.php'); ?>