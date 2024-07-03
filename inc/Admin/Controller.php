<?php
namespace Themepaste\ShippingManager\Admin;

defined( 'ABSPATH' ) || exit;

// @TODO will parse the URL and check what is their inside the URL and if valid pass the data to renderer and render the appropriate template
// @TODO Map requests GET/POST for data saving
// @TODO Manage Data Rendering
// @TODO Fetch data from options/data store and pass down to the rendered

/**
 * Manages Shipping Manager admin menu rendering and data manipulation
 *
 * @since TSM_SINCE
 */
class Controller {
	/**
	 * Unique id for initialized object
	 *
	 * @since TSM_SINCE
	 */
	const INSTANCE_KEY = 'admin_controller';

	/**
	 * Initializes:
	 * 	Admin Root Page
	 *
	 * @since TSM_SINCE
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'tsm_render_admin_root_page', [ $this, 'render_admin_root_page' ] );
	}

	/**
	 * Invokes template and renders admin root menu layout
	 *
	 * @since TSM_SINCE
	 *
	 * @return void
	 */
	public function render_admin_root_page() {
		tsm_template( 'admin/index' );
	}
}