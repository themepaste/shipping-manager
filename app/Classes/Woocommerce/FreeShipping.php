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

use ThemePaste\ShippingManager\Classes\Shipping\RegisterShippingMethod;
use ThemePaste\ShippingManager\Traits\Asset;
use ThemePaste\ShippingManager\Traits\Hook;

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
     * FreeShipping constructor.
     *
     * @param array $settings Configuration options.
     */
    public function __construct( $settings ) {
        $settings = is_array( $settings ) ? $settings : [];

        $this->hide_other = $settings['hide-other'] ?? '';
        $this->free_shipping_bar = $settings['free-shipping-bar'] ?? '';
        $this->minimum_amount = $settings['minimum-amount'] ?? '';
        $this->cart_amount = $settings['cart-amount'] ?? '';

        $shipping_bar_styles = get_option( 'tpsm-free-shipping-barfree-shipping_settings' );
        $this->shipping_bar_styles = is_array( $shipping_bar_styles ) ? $shipping_bar_styles : [];

        // If the free shipping bar is enabled and a minimum amount is set
        if ( $this->free_shipping_bar && $this->minimum_amount ) {
            $this->action( 'wp_footer', [$this, 'free_shipping_bar'] );
        }

        // If minimum amount logic is enabled
        if ( $this->minimum_amount ) {
            $this->filter( 'tpsm_minimum_amount_setting', [$this, 'tpsm_minimum_amount_setting'], 10, 1 );
        }

        // If hiding other shipping methods when free shipping is available
        if ( $this->hide_other ) {
            $this->filter( 'woocommerce_package_rates', [$this, 'filter_shipping_methods'], 10, 2 );
        }

    }

    /**
     * Check if the cart qualifies for free shipping.
     *
     * @return bool True if cart amount exceeds minimum and logic is enabled.
     */
    private function is_able_tpsm_shipping_free() {
        if ( !function_exists( 'WC' ) || !WC()->cart ) {
            return false; // Or handle accordingly
        }
        if ( !$this->minimum_amount || !is_numeric( $this->cart_amount ) ) {
            return false;
        }

        $cart_total = (float) WC()->cart->get_subtotal();
        $minimum_cart_amount = (float) $this->cart_amount;

        // Compare as floats, and treat "exactly the threshold" as qualifying —
        // the progress bar already stops showing at that point, so a strict `>`
        // left a gap where the bar was hidden but free shipping did not apply.
        return $cart_total >= $minimum_cart_amount;
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
        if ( is_checkout() && function_exists( 'WC' ) && WC()->cart ) {
            $cart_total = (float) WC()->cart->get_subtotal();
            $minimum_cart_amount = is_numeric( $this->cart_amount ) ? (float) $this->cart_amount : 0;

            if ( $minimum_cart_amount > 0 ) {
                $progress_bar_value = ( $cart_total / $minimum_cart_amount ) * 100;

                if ( $minimum_cart_amount > $cart_total ) {
                    printf(
                        '<div class="tpsm-free-shipping-bar-wrapper" %3$s>
							<span>%1$s</span>
							<progress value="%2$s" max="100"></progress>
						</div>',
                        esc_html( $this->shipping_bar_message( $minimum_cart_amount - $cart_total ) ), // Message showing remaining amount
                        esc_attr( round( min( $progress_bar_value, 100 ), 2 ) ),
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped attribute-by-attribute in shipping_bar_styles().
                        $this->shipping_bar_styles()
                    );
                }
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
        $formatted_price = $currency_symbol . wc_format_decimal( $price, wc_get_price_decimals() );

        if ( !empty( $this->shipping_bar_styles['shipping-bar-message'] ) ) {
            return str_replace( '[left_price]', $formatted_price, $this->shipping_bar_styles['shipping-bar-message'] );
        }

        return sprintf(
            /* translators: %s: remaining cart amount, including the currency symbol. */
            __( 'You need %s more in your cart to qualify for free shipping.', 'shipping-manager' ),
            $formatted_price
        );
    }

    /**
     * Generate inline styles for the shipping bar based on user settings.
     *
     * @return string Inline style string.
     */
    private function shipping_bar_styles() {
        if ( empty( $this->shipping_bar_styles ) ) {
            return '';
        }

        $styles = $this->shipping_bar_styles;

        // Every key is optional: the merchant may never have opened the style
        // panel, and reading a missing key warns on PHP 8.
        $alignment  = isset( $styles['shipping-bar-alignment'] ) ? $styles['shipping-bar-alignment'] : 'center';
        $text_color = isset( $styles['shipping-bar-text-color'] ) ? sanitize_hex_color( $styles['shipping-bar-text-color'] ) : '';
        $background = isset( $styles['shipping-bar-background-color'] ) ? sanitize_hex_color( $styles['shipping-bar-background-color'] ) : '';
        $position   = isset( $styles['shipping-bar-position'] ) ? $styles['shipping-bar-position'] : 'bottom';

        $alignment = in_array( $alignment, [ 'left', 'center', 'right' ], true ) ? $alignment : 'center';

        return sprintf(
            'style="text-align: %1$s; color: %2$s; background-color: %3$s; %4$s"',
            esc_attr( $alignment ),
            esc_attr( $text_color ? $text_color : 'inherit' ),
            esc_attr( $background ? $background : 'transparent' ),
            'top' === $position ? ( is_admin_bar_showing() ? 'top: 30px;' : 'top: 0;' ) : 'bottom: 0;'
        );
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
                    unset( $rates[$rate_id] );
                }
            }
        }

        return $rates;
    }

}