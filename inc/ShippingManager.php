<?php
namespace Themepaste\ShippingManager;

class ShippingManager {

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
	 * @return ShippingManager
	 */
	public static function init(): ShippingManager {
		if ( empty( self::$self ) ) {
			self::$self = new static();
		}
		return self::$self;
	}

}