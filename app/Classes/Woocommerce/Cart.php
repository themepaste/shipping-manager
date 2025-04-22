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
class Cart {

    use Hook;
    use Asset;

    public $shipping_fees_settings;
    public $box_shipping_settings;
    public $free_shipping_settings;

    /**
     * Cart constructor.
     *
     * Registers necessary filters/hooks.
     */
    public function __construct() {
        $this->shipping_fees_settings   = tpsm_get_shipping_fees_settings();
        $this->box_shipping_settings    = tpsm_get_box_shipping_settings();
        $this->free_shipping_settings   = tpsm_get_free_shipping_settings();

        if( $this->free_shipping_settings['minimum-amount'] ) {
            $this->action( 'wp_footer', [$this, 'free_shipping_bar'] );
        }
        // RegisterShippingMethod::get_tpsm_cost()
        if( $this->free_shipping_settings['hide-other'] ) {
            $this->filter( 'woocommerce_package_rates', [ $this, 'filter_shipping_methods' ], 10, 2 );
        }
        $this->filter( 'tpsm_shipping_fees_cost', [ $this, 'shipping_fees_cost' ] );
    }

    public function free_shipping_bar() {
        if( is_checkout() ) {
            $cart   = WC()->cart;
            $total  = $cart->get_total();
            $minimum_cart_ammount = $this->free_shipping_settings['cart-amount'];
            ?>
                <div class="tpsm-free-shipping-bar-wrapper">
                    <h1>Hello Footer</h1>
                </div>
            <?php
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

    /**
     * Calculates the shipping cost based on total weight of the cart.
     *
     * This function is hooked into the 'tpsm_shipping_fees_cost' filter and modifies
     * the shipping cost using a flat rate per weight unit (e.g., per kg/lb).
     *
     * @param float $cost The existing shipping cost passed by the filter.
     *
     * @return float The modified shipping cost based on total weight.
     */
    public function shipping_fees_cost( $cost ) {
        $shipping_fee = 0;
        $cart = WC()->cart;

        // Return original cost if cart is not available (e.g., not loaded yet)
        if ( is_null( $cart ) ) {
            return $cost;
        }

        // Get total weight of products in the cart
        $total_weight = $this->cart_total_product_weights( $cart );
        $total_dimension_cost = $this->cart_total_dimension_fee( $cart );

        // Get custom shipping fee settings from plugin options
        $shipping_fees_settings = $this->shipping_fees_settings;
        $shipping_fees_type     = $shipping_fees_settings['type'] ?? '';
        $cost_per_unit          = $shipping_fees_settings['flat-rate'] ?? 0;
        $weight_range_price     = $shipping_fees_settings['weight-range-price'] ?? [];
        $is_shipping_fees_enabled = $shipping_fees_settings['enabled'] ?? false;

        if( 'tpsm-unit-weight-fee' == $shipping_fees_type && $is_shipping_fees_enabled ) {
            // Only calculate new cost if both weight and cost/unit are valid
            if ( $total_weight && $cost_per_unit ) {
                $cost = $cost_per_unit * $total_weight;
                $shipping_fee += $cost;
            }
        } 
        else if( 'tpsm-weight-range-fee' == $shipping_fees_type && $is_shipping_fees_enabled ) {
            if( ! empty( $weight_range_price ) ) {
                foreach ( $weight_range_price as $key => $value) {
                    if( $value['from'] <= $total_weight && $total_weight <= $value['to'] ) {
                        $shipping_fee += $value['fee'];
                    } 
                }
            }
        }

        $is_box_shipping_enabled = $this->box_shipping_settings['enabled'] ?? false;
        
        if( $is_box_shipping_enabled ) {
            $shipping_fee += $total_dimension_cost;
        }
        return $shipping_fee;
    }

    /**
     * Calculates total valid weight of products in the cart.
     *
     * Skips products with no weight set (null, empty, or zero).
     *
     * @param \WC_Cart $cart The WooCommerce cart object.
     *
     * @return float The total weight of all products in the cart.
     */
    private function cart_total_product_weights( $cart ) {
        $total_weight = 0;

        foreach ( $cart->get_cart() as $cart_item ) {
            $product  = $cart_item['data'];
            $quantity = $cart_item['quantity'];
            $weight   = $product->get_weight();

            // Validate that the weight is set and greater than zero
            if ( $weight !== '' && $weight !== null && floatval( $weight ) > 0 ) {
                $total_weight += floatval( $weight ) * $quantity;
            }
        }

        return $total_weight;
    }

    private function cart_total_dimension_fee( $cart ) {
        $tpsm_dimensions_settings = $this->box_shipping_settings['box-shipping'];
        $total_fee = 0;
    
        foreach ( $cart->get_cart() as $cart_item ) {
            $product    = $cart_item['data'];
            $quantity   = $cart_item['quantity'];
            $length     = floatval( $product->get_length() );
            $width      = floatval( $product->get_width() );
            $height     = floatval( $product->get_height() );
            $fee        = 0;

            // Check if all dimensions are valid (greater than zero)
            if ( $length > 0 && $width > 0 && $height > 0 ) {

                foreach ( $tpsm_dimensions_settings as $value) {
                    $tpsm_length    = $value['length'];
                    $tpsm_width     = $value['width'];
                    $tpsm_height    = $value['height'];

                    if( $length <= $tpsm_length && $width <= $tpsm_width && $height <= $tpsm_height ) {
                        $fee  = $value['fee'];
                        break;
                    }
                }
                $total_fee += $fee * $quantity;
            }

        }
    
        return $total_fee;
    }
}




