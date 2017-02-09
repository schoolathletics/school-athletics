<?php 


class SA_Admin {

	/**
	 * Hook in methods.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'includes' ) );
	}

	/**
	 * Include any classes we need within admin.
	 */
	public function includes(){
		require SA__PLUGIN_DIR . 'includes/admin/class-sa-admin-menus.php';
	}


}

return new SA_Admin();