<?php 
/**
 * Initiate the school athletics admin. It all starts here.
 *
 * @author   Dwayne Parton
 * @category Admin
 * @package  SchoolAthletics/Admin
 * @version  0.0.1
 */
namespace SchoolAthletics\Admin;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin class.
 */
class MetaBoxes {

	/**
	 * Hook in methods.
	 */
	public function __construct() {
		add_action( 'admin_menu', array($this,'remove_meta_boxes') );
		add_action( 'add_meta_boxes', array($this,'add_meta_boxes'));
	}

	/**
	 * Remove Meta Boxes
	 */
	public static function remove_meta_boxes() {
		remove_meta_box('tagsdiv-sa_sport', 'sa_page', 'normal');
		remove_meta_box('tagsdiv-sa_sport', 'post', 'normal');
	} 

	/**
	 * Add Meta Boxes
	 */
	function add_meta_boxes() {
		//Add Meta Boxes for post types
		add_meta_box( 'sport-tagsdiv', 'Sport', array($this,'multiselect_sport_metabox'), 'post', 'side', 'core');
		add_meta_box( 'sport-tagsdiv', 'Sport', array($this,'multiselect_sport_metabox'), 'sa_page', 'side', 'core');
	}

	/**
	 * Build Multiselect Meta Box
	 */
	public static function multiselect_sport_metabox($post) { 
		$taxonomy = 'sa_sport';
		$args = array(
		    'hide_empty' => false,
		);
		$all_ctax_terms = get_terms($taxonomy,$args);
		$all_post_terms = get_the_terms( $post->ID,$taxonomy );
		$name = 'tax_input[' . $taxonomy . '][]'; 
		$array_post_term_ids = array();
		if ($all_post_terms) {
			foreach ($all_post_terms as $post_term) {
				$post_term_id = $post_term->term_id;
				$array_post_term_ids[] = $post_term_id;
			}
		} 
	?>

	<div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv"> 
		<input type="hidden" name="<?php echo $name; ?>" value="0" />
		<ul>
			<?php   
				foreach($all_ctax_terms as $term){
					if (in_array($term->term_id, $array_post_term_ids)) {
						$checked = "checked = ''";
					} else {
						$checked = "";
					}
					$id = $taxonomy.'-'.$term->term_id;
					echo "<li id='$id'>";
					echo "<input type='checkbox' name='{$name}'id='in-$id'". $checked ."value='$term->slug' /><label> $term->name</label><br />";
					echo "</li>";
				}
			?>
		</ul>
	</div>

	<?php

	}

}
