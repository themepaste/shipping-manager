<?php
namespace Themepaste\ShippingManager\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Manges admin routes and url
 *
 * @since 1.1.0
 */
class Routes {
	/**
	 * Unique id for initialized object
	 *
	 * @since 1.1.0
	 */
	const INSTANCE_KEY = 'admin_routes';

	/**
	 * ID for main shipping manager admin settings page
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	const ROOT = 'shipping-manager';

	/**
	 * ID for Shipping Fees TSM sub-page
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	const SHIPPING_FEES = 'shipping-fees';

	/**
	 * ID for Free Shipping TSM sub-page
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	const FREE_SHIPPING = 'free-shipping';

	/**
	 * ID for Per Product Shipping TSM sub-page
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	const PER_PRODUCT_SHIPPING = 'per-product-shipping';

	/**
	 * ID for Product Page Shipping TSM sub-page
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	const PRODUCT_PAGE_SHIPPING = 'product-page-shipping';

	/**
	 * ID for Track Shipping TSM sub-page
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	const TRACK_SHIPPING = 'track-shipping';

	/**
	 * Stores all named routes against their string
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	private array $routes = [
		self::ROOT => [
			'page' => 'shipping-manager'
		],
		self::SHIPPING_FEES => [
			'page' => 'shipping-manager',
			'tsm-page' => 'shipping-fees'
		],
		self::FREE_SHIPPING => [
			'page' => 'shipping-manager',
			'tsm-page' => 'free-shipping'
		],
		self::PER_PRODUCT_SHIPPING => [
			'page' => 'shipping-manager',
			'tsm-page' => 'per-product-shipping'
		],
		self::PRODUCT_PAGE_SHIPPING => [
			'page' => 'shipping-manager',
			'tsm-page' => 'product-page-shipping'
		],
		self::TRACK_SHIPPING => [
			'page' => 'shipping-manager',
			'tsm-page' => 'track-shipping'
		],
	];

	/**
	 * Builds url from route name
	 *
	 * @since 1.1.0
	 *
	 * @param string $route_name
	 * @param array  $args
	 *
	 * @return string
	 */
	public function get_url( string $route_name = '', array $args = [] ): string {
		$url = admin_url();
		if ( isset( $this->routes[ $route_name ] ) ) {
			$url .= 'admin.php?' . http_build_query( $this->routes[ $route_name ] );
			if ( ! empty( $args ) ) {
				$url .= '&' . http_build_query( $args );
			}
		} else {
			wp_trigger_error( __METHOD__, "`$route_name` named route not found." );
		}
		return $url;
	}

	/**
	 * Returns the list of all routes with query string data
	 *
	 * @since 1.1.0
	 *
	 * @return array|string[]
	 */
	public function get_all_routes(): array {
		return $this->routes;
	}
}
