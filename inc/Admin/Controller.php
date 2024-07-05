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
		add_filter( 'tsm_template_page_title', [ $this, 'page_title' ] );
	}

	/**
	 * Assigns page titles to corresponding pages
	 *
	 * @since TSM_SINCE
	 *
	 * @param $page
	 *
	 * @return string
	 */
	public function page_title( string  $page ): string {
		$page_title = [
			Routes::SHIPPING_FEES => __( 'Shipping Fees', 'shipping-manager' ),
			Routes::FREE_SHIPPING => __( 'Free Shipping', 'shipping-manager' ),
			Routes::PER_PRODUCT_SHIPPING => __( 'Per Product Shipping', 'shipping-manager' ),
			Routes::PRODUCT_PAGE_SHIPPING => __( 'Product Page Shipping', 'shipping-manager' ),
			Routes::TRACK_SHIPPING => __( 'Track Shipping', 'shipping-manager' ),
		];

		return $page_title[ $page ] ?? '';
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
		$tsm_page = isset( $_GET['tsm-page'] ) ? sanitize_text_field( $_GET['tsm-page'] ) : '';
		if ( $this->is_admin_dashboard() ) {
			if ( in_array( $tsm_page, ( ShippingManager::get_instance( Template::INSTANCE_KEY ) )->get_pages() ) ) {
				return $tsm_page;
			}
		}
		return '';
	}

	/**
	 * Checks if route name matches current page
	 *
	 * @since TSM_SINCE
	 *
	 * @param string $route_name
	 *
	 * @return bool
	 */
	public function is_current_page( string $route_name ): bool {
		$current_page = $this->current_page();
		$current_route = '';

		// Get route name from current page
		$routes = ( ShippingManager::get_instance( Routes::INSTANCE_KEY ) )->get_all_routes();
		foreach ( $routes as $key => $route_data ) {
			if ( $route_data['tsm-page'] === $current_page ) {
				$current_route = $key;
			}
		}

		return $route_name === $current_route;
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