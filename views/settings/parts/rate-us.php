<?php defined( 'ABSPATH' ) || exit; ?>

<p class="tpsm-rating-message">
    <?php
    printf(
        /* translators: %1$s: Plugin name, %2$s: Stars, %3$s: Link start tag, %4$s: Link end tag */
        esc_html__( 'If you like %1$s you can rate us %2$s %3$sin plugins repository →%4$s', 'shipping-manager' ),
        '<strong>' . esc_html__( 'Shipping Manager', 'shipping-manager' ) . '</strong>',
        '<span class="tpsm-stars">★★★★★</span>',
        '<strong><a href="' . esc_url( 'https://wordpress.org/plugins/shipping-manager/#reviews' ) . '">',
        '</a></strong>'
    );
    ?>
</p>
