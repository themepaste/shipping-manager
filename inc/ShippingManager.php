<?php
namespace Themepaste\ShippingManager;

use Themepaste\ShippingManager\Admin\Menu;
use Themepaste\ShippingManager\Admin\Routes;

/**
 * Main plugin file
 *
 * @since 1.0.0
 */
final class ShippingManager {

	/**
	 * Plugin main class instance holder
	 *
	 * @since 1.0.0
	 *
	 * @var ShippingManager
	 */
	private static ShippingManager $instance;

	/**
	 * Container to track already initialized objects
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private static array $container = [];

	/**
	 * Initializes the plugin
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function __construct() {
		self::$container[ Routes::INSTANCE_KEY ] = new Routes();
		self::$container[ Menu::INSTANCE_KEY ] = new Menu();
	}

	/**
	 * Returns main plugin object or initialized plugin object with keys
	 *
	 * @param string $key
	 *
	 * @return object
	 */
	public static function get_instance( string $key = '' ): object {
		if ( empty( self::$instance ) ) {
			self::$instance = new ShippingManager();
		}

		if ( empty( $key ) ) {
			return self::$instance;
		} elseif ( isset( self::$container[ $key ] ) ) {
				return self::$container[ $key ];
			} else {
				wp_trigger_error( __METHOD__, "`$key` object not found" );
		}
		return (object)[];
	}
}
