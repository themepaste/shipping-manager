<?php

defined( 'ABSPATH' ) || exit; 

require_once( TPSM_PLUGIN_DIRNAME . '/inc/settings-fields.php' );

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
                // 'shipping-fees' => array(
                //     'label' => __( 'Shipping Fees', 'shipping-manager' ),
                //     'class' => '',
                // ),
                // 'box-shipping' => array(
                //     'label' => __( 'Box Shipping', 'shipping-manager' ),
                //     'class' => '',
                // ),
                'free-shipping' => array(
                    'label' => __( 'Free Shipping', 'shipping-manager' ),
                    'class' => '',
                ),
                'shipping-calculator' => array(
                    'label' => __( 'Shipping Calculator', 'shipping-manager' ),
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
 * Calculate and return available shipping methods for a single product.
 *
 * This function simulates a shipping package using the provided product ID and optional
 * destination details (country, state, postcode, city). It uses WooCommerce's internal
 * shipping method calculations to return a list of available shipping methods based on 
 * the current settings and the destination.
 *
 * @param string|null $country   Optional. Destination country code. Defaults to the customer's shipping country.
 * @param string|null $state     Optional. Destination state code. Defaults to the customer's shipping state.
 * @param string|null $postcode  Optional. Destination postcode. Defaults to the customer's shipping postcode.
 * @param string|null $city      Optional. Destination city. Defaults to the customer's shipping city.
 * @param int|null    $product_id Required. ID of the WooCommerce product.
 *
 * @return array|false Returns an array of available shipping rates or false if not a valid product context.
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

/**
 * Outputs a disabled select dropdown field for the "Taxable" setting in the admin interface.
 *
 * This function generates an HTML select field for the taxable option (Yes/No) and marks the
 * correct value as selected. The field is rendered as disabled, serving as a read-only visual
 * indicator. To modify the value, users are instructed to change it via the Shipping Manager settings.
 *
 * @param string|null $is_taxable Optional. Whether the field is taxable ('yes' or 'no'). Defaults to 'no' if not provided.
 *
 * @return void Outputs HTML directly.
 */
if( ! function_exists( 'tpsm_taxable_field' ) ) {
    function tpsm_taxable_field( $is_taxable = null ) {
        if( is_null( $is_taxable ) ) {
            $is_taxable = 'no';
        }?>
            <div class="tpsm-field">
                <div class="tpsm-field-label">
                    <label><?php esc_html_e( 'Taxable: ', 'shipping-manager' ); ?></label>
                </div>
                <div class="tpsm-field-input">
                    <div class="tpsm-switch-wrapper">
                        <?php 
                            printf(
                                '<select disabled>
                                    <option value="yes" %3$s>%1$s</option>
                                    <option value="no" %4$s>%2$s</option>
                                </select>',
                                esc_html__( 'Yes', 'shipping-manager' ),
                                esc_html__( 'No', 'shipping-manager' ),
                                selected( $is_taxable, 'yes', false ),
                                selected( $is_taxable, 'no', false ),
                            );
                        ?>
                        
                    </div>
                    <p class="tpsm-field-desc"><?php esc_html_e( "'Yes' = Tax Included / 'No' = Tax excluded / Change it from Shipping Manager Settings", 'shipping-manager' ); ?></p>
                </div>
            </div>
        <?php 
    }
}

/**
 * Checks if a value is set and returns it, or returns an empty string if not.
 *
 * This function acts as a safe helper for accessing potentially undefined values.
 * It avoids PHP notices or warnings that may occur when attempting to access
 * unset variables, especially in templating or dynamic settings contexts.
 *
 * @since 1.0.0
 *
 * @param mixed $value The value to check.
 * @return mixed|string Returns the original value if set, or an empty string if not set.
 */
if ( ! function_exists( 'tpsm_isset' ) ) {
    function tpsm_isset( $value ) {
        if ( ! isset( $value ) ) {
            return '';
        }

        return $value;
    }
}

/**
 * Retrieves a list of shipping condition types.
 *
 * @since 1.0.0
 *
 * @return array Associative array with condition types as keys and their labels as values.
 */
if( ! function_exists( 'get_conditions_data' ) ) {
    function get_conditions_data() {
        return [
            'tpsm-flat-rate'            => 'Flat Rate',
            'tpsm-sub-total-price'      => 'Subtotal',
            'tpsm-total-price'          => 'Total',
            'tpsm-per-weight-unit'      => 'Per Weight Unit (' . get_option( 'woocommerce_weight_unit' ) . ')',
            'tpsm-total-weight'         => 'Total Weight',
            'tpsm-shipping-class'       => 'Shipping Class',
        ];
    }
}