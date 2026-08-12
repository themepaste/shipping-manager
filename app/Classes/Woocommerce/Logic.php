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
        $this->filter( 'tpsm_shipping_fees_cost', [$this, 'shipping_fees_cost'], 10, 2 );
    }

    /**
     * Calculate shipping cost based on cart total price, weight, and shipping classes.
     *
     * @param array $data Shipping fees data.
     *
     * @return float Shipping cost.
     */
    public function shipping_fees_cost( $data, $package = array() ) {

        if ( is_string( $data ) ) {
            $data = json_decode( $data, true );
        }

        // Guard with OR, not AND: a decoded scalar (e.g. `5`) is not an array
        // but is also not empty, and used to reach array_filter() below, which
        // is a TypeError on PHP 8 during checkout.
        if ( !is_array( $data ) || empty( $data ) ) {
            return 0;
        }

        // Drop malformed rows, and rows the merchant has switched off. Rows
        // saved before per-rule toggles existed have no 'enabled' key and are
        // treated as on.
        $data = array_filter(
            $data,
            function ( $item ) {
                if ( !is_array( $item ) || !isset( $item['condition'] ) ) {
                    return false;
                }

                return !array_key_exists( 'enabled', $item ) || (bool) $item['enabled'];
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
        $per_item_items = $this->dataFilterByConditionName( $data, 'tpsm-per-item' );
        $line_item_items = $this->dataFilterByConditionName( $data, 'tpsm-line-items' );
        $category_items = $this->dataFilterByConditionName( $data, 'tpsm-product-category' );
        $postcode_items = $this->dataFilterByConditionName( $data, 'tpsm-postcode' );
        $tag_items = $this->dataFilterByConditionName( $data, 'tpsm-product-tag' );
        $product_items = $this->dataFilterByConditionName( $data, 'tpsm-product' );
        $volume_items = $this->dataFilterByConditionName( $data, 'tpsm-cart-volume' );
        $state_items = $this->dataFilterByConditionName( $data, 'tpsm-state' );
        $coupon_items = $this->dataFilterByConditionName( $data, 'tpsm-coupon' );

        $shipping_cost = $this->get_shipping_cost_for_flat_rate( $flat_rate_items )
            + $this->get_shipping_cost_cart_quantity( $tpsm_cart_quantity )
            + $this->get_shipping_cost_for_total_price( $total_price_items )
            + $this->get_shipping_cost_for_subtotal_price( $sub_total_price_items )
            + $this->get_shipping_cost_for_per_weight_unit( $per_weight_unit_items )
            + $this->get_shipping_cost_for_total_weight( $total_weight_items )
            + $this->get_shippng_cost_for_shipping_classes( $shipping_classes_items )
            + $this->get_shipping_cost_for_per_item( $per_item_items )
            + $this->get_shipping_cost_for_line_items( $line_item_items )
            + $this->get_shipping_cost_for_product_categories( $category_items )
            + $this->get_shipping_cost_for_postcode( $postcode_items, $package )
            + $this->get_shipping_cost_for_product_tags( $tag_items )
            + $this->get_shipping_cost_for_products( $product_items )
            + $this->get_shipping_cost_for_cart_volume( $volume_items )
            + $this->get_shipping_cost_for_state( $state_items, $package )
            + $this->get_shipping_cost_for_coupons( $coupon_items, $package );

        // Sum all costs
        return $shipping_cost;
    }

    /**
     * Apply a cost when the cart holds a product carrying a selected tag.
     *
     * @param array $items Rule rows.
     * @return float
     */
    private function get_shipping_cost_for_product_tags( $items ) {
        if ( empty( $items ) ) {
            return 0;
        }

        $cart_tags = $this->get_unique_terms_in_cart( 'product_tag' );
        $cost      = 0;

        foreach ( $items as $item ) {
            $selected = isset( $item['multi'] ) && is_array( $item['multi'] ) ? $item['multi'] : [];

            if ( $selected && array_intersect( $selected, $cart_tags ) ) {
                $cost += $this->value( $item, 'cost' );
            }
        }

        return $cost;
    }

    /**
     * Apply a cost when the cart holds one of the selected products.
     *
     * Matches on the variation ID as well as the parent product ID, so a rule
     * can target either a whole product or one specific variation.
     *
     * @param array $items Rule rows.
     * @return float
     */
    private function get_shipping_cost_for_products( $items ) {
        $cart = WC()->cart;

        if ( is_null( $cart ) || empty( $items ) ) {
            return 0;
        }

        $cart_ids = [];

        foreach ( $cart->get_cart() as $cart_item ) {
            if ( !empty( $cart_item['product_id'] ) ) {
                $cart_ids[] = (int) $cart_item['product_id'];
            }
            if ( !empty( $cart_item['variation_id'] ) ) {
                $cart_ids[] = (int) $cart_item['variation_id'];
            }
        }

        $cart_ids = array_unique( $cart_ids );
        $cost     = 0;

        foreach ( $items as $item ) {
            $selected = isset( $item['multi'] ) && is_array( $item['multi'] ) ? $item['multi'] : [];
            $selected = array_map( 'intval', $selected );

            if ( $selected && array_intersect( $selected, $cart_ids ) ) {
                $cost += $this->value( $item, 'cost' );
            }
        }

        return $cost;
    }

    /**
     * Match the combined volume of the cart against a range.
     *
     * Volume is length x width x height x quantity, in the store's dimension
     * unit. Products missing any dimension contribute nothing.
     *
     * @param array $items Rule rows.
     * @return float
     */
    private function get_shipping_cost_for_cart_volume( $items ) {
        $cart = WC()->cart;

        if ( is_null( $cart ) || empty( $items ) ) {
            return 0;
        }

        $volume = 0;

        foreach ( $cart->get_cart() as $cart_item ) {
            $product = isset( $cart_item['data'] ) ? $cart_item['data'] : null;

            if ( !$product ) {
                continue;
            }

            $length = (float) $product->get_length();
            $width  = (float) $product->get_width();
            $height = (float) $product->get_height();

            if ( $length > 0 && $width > 0 && $height > 0 ) {
                $quantity = isset( $cart_item['quantity'] ) ? (float) $cart_item['quantity'] : 1;
                $volume  += $length * $width * $height * $quantity;
            }
        }

        $cost = 0;

        foreach ( $items as $item ) {
            if ( $this->in_range( $volume, $item ) ) {
                $cost += $this->value( $item, 'cost' );
            }
        }

        return $cost;
    }

    /**
     * Apply a cost when the destination state matches.
     *
     * @param array $items   Rule rows.
     * @param array $package Shipping package.
     * @return float
     */
    private function get_shipping_cost_for_state( $items, $package ) {
        if ( empty( $items ) ) {
            return 0;
        }

        $state = isset( $package['destination']['state'] ) ? strtoupper( trim( $package['destination']['state'] ) ) : '';

        if ( '' === $state ) {
            return 0;
        }

        $cost = 0;

        foreach ( $items as $item ) {
            $wanted = $this->split_list( isset( $item['value'] ) ? $item['value'] : '' );

            if ( in_array( $state, $wanted, true ) ) {
                $cost += $this->value( $item, 'cost' );
            }
        }

        return $cost;
    }

    /**
     * Apply a cost when one of the listed coupons is on the order.
     *
     * @param array $items   Rule rows.
     * @param array $package Shipping package.
     * @return float
     */
    private function get_shipping_cost_for_coupons( $items, $package ) {
        if ( empty( $items ) ) {
            return 0;
        }

        $applied = isset( $package['applied_coupons'] ) && is_array( $package['applied_coupons'] )
            ? $package['applied_coupons']
            : ( WC()->cart ? WC()->cart->get_applied_coupons() : [] );

        if ( empty( $applied ) ) {
            return 0;
        }

        $applied = array_map( 'strtoupper', array_map( 'trim', $applied ) );
        $cost    = 0;

        foreach ( $items as $item ) {
            $wanted = $this->split_list( isset( $item['value'] ) ? $item['value'] : '' );

            if ( $wanted && array_intersect( $wanted, $applied ) ) {
                $cost += $this->value( $item, 'cost' );
            }
        }

        return $cost;
    }

    /**
     * Split a comma/whitespace separated list into upper-case tokens.
     *
     * @param string $list Raw list.
     * @return string[]
     */
    private function split_list( $list ) {
        return preg_split( '/[\s,]+/', strtoupper( trim( (string) $list ) ), -1, PREG_SPLIT_NO_EMPTY ) ?: [];
    }

    /**
     * Multiply the cost by the number of items in the cart.
     *
     * @param array $items Rule rows.
     * @return float
     */
    private function get_shipping_cost_for_per_item( $items ) {
        $cart = WC()->cart;

        if ( is_null( $cart ) || empty( $items ) ) {
            return 0;
        }

        $quantity = (float) $cart->get_cart_contents_count();
        $costs    = array_map( 'floatval', array_column( $items, 'cost' ) );

        return $quantity * array_sum( $costs );
    }

    /**
     * Compare the number of distinct line items in the cart.
     *
     * @param array $items Rule rows.
     * @return float
     */
    private function get_shipping_cost_for_line_items( $items ) {
        $cart = WC()->cart;

        if ( is_null( $cart ) || empty( $items ) ) {
            return 0;
        }

        $line_count = (float) count( $cart->get_cart() );
        $cost       = 0;

        foreach ( $items as $item ) {
            if ( $this->compare( $line_count, $item ) ) {
                $cost += $this->value( $item, 'cost' );
            }
        }

        return $cost;
    }

    /**
     * Apply a cost when the cart holds a product from a selected category.
     *
     * @param array $items Rule rows.
     * @return float
     */
    private function get_shipping_cost_for_product_categories( $items ) {
        if ( empty( $items ) ) {
            return 0;
        }

        $cart_categories = $this->get_unique_product_categories_in_cart();
        $cost            = 0;

        foreach ( $items as $item ) {
            $selected = isset( $item['multi'] ) && is_array( $item['multi'] ) ? $item['multi'] : [];

            if ( $selected && array_intersect( $selected, $cart_categories ) ) {
                $cost += $this->value( $item, 'cost' );
            }
        }

        return $cost;
    }

    /**
     * Apply a cost when the destination postcode matches.
     *
     * @param array $items   Rule rows.
     * @param array $package Shipping package, for its destination.
     * @return float
     */
    private function get_shipping_cost_for_postcode( $items, $package ) {
        if ( empty( $items ) ) {
            return 0;
        }

        $postcode = isset( $package['destination']['postcode'] ) ? $package['destination']['postcode'] : '';

        if ( '' === $postcode ) {
            return 0;
        }

        $cost = 0;

        foreach ( $items as $item ) {
            $patterns = isset( $item['value'] ) ? (string) $item['value'] : '';

            if ( $this->postcode_matches( $postcode, $patterns ) ) {
                $cost += $this->value( $item, 'cost' );
            }
        }

        return $cost;
    }

    /**
     * Match a postcode against a comma or newline separated pattern list.
     *
     * Supports the same shorthand WooCommerce uses for shipping zones:
     * exact values, `*` wildcards (e.g. `SW1*`) and numeric ranges
     * (e.g. `1000...2000`).
     *
     * @param string $postcode Destination postcode.
     * @param string $patterns Pattern list.
     * @return bool
     */
    private function postcode_matches( $postcode, $patterns ) {
        $patterns = preg_split( '/[\s,]+/', strtoupper( trim( $patterns ) ), -1, PREG_SPLIT_NO_EMPTY );

        if ( empty( $patterns ) ) {
            return false;
        }

        $postcode = strtoupper( str_replace( ' ', '', $postcode ) );

        foreach ( $patterns as $pattern ) {
            // Numeric range: 1000...2000
            if ( false !== strpos( $pattern, '...' ) ) {
                list( $from, $to ) = array_pad( explode( '...', $pattern, 2 ), 2, '' );

                if ( is_numeric( $from ) && is_numeric( $to ) && is_numeric( $postcode )
                    && (float) $postcode >= (float) $from && (float) $postcode <= (float) $to ) {
                    return true;
                }
                continue;
            }

            // Wildcard: SW1*
            if ( false !== strpos( $pattern, '*' ) ) {
                $regex = '/^' . str_replace( '\*', '.*', preg_quote( $pattern, '/' ) ) . '$/';

                if ( preg_match( $regex, $postcode ) ) {
                    return true;
                }
                continue;
            }

            if ( $pattern === $postcode ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Compare a value against a rule's operator and value.
     *
     * @param float $actual Value from the cart.
     * @param array $item   Rule row.
     * @return bool
     */
    private function compare( $actual, $item ) {
        $operator = isset( $item['equal'] ) && '' !== $item['equal'] ? $item['equal'] : 'equals';
        $expected = $this->value( $item, 'value' );

        switch ( $operator ) {
            case 'equals':
                return $actual === $expected;
            case 'not-equals':
                return $actual !== $expected;
            case 'greater':
                return $actual > $expected;
            case 'less':
                return $actual < $expected;
            case 'greater-equal':
                return $actual >= $expected;
            case 'less-equal':
                return $actual <= $expected;
        }

        return false;
    }

    /**
     * Unique product category slugs present in the cart.
     *
     * @return string[]
     */
    private function get_unique_product_categories_in_cart() {
        return $this->get_unique_terms_in_cart( 'product_cat' );
    }

    /**
     * Unique term slugs of a taxonomy present across the cart's products.
     *
     * @param string $taxonomy Taxonomy name.
     * @return string[]
     */
    private function get_unique_terms_in_cart( $taxonomy ) {
        $cart = WC()->cart;

        if ( is_null( $cart ) ) {
            return [];
        }

        $slugs = [];

        foreach ( $cart->get_cart() as $cart_item ) {
            $product = isset( $cart_item['data'] ) ? $cart_item['data'] : null;

            if ( !$product ) {
                continue;
            }

            // Variations inherit categories and tags from the parent product.
            $product_id = $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id();
            $terms      = get_the_terms( $product_id, $taxonomy );

            if ( !$terms || is_wp_error( $terms ) ) {
                continue;
            }

            foreach ( $terms as $term ) {
                $slugs[] = $term->slug;
            }
        }

        return array_unique( $slugs );
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
            if ( $this->compare( $total_qty, $item ) ) {
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