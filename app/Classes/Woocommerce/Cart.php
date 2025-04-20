<?php 

namespace ThemePaste\ShippingManager\Classes\Woocommerce;

defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Helpers\Utility;
use ThemePaste\ShippingManager\Traits\Asset;
use ThemePaste\ShippingManager\Traits\Hook;

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

    /**
     * Cart constructor.
     *
     * Registers necessary filters/hooks.
     */
    public function __construct() {
        $this->filter( 'tpsm_shipping_fees_cost', [ $this, 'shipping_fees_cost' ] );
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
        $cart = WC()->cart;

        // Return original cost if cart is not available (e.g., not loaded yet)
        if ( is_null( $cart ) ) {
            return $cost;
        }

        // Get total weight of products in the cart
        $total_weight = $this->cart_total_product_weights( $cart );
        $total_dimension = $this->cart_total_dimension( $cart );

        // Get custom shipping fee settings from plugin options
        $shipping_fees_settings = tpsm_get_shipping_fees_settings();
        $shipping_fees_type     = $shipping_fees_settings['type'] ?? '';
        $cost_per_unit          = $shipping_fees_settings['flat-rate'] ?? 0;
        $weight_range_price     = $shipping_fees_settings['weight-range-price'] ?? [];

        if( 'tpsm-unit-weight-fee' == $shipping_fees_type ) {
            // Only calculate new cost if both weight and cost/unit are valid
            if ( $total_weight && $cost_per_unit ) {
                return $cost_per_unit * $total_weight;
            }
        } 
        else if( 'tpsm-weight-range-fee' == $shipping_fees_type ) {
            if( ! empty( $weight_range_price ) ) {
                foreach ( $weight_range_price as $key => $value) {
                    if( $value['from'] <= $total_weight && $total_weight <= $value['to'] ) {
                        return $value['fee'];
                    } 
                }
            }
        }
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

    private function cart_total_dimension( $cart ) {
        $total_dimension = 0;
    
        foreach ( $cart->get_cart() as $cart_item ) {
            $product  = $cart_item['data'];
            $quantity = $cart_item['quantity'];
    
            $length = floatval( $product->get_length() );
            $width  = floatval( $product->get_width() );
            $height = floatval( $product->get_height() );
    
            // Check if all dimensions are valid (greater than zero)
            if ( $length > 0 && $width > 0 && $height > 0 ) {
                $volume = $length * $width * $height;
                $total_dimension += $volume * $quantity;
            }
        }
    
        return $total_dimension;
    }
}
