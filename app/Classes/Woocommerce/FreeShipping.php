<?php
/**
 * FreeShipping Class
 *
 * Handles the logic for displaying a free shipping progress bar,
 * conditionally hiding other shipping methods, and applying minimum amount checks.
 *
 * @package ThemePaste\ShippingManager\Classes\Woocommerce
 */

namespace ThemePaste\ShippingManager\Classes\Woocommerce;

defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Helpers\Utility;
use ThemePaste\ShippingManager\Traits\Asset;
use ThemePaste\ShippingManager\Traits\Hook;
use ThemePaste\ShippingManager\Classes\Shipping\RegisterShippingMethod;

/**
 * Class FreeShipping
 *
 * Applies free shipping conditions and optionally displays a shipping bar in the cart or checkout.
 */
class FreeShipping {

	use Hook;
	use Asset;

	/**
	 * @var bool Whether to hide other shipping methods when free shipping is available.
	 */
	private $hide_other;

	/**
	 * @var bool Whether to display the free shipping progress bar.
	 */
	private $free_shipping_bar;

	/**
	 * @var bool Whether to enforce a minimum cart amount for free shipping.
	 */
	private $minimum_amount;

	/**
	 * @var float The minimum cart amount required for free shipping.
	 */
	private $cart_amount;

	/**
	 * @var array Styles and messages for the free shipping bar.
	 */
	private $shipping_bar_styles;

	/**
	 * @var array Settings.
	 */
	private $general_settings;

	/**
	 * FreeShipping constructor.
	 *
	 * @param array $settings Configuration options.
	 */
	public function __construct( $settings ) {
		$this->hide_other          = $settings['hide-other'] ?? '';
		$this->free_shipping_bar   = $settings['free-shipping-bar'] ?? '';
		$this->minimum_amount      = $settings['minimum-amount'] ?? '';
		$this->cart_amount         = $settings['cart-amount'] ?? '';
		$this->shipping_bar_styles = get_option( 'tpsm-free-shipping-barfree-shipping_settings' );
		$this->general_settings    = get_option( 'tpsm-general_settings' );

		// If the free shipping bar is enabled and a minimum amount is set
		if ( $this->free_shipping_bar && $this->minimum_amount && $this->general_settings['is-plugin-enable'] ) {
			$this->action( 'wp_footer', [ $this, 'free_shipping_bar' ] );
		}

		// If minimum amount logic is enabled
		if ( $this->minimum_amount ) {
			$this->filter( 'tpsm_minimum_amount_setting', [ $this, 'tpsm_minimum_amount_setting' ], 10, 1 );
		}

		// If hiding other shipping methods when free shipping is available
		if ( $this->hide_other ) {
			$this->filter( 'woocommerce_package_rates', [ $this, 'filter_shipping_methods' ], 10, 2 );
		}

	}

	/**
	 * Check if the cart qualifies for free shipping.
	 *
	 * @return bool True if cart amount exceeds minimum and logic is enabled.
	 */
	private function is_able_tpsm_shipping_free() {
		if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
			return false; // Or handle accordingly
		}
		$cart_total           = WC()->cart->get_subtotal();
		$minimum_cart_amount  = $this->cart_amount;
		
		return ( $cart_total > $minimum_cart_amount && $this->minimum_amount );
	}

	/**
	 * Filter hook to determine if minimum amount condition should apply.
	 *
	 * @param bool $is_enable Original state.
	 * @return bool Modified state based on cart subtotal.
	 */
	public function tpsm_minimum_amount_setting( $is_enable ) {
		$is_enable = $this->is_able_tpsm_shipping_free();
		return $is_enable;
	}

	/**
	 * Display a shipping bar in the footer during checkout.
	 */
	public function free_shipping_bar() {
		if ( is_checkout() ) {
			$cart_total          = WC()->cart->get_subtotal();
			$minimum_cart_amount = $this->cart_amount;

			$progress_bar_value = ( $cart_total / $minimum_cart_amount ) * 100;

			if ( $minimum_cart_amount > $cart_total ) {
				printf(
					'<div class="tpsm-free-shipping-bar-wrapper" %3$s>
						%1$s
						<progress value="%2$s" max="100"></progress>
					</div>',
					esc_html( $this->shipping_bar_message( $minimum_cart_amount - $cart_total ) ), // Message showing remaining amount
					esc_html( $progress_bar_value ),
					wp_kses_post( $this->shipping_bar_styles() ),
				);
			}
		}
	}

	/**
	 * Generate a dynamic message for the shipping bar.
	 *
	 * @param float $price The remaining amount required for free shipping.
	 * @return string HTML output with the message.
	 */
	private function shipping_bar_message( $price ) {
		$currency_symbol = get_woocommerce_currency_symbol();

		if ( ! empty( $this->shipping_bar_styles ) && is_array( $this->shipping_bar_styles ) ) {
			if ( ! empty( $this->shipping_bar_styles['shipping-bar-message'] ) ) {
				return str_replace( '[left_price]', $currency_symbol . $price, $this->shipping_bar_styles['shipping-bar-message'] );
			}
		}

		return sprintf(
			'<span>You need %1$s%2$s more in your cart to qualify for free shipping.</span>',
			$currency_symbol,
			$price
		);
	}

	/**
	 * Generate inline styles for the shipping bar based on user settings.
	 *
	 * @return string Inline style string.
	 */
	private function shipping_bar_styles() {
		if ( ! empty( $this->shipping_bar_styles ) && is_array( $this->shipping_bar_styles ) ) {
			return sprintf(
				'style="
					text-align: %1$s;
					color: %2$s;
					background-color: %3$s;
					%4$s;
				"',
				esc_attr( $this->shipping_bar_styles['shipping-bar-alignment'] ),
				esc_attr( $this->shipping_bar_styles['shipping-bar-text-color'] ),
				esc_attr( $this->shipping_bar_styles['shipping-bar-background-color'] ),
				$this->shipping_bar_styles['shipping-bar-position'] === 'top' ? 'top: 0;' : 'bottom: 0;'
			);
		}

		return '';
	}

	/**
	 * Filter WooCommerce shipping methods to hide others if free shipping is available.
	 *
	 * @param array $rates   Available shipping rates.
	 * @param array $package Shipping package.
	 * @return array Filtered shipping rates.
	 */
	public function filter_shipping_methods( $rates, $package ) {
		if ( $this->is_able_tpsm_shipping_free() ) {
			$allowed_shipping_method = RegisterShippingMethod::ID;

			foreach ( $rates as $rate_id => $rate ) {
				if ( $rate->method_id !== $allowed_shipping_method ) {
					unset( $rates[ $rate_id ] );
				}
			}
		}

		return $rates;
	}

}
