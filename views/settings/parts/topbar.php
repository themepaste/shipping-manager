<?php defined( 'ABSPATH' ) || exit; ?>
<div class="tpsm-plugin-topbar-wrapper">
    <div class="tpsm-logo-title-area">
        <div class="tpsm-icons">
            <?php 
                $tpsm_icon = TPSM_ASSETS_URL . '/img/icons/order-fulfillment.png';
                printf( '<img src="%1$s" >', esc_url( $tpsm_icon ) );
            ?>
            
        </div>
        <div class="tpsm-titles">
            <h1><?php esc_html_e( 'Shipping Manager', 'shipping-manager' ); ?></h1>
            <p style="margin:0;"><?php esc_html_e( 'Set up your store global shipping rules', 'shipping-manager' ); ?></p>
        </div>
    </div>
    <div class="tpsm-topbar-info-area">
        <a href="https://themepaste.com/documentation/shipping-manager-documentation" target="_blank"><?php esc_html_e( 'Documentation', 'shipping-manager' ); ?></a>
    </div>
</div>
    
    <br>