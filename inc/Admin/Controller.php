<?php
namespace Themepaste\ShippingManager\Admin;

use Themepaste\ShippingManager\ShippingManager;

defined( 'ABSPATH' ) || exit;

// @TODO Add an upgrader to run data upgrader

/**
 * Manages Shipping Manager admin menu rendering and data manipulation
 *
 * @since 1.1.0
 */
class Controller {
	/**
	 * Unique id for initialized object
	 *
	 * @since 1.1.0
	 */
	const INSTANCE_KEY = 'admin_controller';

	/**
	 * Initializes:
	 * 	Admin Root Page
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'process_post_request' ] );
		add_action( 'tsm_render_admin_root_page', [ $this, 'render_admin_root_page' ] );
		add_filter( 'tsm_template_page_title', [ $this, 'page_title' ] );
	}

	/**
	 * Assigns page titles to corresponding pages
	 *
	 * @since 1.1.0
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
	 * @since 1.1.0
	 *
	 * @return bool
	 */
	public function is_admin_dashboard(): bool {
		global $pagenow;
		if ( 'admin.php' === $pagenow ) {
			$page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : ''; // WP admin URL parameter
			return $page === Routes::ROOT;
		}
		return false;
	}

	/**
	 * Retrieve current page for admin settings from URL
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	public function current_page(): string {
		$tsm_page = isset( $_GET['tsm-page'] ) ? sanitize_text_field( $_GET['tsm-page'] ) : ''; // WP admin URL parameter
		$template = ( ShippingManager::get_instance( Template::INSTANCE_KEY ) );
		if ( $this->is_admin_dashboard() ) {
			if ( in_array( $tsm_page, $template->get_pages() ) ) {
				return $tsm_page;
			}
		}
		return $template->get_default_page();
	}

	/**
	 * Checks if route name matches current page
	 *
	 * @since 1.1.0
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
			if ( ! empty( $route_data['tsm-page'] ) ) {
				if ( $route_data['tsm-page'] === $current_page ) {
					$current_route = $key;
				}
			} else {

			}
		}

		return $route_name === $current_route;
	}

	/**
	 * Invokes template and renders admin root menu layout
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function render_admin_root_page() {
		$data = [];
		$page = $this->current_page();

		/**
		 * Loads data for admin settings page value hydrating
		 *
		 * @since 1.1.0
		 *
		 * @param string $page
		 *
		 * @param array $data
		 *
		 * @return array Array with loaded data
		 */
		$data = apply_filters( 'tsm_fetch_admin_form_data', $page, $data );

		tsm_template( 'admin/index', compact( 'page', 'data' ) );
	}

	/**
	 * Initializes post request processing
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function process_post_request() {
		if ( $this->is_post_request() ) {
			$page = $this->current_page();

			/**
			 * To initiate form processing
			 *
			 * @since 1.1.0
			 *
			 * @param string $page
			 *
			 * @retun void
			 */
			do_action( 'tsm_process_admin_form_data', $page );
		}
	}

	/**
	 * Checks if post request is submitted
	 *
	 * @since 1.1.0
	 *
	 * @return bool
	 */
	protected function is_post_request(): bool {
		if ( isset( $_SERVER['REQUEST_METHOD'] ) ) {
			$request_method = sanitize_text_field( $_SERVER['REQUEST_METHOD'] );
			if ( 'POST' === $request_method ) {
				return true;
			}
		}

		return false;
	}
}