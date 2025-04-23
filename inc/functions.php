<?php 

/**
 * Define @shipping manager plugin all settings option 
 * 
 * @return setting_options
 */
if( ! function_exists( 'tpsm_settings_options' ) ) {
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
                'others' => array(
                    'label' => __( 'Others', 'shipping-manager' ),
                    'class' => '',
                ),
            )
        );
    }
}

if( !function_exists( 'tpsm_get_shipping_fees_settings' ) ) {
    function tpsm_get_shipping_fees_settings() {
        return get_option( 'tpsm-shipping-fees_settings' );
    }
}

if( !function_exists( 'tpsm_get_box_shipping_settings' ) ) {
    function tpsm_get_box_shipping_settings() {
        return get_option( 'tpsm-box-shipping_settings' );
    }
}

if( !function_exists( 'tpsm_get_free_shipping_settings' ) ) {
    function tpsm_get_free_shipping_settings() {
        return get_option( 'tpsm-free-shipping_settings' );
    }
}