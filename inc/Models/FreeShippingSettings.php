<?php
namespace Themepaste\ShippingManager\Models;

use Themepaste\ShippingManager\Constants;

defined( 'ABSPATH' ) || exit;

/**
 * Datasource for free shipping
 *
 * @since 1.1.0
 */
class FreeShippingSettings extends Model {
	/**
	 * List of keys
	 *
	 * @since 1.1.0
	 */
	const HIDE_OTHERS = 'hide-others';
	const FREE_SHIPPING_BAR = 'free-shipping-bar';
	const MINIMUM_AMOUNT = 'minimum-amount';
	const CART_AMOUNT = 'cart-amount';
	const AFTER_COUPON = 'after-coupon';

	/**
	 * Declaring default settings
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	protected array $settings = [
		self::HIDE_OTHERS => Constants::NO,
		self::FREE_SHIPPING_BAR => Constants::NO,
		self::MINIMUM_AMOUNT => Constants::NO,
		self::CART_AMOUNT => '0.00',
		self::AFTER_COUPON => Constants::NO,
	];

	/**
	 * Initializing option keys
	 *
	 * @since 1.1.0
	 */
	public function __construct() {
		parent::__construct( 'tsm_free_shipping' );
	}

	/**
	 * Checks if the data is valid
	 *
	 * @since 1.1.0
	 *
	 * @return bool
	 */
	public function is_valid(): bool {
		if ( parent::is_valid() ) {

		} else  {
			return false;
		}
		return true;
	}
}