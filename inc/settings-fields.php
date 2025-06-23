<?php
/**
 * Shipping Manager Settings Fields
 *
 * @package ShippingManager
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'tpsm_general_settings_fields' ) ) {
    /**
     * General Settings Fields
     *
     * @return array
     */
    function tpsm_general_settings_fields() {
        return array(
            'method-title' => array(
                'label' => __( 'Method Title', 'shipping-manager' ),
                'type'  => 'text',
                'value' => '',
                'desc'  => __( 'Default Title: "Shipping Manager"', 'shipping-manager' ),
            ),
            'is-plugin-enable' => array(
                'label' => __( 'Disable/Enable', 'shipping-manager' ),
                'type'  => 'switch',
                'value' => '',
                'desc'  => __( 'Enable or disable all shipping methods site-wide.', 'shipping-manager' ),
            )
        );
    }
}

if ( ! function_exists( 'tpsm_free_shipping_settings_fields' ) ) {
    /**
     * Free Shipping Settings Fields
     *
     * @return array
     */
    function tpsm_free_shipping_settings_fields() {
        return array(
            'hide-other' => array(
                'label' => __( 'Hide Other', 'shipping-manager' ),
                'type'  => 'switch',
                'value' => '',
                'desc'  => __( 'Hide other shipping methods when Free shipping is available.', 'shipping-manager' ),
            ),
            'minimum-amount' => array(
                'label' => __( 'Minimum Amount', 'shipping-manager' ),
                'type'  => 'switch',
                'value' => '',
                'desc'  => __( 'Enable custom minimum amount for free shipping. When disabled, standard WooCommerce settings will apply.', 'shipping-manager' ),
            ),
            'cart-amount' => array(
                'label' => __( 'Cart Amount', 'shipping-manager' ),
                'type'  => 'text',
                'value' => '',
                'desc'  => __( 'Cart minimum amount required for Free shipping.', 'shipping-manager' ),
            ),
            'free-shipping-bar' => array(
                'label' => __( 'Show Free Shipping Progress Bar', 'shipping-manager' ),
                'type'  => 'switch',
                'value' => '',
                'desc'  => __( 'Enable a Progress bar showing customers their Progress towards free shipping.', 'shipping-manager' ),
                'child-fields' => array(
                    'shipping-bar-message' => array(
                        'label' => __( 'Message', 'shipping-manager' ),
                        'type'  => 'text',
                        'value' => '',
                        'desc'  => __( 'Use [left_price] as a placeholder to show the remaining amount to qualify for free shipping.', 'shipping-manager' ),
                    ),
                    'shipping-bar-position' => array(
                        'label'   => __( 'Position', 'shipping-manager' ),
                        'type'    => 'select',
                        'value'   => '',
                        'desc'    => __( 'Position of the Progress bar.', 'shipping-manager' ),
                        'options' => array(
                            'Bottom' => __( 'Bottom', 'shipping-manager' ),
                            'Top'    => __( 'Top', 'shipping-manager' ),
                        ),
                    ),
                    'shipping-bar-alignment' => array(
                        'label'   => __( 'Alignment', 'shipping-manager' ),
                        'type'    => 'select',
                        'value'   => '',
                        'desc'    => __( 'Alignment of the Progress bar.', 'shipping-manager' ),
                        'options' => array(
                            'Left'   => __( 'Left', 'shipping-manager' ),
                            'Center' => __( 'Center', 'shipping-manager' ),
                            'Right'  => __( 'Right', 'shipping-manager' ),
                        ),
                    ),
                    'shipping-bar-text-color' => array(
                        'label' => __( 'Text Color', 'shipping-manager' ),
                        'type'  => 'picker',
                        'value' => '',
                        'desc'  => __( 'Text color of the Progress bar.', 'shipping-manager' ),
                    ),
                    'shipping-bar-background-color' => array(
                        'label' => __( 'Background Color', 'shipping-manager' ),
                        'type'  => 'picker',
                        'value' => '',
                        'desc'  => __( 'Background color of the Progress bar.', 'shipping-manager' ),
                    ),
                ),
            ),
        );
    }
}

if ( ! function_exists( 'tpsm_shipping_calculator_settings_fields' ) ) {
    /**
     * Shipping Calculator Settings Fields
     *
     * @return array
     */
    function tpsm_shipping_calculator_settings_fields() {
        return array(
            'shipping-calculator-enable' => array(
                'label' => __( 'Disable/Enable', 'shipping-manager' ),
                'type'  => 'switch',
                'value' => '',
                'desc'  => __( 'Enable or disable the shipping calculator. Default: enabled.', 'shipping-manager' ),
            ),
            'enable-location-field' => array(
                'label' => __( 'Location Form', 'shipping-manager' ),
                'type'  => 'switch',
                'value' => '',
                'desc'  => __( 'Enable or disable the location form. Default: enabled.', 'shipping-manager' ),
            ),
            'shipping-calculator-position' => array(
                'label'   => __( 'Position', 'shipping-manager' ),
                'type'    => 'select',
                'value'   => '',
                'desc'    => __( 'Placement of the shipping calculator.', 'shipping-manager' ),
                'options' => array(
                    'before-add-to-cart-button' => __( 'Before Add to Cart button', 'shipping-manager' ),
                    'after-add-to-cart-button'  => __( 'After Add to Cart button', 'shipping-manager' ),
                    'using-shortcode'           => __( '[tpsm-shipping-calculator/] Using Shortcode', 'shipping-manager' ),
                ),
            ),
        );
    }
}
