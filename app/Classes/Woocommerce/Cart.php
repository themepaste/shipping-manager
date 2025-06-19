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

        new FreeShipping( $this->free_shipping_settings );
        $this->filter( 'tpsm_shipping_fees_cost', [ $this, 'shipping_fees_cost' ] );
    }

    
    /**
     * Calculate shipping cost based on cart total price, weight, and shipping classes.
     * 
     * @param array $data Shipping fees data.
     * 
     * @return float Shipping cost.
     */
    public function shipping_fees_cost( $data ) {

        if ( is_string( $data ) ) {
            $data = json_decode( $data, true );
        }
        $cart = WC()->cart;

        if( ! is_array( $data ) && empty( $data ) ) {
            return;
        }

        if ( is_null( $cart ) ) {
            return;
        }

        // $conditions_data = $this->conditions_data;
        $flat_rate_items        = $this->dataFilterByConditionName( $data, 'tpsm-flat-rate' );
        $total_price_items      = $this->dataFilterByConditionName( $data, 'tpsm-total-price' );
        $sub_total_price_items  = $this->dataFilterByConditionName( $data, 'tpsm-sub-total-price' );
        $total_weight_items     = $this->dataFilterByConditionName( $data, 'tpsm-total-weight' );
        $shipping_callsestItems = $this->dataFilterByConditionName( $data, 'shipping-class' );

        $flat_rate_cost            = $this->get_shipping_cost_for_flat_rate( $flat_rate_items );
        $cart_total_price_cost     = $this->get_shipping_cost_for_total_price( $total_price_items );
        $cart_subtotal_price_cost  = $this->get_shipping_cost_for_subtotal_price( $sub_total_price_items );
        $cart_total_weight_cost    = $this->get_shipping_cost_for_total_weight( $total_weight_items ); 

        $shipping_cost = $flat_rate_cost + $cart_total_price_cost + $cart_subtotal_price_cost + $cart_total_weight_cost;
        
        // Sum all costs
        return $shipping_cost;
    }

    /**
     * Calculate shipping cost based on cart subtotal price.
     *
     * @param array $items Array of items with shipping cost data.
     *
     * @return int Shipping cost.
     */
    private function get_shipping_cost_for_subtotal_price( $items ) {
        $cart = WC()->cart;

        if ( is_null( $cart ) || empty( $items ) ) {
            return;
        }

        $subtotal = WC()->cart->get_subtotal( $items );
        $cost = 0;

        foreach ( $items as $item ) {
            if( $subtotal <= $item['max'] && $subtotal >= $item['min'] ) {
                $cost += $item['cost'];
            }
        }

        return $cost;
    }

    /**
     * Calculate shipping cost based on cart total price.
     *
     * @param array $items Array of items with shipping cost data.
     *
     * @return int Shipping cost.
     */
    private function get_shipping_cost_for_total_price( $items ) {
        
        $cart = WC()->cart;

        if ( is_null( $cart ) || empty( $items ) ) {
            return;
        }

        $total = (float) $cart->get_total( 'edit' );
        $cost = 0;

        foreach ( $items as $item ) {
            if( $total <= $item['max'] && $total >= $item['min'] ) {
                $cost += $item['cost'];
            }
        }

        return $cost;
    }

    /**
     * Calculate shipping cost based on cart total weight.
     *
     * @param array $items Array of items with shipping cost data.
     *
     * @return int Shipping cost.
     */
    private function get_shipping_cost_for_total_weight( $items ) {

        $cart = WC()->cart;

        if ( is_null( $cart ) || empty( $items ) ) {
            return;
        }

        

        $weight = $this->cart_total_product_weights();
        
        $cost = 0;
        foreach ( $items as $item ) {
            if( $weight <= $item['max'] && $weight >= $item['min'] ) {
                $cost += $item['cost'];
            }
        }

        return $cost;
    }

    /**
     * Calculates shipping cost based on flat rate shipping.
     * 
     * @param array $items Array of items with shipping cost data.
     * 
     * @return float Shipping cost.
     */
    private function get_shipping_cost_for_flat_rate( $items ) {

        $cart = WC()->cart;

        if ( is_null( $cart ) || empty( $items ) ) {
            return;
        }

        $costs = array_column( $items, 'cost' );
        $costs = array_map( 'floatval', $costs );

        return array_sum( $costs );
    }

    /**
     * Filters an array of data based on a specified condition.
     *
     * @param array $data The array of data to be filtered.
     * @param string $condition The condition to filter the data by.
     *
     * @return array The filtered array containing only items that match the condition.
     */
    private function dataFilterByConditionName( $data, $condition ) {
        return array_filter( $data, function( $item ) use ( $condition ) {
            return $item['condition'] === $condition;
        });
    }

    /**
     * Calculates total valid weight of products in the cart.
     *
     * Skips products with no weight set (null, empty, or zero).
     *
     * @return float The total weight of all products in the cart.
     */
    private function cart_total_product_weights( ) {
        $cart = WC()->cart;
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

    /**
     * Calculate the total dimension-based shipping fee for the products in the cart.
     *
     * Uses the dimensions of each product to determine applicable fees based on pre-defined settings.
     * Only considers products with valid dimensions (greater than zero).
     *
     * @param WC_Cart $cart The WooCommerce cart object.
     * 
     * @return float The total dimension-based shipping fee for the cart.
     */

    private function cart_total_dimension_fee( $cart ) {
        $tpsm_dimensions_settings = $this->box_shipping_settings['box-shipping'] ?? [];
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

                if( !empty( $tpsm_dimensions_settings ) ) {
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
        }
    
        return $total_fee;
    }
}




