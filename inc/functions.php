<?php
/**
 * Shipping Manager Plugin - Settings and Shipping Methods
 *
 * @package ShippingManager
 */

/**
 * Returns the available settings options for the Shipping Manager plugin.
 *
 * @return array Associative array of settings options.
 */
if ( ! function_exists( 'tpsm_settings_options' ) ) {
    function tpsm_settings_options() {
        return apply_filters(
            'tpsm_settings_options',
            array(
                'shipping-fees' => array(
                    'label' => __( 'Shipping Fees', 'shipping-manager' ),
                    'class' => '',
                ),
                'box-shipping' => array(
                    'label' => __( 'Box Shipping', 'shipping-manager' ),
                    'class' => '',
                ),
                'free-shipping' => array(
                    'label' => __( 'Free Shipping', 'shipping-manager' ),
                    'class' => '',
                ),
                'shipping-calculator' => array(
                    'label' => __( 'Shipping Calculator', 'shipping-manager' ),
                    'class' => '',
                ),
                'distance-shipping' => array(
                    'label' => __( 'Distance Shipping', 'shipping-manager' ),
                    'class' => '',
                ),
            )
        );
    }
}

/**
 * Get settings for Shipping Fees.
 *
 * @return mixed Option value from the database.
 */
if ( ! function_exists( 'tpsm_get_shipping_fees_settings' ) ) {
    function tpsm_get_shipping_fees_settings() {
        return get_option( 'tpsm-shipping-fees_settings' );
    }
}

/**
 * Get settings for Box Shipping.
 *
 * @return mixed Option value from the database.
 */
if ( ! function_exists( 'tpsm_get_box_shipping_settings' ) ) {
    function tpsm_get_box_shipping_settings() {
        return get_option( 'tpsm-box-shipping_settings' );
    }
}

/**
 * Get settings for Free Shipping.
 *
 * @return mixed Option value from the database.
 */
if ( ! function_exists( 'tpsm_get_free_shipping_settings' ) ) {
    function tpsm_get_free_shipping_settings() {
        return get_option( 'tpsm-free-shipping_settings' );
    }
}

/**
 * Calculate and return available shipping methods for a product page.
 *
 * @param string|null $country  Optional. Shipping country.
 * @param string|null $state    Optional. Shipping state.
 * @param string|null $postcode Optional. Shipping postcode.
 * @param string|null $city     Optional. Shipping city.
 *
 * @return array|false List of shipping methods or false if not on product page.
 */
if ( ! function_exists( 'tpsm_get_available_shipping_methods' ) ) {
    function tpsm_get_available_shipping_methods( $country = null, $state = null, $postcode = null, $city = null, $product_id = null ) {
        if ( ! $product_id ) {
            return false;
        }
    
        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return false;
        }
    
        $country  = $country  ?: WC()->customer->get_shipping_country();
        $state    = $state    ?: WC()->customer->get_shipping_state();
        $postcode = $postcode ?: WC()->customer->get_shipping_postcode();
        $city     = $city     ?: WC()->customer->get_shipping_city();
    
        $package = array(
            'contents' => array(
                array(
                    'data'     => $product,
                    'quantity' => 1,
                ),
            ),
            'destination' => array(
                'country'   => $country,
                'state'     => $state,
                'postcode'  => $postcode,
                'city'      => $city,
                'address'   => '',
                'address_2' => '',
            ),
            'user'            => array(),
            'contents_cost'   => $product->get_price(),
            'applied_coupons' => array(),
        );
    
        $shipping = WC_Shipping::instance();
        $shipping->load_shipping_methods();
    
        return $shipping->calculate_shipping_for_package( $package );
    }
}
