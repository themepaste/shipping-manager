<?php
defined( 'ABSPATH' ) || exit; 
use ThemePaste\ShippingManager\Helpers\Utility;
?>

<div class="tpsm-main-wrapper">
    <?php 
        foreach ( $settings_option as $key => $value ) {
            if( $current_screen == $key ) {
                $template = Utility::get_template( 'settings/pages/' . $key . '.php', $args );
                if( $template ) {
                    echo $template;
                } else {
                    ?>
                        <div style="text-align: center;">
                            <button style="padding:10px 35px; background:#f25500; color: #fff; border:none; cursor:pointer; border-radius: 4px; ">Upgrade to Pro</button>
                        </div>
                    <?php
                }
                
            }
        }
    ?>
</div>