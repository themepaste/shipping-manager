<?php
namespace Themepaste\ShippingManager;

defined( 'ABSPATH' ) || exit;

/**
 * Main Plugin class
 *
 * @since TSM_SINCE
 */
final class ShippingManager {

	private static $self;

	/**
	 * This will contain all the initialized objects
	 *
	 * @var array
	 */
	private $container = [];

	/**
	 * Initializing the plugin
	 *
	 * @since TSM_SINCE
	 */
	private function __construct() {
		$this->container[ AddShippingSettingsPage::INSTANCE_KEY ] = new AddShippingSettingsPage();
		$this->container[ Assets::INSTANCE_KEY ] = new Assets();
		$this->container[ SaveRule::INSTANCE_KEY ] = new SaveRule();
	}

	/**
	 * Initializes self object and returns the object
	 *
	 * @since TSM_SINCE
	 *
	 * @return ShippingManager
	 */
	public static function init(): ShippingManager {
		if ( empty( self::$self ) ) {
			self::$self = new static();
		}
		return self::$self;
	}

}