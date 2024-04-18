<?php
namespace Themepaste\ShippingManager;

/**
 * Main Plugin class
 *
 * @since TSM_SINCE
 */
final class ShippingManager {

	private static $self;

	/**
	 * Initializing the plugin
	 *
	 * @since TSM_SINCE
	 */
	private function __construct() {}

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