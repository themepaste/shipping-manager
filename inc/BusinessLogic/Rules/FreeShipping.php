<?php
namespace Themepaste\ShippingManager\BusinessLogic\Rules;

defined( 'ABSPATH' ) || exit;

use Themepaste\ShippingManager\Admin\Form\ShippingFees;
use Themepaste\ShippingManager\Models\FreeShippingSettings;

/**
 * Enforces rules interface
 *
 * @since 1.2.1
 */
class FreeShipping extends AbstractRules implements RulesInterface {
	/**
	 * Unique name for rules
	 *
	 * @since 1.2.1
	 */
	const RULES_KEY = 'tsm-free-shipping';

	/**
	 * Calculates current rules fees
	 *
	 * @since 1.2.1
	 *
	 * @return float
	 */
	public function calculate(): float {
		global $woocommerce;
		$shipping_fees = new FreeShippingSettings();
		$hide_other = $shipping_fees->fetch()->get( FreeShippingSettings::HIDE_OTHERS );
		$enable_minimum = $shipping_fees->fetch()->get( FreeShippingSettings::MINIMUM_AMOUNT );
		$minimum_amount = $shipping_fees->fetch()->get( FreeShippingSettings::CART_AMOUNT );
		$after_coupon = $shipping_fees->fetch()->get( FreeShippingSettings::AFTER_COUPON );
		$cart_total = $woocommerce->cart->get_cart_contents_total();
		if ( 'yes' === $after_coupon ) {
			$cart_total = $cart_total + $woocommerce->cart->get_discount_total();
		}

		if ( ( $hide_other == 'yes' ) && ( $enable_minimum === 'yes' ) && ( $cart_total >= $minimum_amount ) ) {
			add_filter( 'woocommerce_package_rates', array( $this, 'tps_hide_shipping_when_free_is_available' ), 100 );
		} elseif ( ( $hide_other == 'no' ) && ( $enable_minimum === 'yes' ) && ( $cart_total >= $minimum_amount ) ) {
			add_filter( 'woocommerce_package_rates', array( $this, 'tps_show_shipping_when_free_is_available' ), 100 );
		}

		return 0;
	}

	/**
	 * Hide shipping rates when free shipping is available.
	 *
	 * @param array $rates Array of rates found for the package.
	 * @return array
	 * 
	 * @since 1.2.1
	 */
	public function tps_hide_shipping_when_free_is_available( $rates ): array {

		$all_free_rates = array();

		foreach ( $rates as $rate_id => $rate ) {
			if ( 'tsm-shipping-manager-shipping-method' === $rate->method_id ) {
				$all_free_rates[ $rate_id ] = $rate;
				$rate->cost = 0;
				break;
			}
		}

		return empty( $all_free_rates ) ? $rates : $all_free_rates;

	}

	/**
	 * Show shipping rates when free shipping is available.
	 *
	 * @param array $rates Array of rates found for the package.
	 * @return array
	 * 
	 * @since 1.2.1
	 */
	public function tps_show_shipping_when_free_is_available( $rates ): array {

		$all_free_rates = array();

		foreach ( $rates as $rate_id => $rate ) {
			if ( 'tsm-shipping-manager-shipping-method' === $rate->method_id ) {
				$rate->cost = 0;
				break;
			}
		}

		return empty( $all_free_rates ) ? $rates : $all_free_rates;

	}

}
