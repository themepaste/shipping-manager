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
                'others' => array(
                    'label' => __( 'Others', 'shipping-manager' ),
                    'class' => '',
                ),
            )
        );
    }
}