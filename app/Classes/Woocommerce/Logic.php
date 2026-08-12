<?php

namespace ThemePaste\ShippingManager\Classes\Woocommerce;

defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Traits\Asset;
use ThemePaste\ShippingManager\Traits\Hook;

/**
 * Class Logic
 *
 * Handles custom shipping fee calculations based on cart total weight.
 *
 * @package ThemePaste\ShippingManager\Classes\Woocommerce
 */
class Logic {

    use Hook;
    use Asset;

    public $free_shipping_settings;

    /**
     * Cart constructor.
     *
     * Registers necessary filters/hooks.
     */
    public function __construct() {
        $this->free_shipping_settings = tpsm_get_free_shipping_settings();

        new FreeShipping( $this->free_shipping_settings );
        $this->filter( 'tpsm_shipping_fees_cost', [$this, 'shipping_fees_cost'] );
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

        // Guard with OR, not AND: a decoded scalar (e.g. `5`) is not an array
        // but is also not empty, and used to reach array_filter() below, which
        // is a TypeError on PHP 8 during checkout.
        if ( !is_array( $data ) || empty( $data ) ) {
            return 0;
        }

        // Drop anything that is not a well-formed rule row.
        $data = array_filter(
            $data,
            function ( $item ) {
                return is_array( $item ) && isset( $item['condition'] );
            }
        );

        if ( empty( $data ) ) {
            return 0;
        }

        $cart = function_exists( 'WC' ) ? WC()->cart : null;

        if ( is_null( $cart ) ) {
            return 0;
        }

        /**
         * Filter the shipping cost based on cart total price, weight, and shipping classes.
         */
        $flat_rate_items = $this->dataFilterByConditionName( $data, 'tpsm-flat-rate' );
        $tpsm_cart_quantity = $this->dataFilterByConditionName( $data, 'tpsm-cart-quantity' );
        $total_price_items = $this->dataFilterByConditionName( $data, 'tpsm-total-price' );
        $sub_total_price_items = $this->dataFilterByConditionName( $data, 'tpsm-sub-total-price' );
        $per_weight_unit_items = $this->dataFilterByConditionName( $data, 'tpsm-per-weight-unit' );
        $total_weight_items = $this->dataFilterByConditionName( $data, 'tpsm-total-weight' );
        $shipping_classes_items = $this->dataFilterByConditionName( $data, 'tpsm-shipping-class' );

        $flat_rate_cost = $this->get_shipping_cost_for_flat_rate( $flat_rate_items );
        $cart_quantity_cost = $this->get_shipping_cost_cart_quantity( $tpsm_cart_quantity );
        $cart_total_price_cost = $this->get_shipping_cost_for_total_price( $total_price_items );
        $cart_subtotal_price_cost = $this->get_shipping_cost_for_subtotal_price( $sub_total_price_items );
        $per_weight_unit_cost = $this->get_shipping_cost_for_per_weight_unit( $per_weight_unit_items );
        $cart_total_weight_cost = $this->get_shipping_cost_for_total_weight( $total_weight_items );
        $shipping_classes_cost = $this->get_shippng_cost_for_shipping_classes( $shipping_classes_items );

        $shipping_cost = $flat_rate_cost + $cart_quantity_cost + $cart_total_price_cost + $cart_subtotal_price_cost + $cart_total_weight_cost + $per_weight_unit_cost + $shipping_classes_cost;

        // Sum all costs
        return $shipping_cost;
    }

    /**
     * Read a rule value as a float, treating missing/blank values as 0.
     *
     * @param array  $item Rule row.
     * @param string $key  Key to read.
     * @return float
     */
    private function value( $item, $key ) {
        return isset( $item[$key] ) && is_numeric( $item[$key] ) ? (float) $item[$key] : 0.0;
    }

    /**
     * Whether $amount falls inside a rule's min/max range.
     *
     * A blank min or max means "unbounded" in that direction, which is what a
     * merchant expects when they only fill in one side of the range.
     *
     * @param float $amount Value to test.
     * @param array $item   Rule row.
     * @return bool
     */
    private function in_range( $amount, $item ) {
        $min = isset( $item['min'] ) && is_numeric( $item['min'] ) ? (float) $item['min'] : null;
        $max = isset( $item['max'] ) && is_numeric( $item['max'] ) ? (float) $item['max'] : null;

        if ( null !== $min && $amount < $min ) {
            return false;
        }

        if ( null !== $max && $amount > $max ) {
            return false;
        }

        return true;
    }

    private function get_shippng_cost_for_shipping_classes( $items ) {
        if ( empty( $items ) ) {
            return 0;
        }

        $cart_classes = $this->get_unique_shipping_classes_in_cart();
        $cost = 0;

        foreach ( $items as $item ) {
            $selected = isset( $item['multi'] ) && is_array( $item['multi'] ) ? $item['multi'] : [];

            if ( $selected && array_intersect( $selected, $cart_classes ) ) {
                $cost += $this->value( $item, 'cost' );
            }
        }

        return $cost;
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
            return 0;
        }

        $subtotal = (float) $cart->get_subtotal();
        $cost = 0;

        foreach ( $items as $item ) {
            if ( $this->in_range( $subtotal, $item ) ) {
                $cost += $this->value( $item, 'cost' );
            }
        }

        return $cost;
    }

    /**
     * Calculate shipping cost based on cart quantity.
     *
     * @param array $items Array of items with shipping cost data.
     *
     * @return float Shipping cost.
     */
    private function get_shipping_cost_cart_quantity( $items ) {

        $cart = WC()->cart;

        if ( is_null( $cart ) || empty( $items ) ) {
            return 0;
        }

        $total_qty = (float) $cart->get_cart_contents_count();
        $shipping_cost = 0;

        foreach ( $items as $item ) {
            // Rows saved before an operator was picked default to "equals",
            // which is what the rules UI shows for them.
            $operator = isset( $item['equal'] ) && '' !== $item['equal'] ? $item['equal'] : 'equals';
            $value    = $this->value( $item, 'value' );
            $matches  = false;

            switch ( $operator ) {
                case 'equals':
                    $matches = $total_qty === $value;
                    break;
                case 'not-equals':
                    $matches = $total_qty !== $value;
                    break;
                case 'greater':
                    $matches = $total_qty > $value;
                    break;
                case 'less':
                    $matches = $total_qty < $value;
                    break;
                case 'greater-equal':
                    $matches = $total_qty >= $value;
                    break;
                case 'less-equal':
                    $matches = $total_qty <= $value;
                    break;
            }

            if ( $matches ) {
                $shipping_cost += $this->value( $item, 'cost' );
            }
        }

        return $shipping_cost;
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
            return 0;
        }
        $total = (float) $cart->get_cart_contents_total();
        $cost = 0;

        foreach ( $items as $item ) {
            if ( $this->in_range( $total, $item ) ) {
                $cost += $this->value( $item, 'cost' );
            }
        }

        return $cost;
    }

    /**
     * Calculates shipping cost based on cart total weight.
     *
     * @param array $items Array of items with shipping cost data.
     *
     * @return float Shipping cost.
     */
    private function get_shipping_cost_for_per_weight_unit( $items ) {
        $cart = WC()->cart;

        if ( is_null( $cart ) || empty( $items ) ) {
            return 0;
        }

        $weight = $this->cart_total_product_weights();
        $costs = array_map( 'floatval', array_column( $items, 'cost' ) );

        $cost = $weight * array_sum( $costs );

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
            return 0;
        }

        $weight = $this->cart_total_product_weights();

        $cost = 0;
        foreach ( $items as $item ) {
            if ( $this->in_range( $weight, $item ) ) {
                $cost += $this->value( $item, 'cost' );
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
            return 0;
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
        return array_filter( $data, function ( $item ) use ( $condition ) {
            return isset( $item['condition'] ) && $item['condition'] === $condition;
        } );
    }

    /**
     * Calculates total valid weight of products in the cart.
     *
     * Skips products with no weight set (null, empty, or zero).
     *
     * @return float The total weight of all products in the cart.
     */
    private function cart_total_product_weights() {
        $cart = WC()->cart;
        $total_weight = 0;

        foreach ( $cart->get_cart() as $cart_item ) {
            $product = $cart_item['data'];
            $quantity = $cart_item['quantity'];
            $weight = $product->get_weight();

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
        // Read from the stored option: $this->box_shipping_settings was never
        // assigned, so this used to touch an undefined property.
        $box_shipping_settings    = tpsm_get_box_shipping_settings();
        $tpsm_dimensions_settings = is_array( $box_shipping_settings ) && isset( $box_shipping_settings['box-shipping'] )
            ? $box_shipping_settings['box-shipping']
            : [];
        $total_fee = 0;

        foreach ( $cart->get_cart() as $cart_item ) {
            $product = $cart_item['data'];
            $quantity = $cart_item['quantity'];
            $length = floatval( $product->get_length() );
            $width = floatval( $product->get_width() );
            $height = floatval( $product->get_height() );
            $fee = 0;

            // Check if all dimensions are valid (greater than zero)
            if ( $length > 0 && $width > 0 && $height > 0 ) {

                if ( !empty( $tpsm_dimensions_settings ) ) {
                    foreach ( $tpsm_dimensions_settings as $value ) {
                        $tpsm_length = $value['length'];
                        $tpsm_width = $value['width'];
                        $tpsm_height = $value['height'];

                        if ( $length <= $tpsm_length && $width <= $tpsm_width && $height <= $tpsm_height ) {
                            $fee = $value['fee'];
                            break;
                        }
                    }
                    $total_fee += $fee * $quantity;
                }
            }
        }

        return $total_fee;
    }

    /**
     * Get all unique shipping classes in the cart.
     *
     * Loops through all cart items and gets their shipping class IDs.
     * Then, it gets the shipping class object for each ID and adds the slug to an array.
     * Finally, it returns the array of unique shipping classes.
     *
     * @return string[] An array of unique shipping classes in the cart.
     */
    private function get_unique_shipping_classes_in_cart() {

        $cart = WC()->cart;

        if ( is_null( $cart ) ) {
            return [];
        }

        $cart_items = $cart->get_cart();
        $shipping_classes = [];

        foreach ( $cart_items as $cart_item ) {
            $product = $cart_item['data'];

            if ( $product->needs_shipping() ) {
                $shipping_class_id = $product->get_shipping_class_id();

                if ( $shipping_class_id ) {
                    $shipping_class = get_term( $shipping_class_id, 'product_shipping_class' );

                    if ( $shipping_class && !is_wp_error( $shipping_class ) ) {
                        $shipping_classes[] = $shipping_class->slug;
                    }
                }
            }
        }

        // Return unique shipping classes
        return array_unique( $shipping_classes );
    }
}