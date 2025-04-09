<?php

use ThemePaste\ShippingManager\Helpers\Utility;

 defined( 'ABSPATH' ) || exit; ?>

<div class="tpsm-main-wrapper">
    <?php 
        foreach ( $settings_option as $key => $value ) {
            if( $current_screen == $key ) {
                $template = Utility::get_template( 'settings/pages/' . $key . '.php' );
                if( $template ) {
                    echo $template;
                } else {
                    esc_html_e( "Load From pro" , 'shipping-manager' );
                }
                
            }
        }
    ?>
</div>