<?php
namespace Themepaste\ShippingManager;

defined( 'ABSPATH' ) || exit;

use Themepaste\ShippingManager\Admin\{
	Assets,
	Controller,
	Menu,
	Messages,
	Routes,
	Template,
	Form\Manager as Form_Manager,
};
use Themepaste\ShippingManager\BusinessLogic\ {
	Manager as BusinessManager,
	ShippingCost,
	ProductPageShipping
};

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
		self::$container[ Assets::INSTANCE_KEY ] = new Assets();
		self::$container[ Messages::INSTANCE_KEY ] = new Messages();
		self::$container[ Template::INSTANCE_KEY ] = new Template();
		self::$container[ Menu::INSTANCE_KEY ] = new Menu();
		self::$container[ Controller::INSTANCE_KEY ] = new Controller();
		self::$container[ Form_Manager::INSTANCE_KEY ] = new Form_Manager();

		// Frontend logic
		self::$container[ BusinessManager::INSTANCE_KEY ] = new BusinessManager();
		self::$container[ ShippingCost::INSTANCE_KEY ] = new ShippingCost();
		self::$container[ ProductPageShipping::INSTANCE_KEY ] = new ProductPageShipping();
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
