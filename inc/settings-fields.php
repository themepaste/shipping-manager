<?php 

defined( 'ABSPATH' ) || exit;

if( ! function_exists( 'tpsm_general_settings_fields' ) ) {
    function tpsm_general_settings_fields() {
        return [
            'method-title' => array(
                'label' => __( 'Method Title', 'shipping-manager' ),
                'type'  => 'text',
                'value' => '',
                'desc'  => __( 'By default "Shipping Manager"', 'shipping-manager' ),
            ),
            'is-plugin-enable' => array(
                'label' => __( 'Disable/Enable', 'shipping-manager' ),
                'type'  => 'switch',
                'value' => '',
                'desc'  => __( 'To enable/disable method. By deafult enabled', 'shipping-manager' ),
            ),
            'is-plugin-taxable' => array(
                'label' => __( 'Taxable', 'shipping-manager' ),
                'type'  => 'select',
                'value' => '',
                'desc'  => __( 'Will shipping method taxable or not', 'shipping-manager' ),
            ),
        ];
    }
}

if( ! function_exists( 'tpsm_free_shipping_settings_fields' ) ) {
    function tpsm_free_shipping_settings_fields() {
        return [
            'hide-other' => array(
                'label' => __( 'Hide Other', 'shipping-manager' ),
                'type'  => 'switch',
                'value' => '',
                'desc'  => __( 'Hide other shipping methods while free shipping is available.', 'shipping-manager' ),
            ), 
            'minimum-amount' => array(
                'label' => __( 'Minimum Amount', 'shipping-manager' ),
                'type'  => 'switch',
                'value' => '',
                'desc'  => __( 'This will enable a custom minimum amount for free shipping. Otherwise it will use default minimum amount set in WooCommerce free shipping.', 'shipping-manager' ),
            ),
            'cart-amount' => array(
                'label' => __( 'Cart Amount', 'shipping-manager' ),
                'type'  => 'text',
                'value' => '',
                'desc'  => __( 'Cart minimum amount for free shipping.', 'shipping-manager' ),
            ), 
            'free-shipping-bar' => array(
                'label' => __( 'Show Free Shipping Progress Bar', 'shipping-manager' ),
                'type'  => 'switch',
                'value' => '',
                'desc'  => __( 'Enable free shipping bar for customers. Customers will see a bar for target to achieve free shipping.', 'shipping-manager' ),
                'child-fields' => [
                    'shipping-bar-message' => array(
                        'label' => __( 'Message', 'shipping-manager' ),
                        'type'  => 'text',
                        'value' => '',
                        'desc'  => __( 'To show how many prie left to qualify free shipment than please use [left_price] as placeholder.', 'shipping-manager' ),
                    ),
                    'shipping-bar-position' => array(
                        'label' => __( 'Position', 'shipping-manager' ),
                        'type'  => 'select',
                        'value' => '',
                        'desc'  => __( 'Position', 'shipping-manager' ),
                        'options' => [
                            'Bottom',
                            'Top'
                        ]
                    ),
                    'shipping-bar-alignment' => array(
                        'label' => __( 'Alignment', 'shipping-manager' ),
                        'type'  => 'select',
                        'value' => '',
                        'desc'  => __( 'Alignment', 'shipping-manager' ),
                        'options' => [
                            'Left',
                            'Center',
                            'Right'
                        ]
                    ),
                    'shipping-bar-text-color' => array(
                        'label' => __( 'Text Color', 'shipping-manager' ),
                        'type'  => 'picker',
                        'value' => '',
                        'desc'  => __( 'Text color', 'shipping-manager' ),
                    ),
                    'shipping-bar-background-color' => array(
                        'label' => __( 'Background Color', 'shipping-manager' ),
                        'type'  => 'picker',
                        'value' => '',
                        'desc'  => __( 'Background Color', 'shipping-manager' ),
                    ),
                ]
            ),
        ];
    }
}

if( ! function_exists( 'tpsm_shipping_calculator_settings_fields' ) ) {
    function tpsm_shipping_calculator_settings_fields() {
        return [
            'shipping-calculator-enable' => array(
                'label' => __( 'Disable/Enable', 'shipping-manager' ),
                'type'  => 'switch',
                'value' => '',
                'desc'  => __( 'To enable/disable features. By deafult enabled', 'shipping-manager' ),
            ),
            'enable-location-field' => array(
                'label' => __( 'Off Location form', 'shipping-manager' ),
                'type'  => 'switch',
                'value' => '',
                'desc'  => __( 'To enable/disable features. By deafult enabled', 'shipping-manager' ),
            ),
            'shipping-calculator-position' => array(
                'label' => __( 'Position', 'shipping-manager' ),
                'type'  => 'select',
                'value' => '',
                'desc'  => __( 'Placehment of shipping calculator', 'shipping-manager' ),
                'options' => [
                    'before-add-to-cart-button' => 'Before Add to Cart buton',
                    'after-add-to-cart-button'  => 'After Add to Cart buton',
                    'using-shortcode'           => 'Using Shortcode',
                ],
            ),
        ];
    }
}
