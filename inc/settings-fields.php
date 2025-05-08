<?php 

defined( 'ABSPATH' ) || exit;

if( ! function_exists( 'shipping_calculator_settings_fields' ) ) {
    function shipping_calculator_settings_fields() {
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
