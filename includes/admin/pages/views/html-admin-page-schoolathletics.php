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
					<p><?php _e('The Dashboard for School Athletics', 'school-athletics' ); ?></p>
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

<?php \SchoolAthletics\Debug::file_path(SA__PLUGIN_DIR .'includes/admin/views/html-admin-page-settings.php'); ?>
<?php \SchoolAthletics\Debug::content($_REQUEST); ?>