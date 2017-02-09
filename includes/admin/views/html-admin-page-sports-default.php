<?php //Short Codes
/*
ADD META
sa_sport_status
sa_sport_id
sa_sport_has_roster
sa_sport_roster_id
sa_sport_roster_options
sa_sport_has_schedule
sa_sport_schedule_id
sa_sport_schedule_options
sa_sport_facebook
sa_sport_instagram
sa_sport_twitter
*/

function sa_notice($message) {
    ?>
	 <div class="notice notice-success is-dismissible"> 
		<p><strong><?php echo $message ?></strong></p>
		<button type="button" class="notice-dismiss">
			<span class="screen-reader-text">Dismiss this notice.</span>
		</button>
	</div>
    <?php
}

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
<p>This page manages sports.</p>
<table class="wp-list-table widefat striped pages">
<thead>
	<tr>
		<th width="20px"></th>
		<th>Sport</th>
		<th>Rosters</th>
		<th>Schedules</th>
		<th>Coaches</th>
	</tr>
</thead>
<tbody id="the-list">
<?php


foreach ($terms as $term) {
	$status = get_term_meta( $term->term_id, 'sa_sport_status', true );
	if($status == ''){
		$status = 'unpublish';
	}
	$home = get_permalink(get_term_meta( $term->term_id, 'sa_sport_home_id', true ));
	$edit = admin_url('admin.php?page=sports').'&action=edit&sport='.$term->term_taxonomy_id;
	echo '<tr>
		<td>
			<a id="publish" class="status '.$status.'" href="'.admin_url('admin.php?page=sports').'&action=publish&sport='.$term->term_taxonomy_id.'"></a>
			<a id="unpublish" class="status '.$status.'" href="'.admin_url('admin.php?page=sports').'&action=unpublish&sport='.$term->term_taxonomy_id.'"></a>
		</td>
		<td><span class="row-title">'.$term->name.'</span><div class="row-actions"><span class="options"><a href="'.$home.'" >View</a> | </span> <a href="'.$edit.'">Options</a></div></td>
		<td>'.get_rosters($term).'</td>
		<td>'.get_schedules($term).'</td>
		<td>'.get_staff_members($term).'</td>
		</tr>';
}
?>
</tbody>
</table>

<style type="text/css">
	.status{display: block; background-color: #dc3232; height:1em; width:1em; border-radius: .5em;}
	#publish.publish,#unpublish.unpublish{display:none;}
	#unpublish.publish{display: block; background-color:#46b450;}
	#publish.unpublish{display: block;}
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
	  'post_type' => 'sa_staff',
	  'numberposts' => -1,
	  'tax_query' => array(
	    array(
	      'taxonomy' => 'sa_sport',
	      'field' => 'id',
	      'terms' => $term->term_id, // Where term_id of Term 1 is "1".
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