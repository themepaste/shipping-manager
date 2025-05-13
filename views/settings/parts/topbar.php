<?php defined( 'ABSPATH' ) || exit; // Exit if accessed directly ?>
<!-- Top bar wrapper for the Shipping Manager plugin -->
<div class="tpsm-plugin-topbar-wrapper">

    <!-- Logo and title area -->
    <div class="tpsm-logo-title-area">

        <!-- Plugin icon -->
        <div class="tpsm-icons">
            <?php 
                // Set the path for the top bar icon
                $tpsm_icon = TPSM_ASSETS_URL . '/img/logo.png';

                // Output the icon with proper escaping
                printf( '<img src="%1$s" >', esc_url( $tpsm_icon ) );
            ?>
        </div>

        <!-- Plugin title and tagline -->
        <div class="tpsm-titles">
            <h1><?php esc_html_e( 'Shipping Manager', 'shipping-manager' ); // Plugin title ?></h1>
            <p style="margin:0;"><?php esc_html_e( 'One solution for all shipping needs', 'shipping-manager' ); // Plugin tagline ?></p>
        </div>
    </div>

    <!-- Right-aligned topbar info area -->
    <div class="tpsm-topbar-info-area">
        <!-- Link to plugin documentation -->
        <a href="https://themepaste.com/documentation/shipping-manager-documentation" target="_blank">
            <?php esc_html_e( 'Documentation', 'shipping-manager' ); ?>
        </a>
    </div>
</div>

<!-- Add space below the top bar -->
<br>
