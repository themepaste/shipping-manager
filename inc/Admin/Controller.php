<?php
namespace Themepaste\ShippingManager\Admin;

use Themepaste\ShippingManager\Admin\Routes;
use Themepaste\ShippingManager\ShippingManager;

defined( 'ABSPATH' ) || exit;

// @TODO Add an upgrader to run data upgrader
// @TODO will parse the URL and check what is their inside the URL and if valid pass the data to renderer and render the appropriate template
// @TODO Map requests GET/POST for data saving
// @TODO Fetch data from options/data store and pass down to the rendered
// @TODO Invoke data saver to save data

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
	 * Check if current page is shipping manager admin dashboard
	 *
	 * @since TSM_SINCE
	 *
	 * @return bool
	 */
	public function is_admin_dashboard(): bool {
		global $pagenow;
		if ( 'admin.php' === $pagenow ) {
			$page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';
			return $page === Routes::ROOT;
		}
		return false;
	}

	/**
	 * Retrieve current page for admin settings from URL
	 *
	 * @since TSM_SINCE
	 *
	 * @return string
	 */
	public function current_page(): string {
		$page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';
		$tsm_page = isset( $_GET['tsm-page'] ) ? sanitize_text_field( $_GET['tsm-page'] ) : '';
		if ( $this->is_admin_dashboard() ) {
			$pages = ( ShippingManager::get_instance( Template::INSTANCE_KEY ) )->get_pages();
			if ( in_array( $tsm_page, ( ShippingManager::get_instance( Template::INSTANCE_KEY ) )->get_pages() ) ) {
				return $tsm_page;
			}
		}
		return '';
	}

	/**
	 * Invokes template and renders admin root menu layout
	 *
	 * @since TSM_SINCE
	 *
	 * @return void
	 */
	public function render_admin_root_page() {
		tsm_template( 'admin/index', [ 'page' => $this->current_page() ] );
	}
}