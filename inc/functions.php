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
                'free-shipping' => array(
                    'label' => __( 'Free Shipping', 'shipping-manager' ),
                    'class' => '',
                ),
                'per-product-shipping' => array(
                    'label' => __( 'Per Product Shipping', 'shipping-manager' ),
                    'class' => '',
                ),
            )
        );
    }
}