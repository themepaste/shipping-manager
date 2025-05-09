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
                            <p style="margin-top: 50px; font-size: 16px;"><?php esc_html_e( 'To Enable this Feature you need to purchase Pro', 'shipping-manager' ); ?></p>
                            <button style="padding:10px 35px; background:#f25500; color: #fff; border:none; cursor:pointer; border-radius: 4px;"><?php esc_html_e( 'Upgrade to Pro', 'shipping-manager' ); ?></button>
                        </div>
                    <?php
                }
                
            }
        }
    ?>

    <?php echo Utility::get_template( 'settings/parts/rate-us.php' ); ?>
</div>