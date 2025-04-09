<?php

use ThemePaste\ShippingManager\Helpers\Utility;

 defined( 'ABSPATH' ) || exit; ?>

<div class="tpsm-main-wrapper">
    <?php 
        foreach ( $settings_option as $key => $value ) {
            if( $current_screen == $key ) {
                echo Utility::get_template( 'settings/pages/' . $key . '.php' );
            }
        }
    ?>
</div>