<?php

use ThemePaste\ShippingManager\Helpers\Utility;

 defined( 'ABSPATH' ) || exit; ?>

<div class="tpsm-main-wrapper">
    <?php 
        foreach ( $settings_option as $key => $value ) {
            if( $current_screen == $key ) {
                if( Utility::get_template( 'settings/pages/' . $key . '.php' ) ) {
                    printf( Utility::get_template( 'settings/pages/' . $key . '.php' ) );
                }else {
                    echo esc_html_e( "Load From pro" , 'shiping-manager' );
                }
            }
        }
    ?>
</div>