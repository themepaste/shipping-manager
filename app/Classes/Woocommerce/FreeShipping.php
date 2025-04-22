<?php 

namespace ThemePaste\ShippingManager\Classes\Woocommerce;

defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Helpers\Utility;
use ThemePaste\ShippingManager\Traits\Asset;
use ThemePaste\ShippingManager\Traits\Hook;
use ThemePaste\ShippingManager\Classes\Shipping\RegisterShippingMethod;

/**
 * Class Cart
 *
 * Handles custom shipping fee calculations based on cart total weight.
 *
 * @package ThemePaste\ShippingManager\Classes\Woocommerce
 */
class FreeShipping {

    use Hook;
    use Asset;

    private $hide_other, $free_shipping_bar, $minimum_amount, $cart_amount;

    public function __construct( $settings ) {

        $this->hide_other         = $settings['hide-other'] ?? '';
        $this->free_shipping_bar  = $settings['free-shipping-bar'] ?? '';
        $this->minimum_amount     = $settings['minimum-amount'] ?? '';
        $this->cart_amount        = $settings['cart-amount'] ?? '';

        if( $is_enable = $this->free_shipping_bar && $is_enable = $this->minimum_amount ) {
            $this->action( 'wp_footer', [$this, 'free_shipping_bar'] );
        }
        // RegisterShippingMethod::get_tpsm_cost()
        if( $is_enable = $this->hide_other ) {
            $this->filter( 'woocommerce_package_rates', [ $this, 'filter_shipping_methods' ], 10, 2 );
        }
    }

    public function free_shipping_bar() {
        if( is_checkout() ) {
            $cart   = WC()->cart;
            $cart_total           = $cart->get_subtotal();
            $currency_symbol      = get_woocommerce_currency_symbol();
            $minimum_cart_ammount = $this->cart_amount;
            $progress_bar_value   = ( $cart_total / $minimum_cart_ammount ) * 100;

            if( $minimum_cart_ammount > $cart_total ):
                printf(
                    '<div class="tpsm-free-shipping-bar-wrapper">
                        <span>You need %1$s%2$s more in your cart to qualify for free shipping.</span>
                        <progress id="file" value="%3$s" max="100"></progress>
                    </div>',
                    $currency_symbol,
                    $minimum_cart_ammount - $cart_total,
                    $progress_bar_value
                );
            endif;
        }
    }

    public function filter_shipping_methods($rates, $package) {
        $allowed_shipping_method = RegisterShippingMethod::ID; // Change this to your method's ID
    
        foreach ($rates as $rate_id => $rate) {
            if ($rate->method_id !== $allowed_shipping_method) {
                unset($rates[$rate_id]);
            }
        }
    
        return $rates;
    }

}