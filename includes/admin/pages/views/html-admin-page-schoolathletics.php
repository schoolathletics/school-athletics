<?php
/**
 * Admin View: Page - Status
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="wrap">
	<h1 ><?php _e( 'School Athletics', 'school-athletics' ); ?></h1>
	<div class="dashboard-widgets-wrap">
	<div id="dashboard-widgets" class="metabox-holder">
		<div id="postbox-container-1" class="postbox-container">
		<div style="padding:0px 5px;">
			
			<div class="postbox">
				<h2 class="hndle">
					<span><?php _e('Overview', 'school-athletics' ); ?></span>
				</h2>
				<div class="inside">
					<div class="main">
					<p><?php _e('The Dashboard for School Athletics', 'school-athletics' ); ?></p>
					</div>
				</div>
			</div>

			<div class="postbox">
				<h2 class="hndle">
					<span><?php _e('SchoolAthletics.org', 'school-athletics' ); ?></span>
				</h2>
				<div class="inside">
					<div class="main">
						<?php 
						$items = wp_remote_get( 'http://schoolathletics.org/wp-json/wp/v2/posts?per_page=5' );
						$items = json_decode( wp_remote_retrieve_body( $items ), true );;
						foreach ($items as $item) {
							?>
							<h3><a href="<?php echo $item['link']; ?>"><?php echo $item['title']['rendered']; ?></a></h3>
							<small><?php echo date('F j, Y', strtotime($item['date']) ); ?></small>
							<p><?php echo $item['excerpt']['rendered']; ?></p>
							<hr />
							<?php //print_r($item); ?>
							<?php
						}
						?>
						<p>Updates provided from <a href="http://schoolathletics.org">SchoolAthletics.org</a></p>
					</div>
				</div>
			</div>

		</div>
		</div>

		<div id="postbox-container-2" class="postbox-container">
		<div style="padding:0px 5px;">

			<div class="postbox">
				<h2 class="hndle">
					<span><?php _e('Help', 'school-athletics' ); ?></span>
				</h2>
				<div class="inside">
					<div class="main">
					<p><?php _e('Get your help here!', 'school-athletics' ); ?></p>
					<ul>
						<li>Add a Sport</li>
						<li>Add a Roster</li>
						<li>Add a Schedule</li>
					</ul>
					</div>
				</div>
			</div>

		</div>
		</div>

	</div>
	</div>
</div>

<?php //\SchoolAthletics\Debug::file_path(SA__PLUGIN_DIR .'includes/admin/views/html-admin-page-settings.php'); ?>
<?php //\SchoolAthletics\Debug::content($_REQUEST); ?>