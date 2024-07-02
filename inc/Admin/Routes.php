<?php
namespace Themepaste\ShippingManager\Admin;

defined( 'ABSPATH' ) || exit;

class Routes {
	const INSTANCE_KEY = 'admin_routes';

	const ROOT = 'root';

	/**
	 * Stores all named routes against their string
	 *
	 * @since TSM_SINCE
	 *
	 * @var array
	 */
	private array $routes = [
		self::ROOT => 'shipping-manager',
	];

	/**
	 * Returns actual route string from route name
	 *
	 * @since TSM_SINCE
	 *
	 * @param string $key
	 *
	 * @return string
	 */
	public function get_route( string $key = '' ): string {
		if ( isset( $this->routes[ $key ] ) ) {
			return $this->routes[ $key ];
		}
		wp_trigger_error( __METHOD__, "`$key` named route not found" );
		return '';
	}

	/**
	 * Returns the list of all routes
	 *
	 * @since TSM_SINCE
	 *
	 * @return array|string[]
	 */
	public function get_all_routes(): array {
		return $this->routes;
	}
}
