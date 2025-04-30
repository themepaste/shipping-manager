<?php defined( 'ABSPATH' ) || exit; ?>

<div class="tpsm-siderbar-wrapper">
    <ul>
        <?php 
            foreach ( $settings_option as $key => $value ) {
                printf( '<li><a class="%1$s" href="%2$s">%3$s</a></li>', 
                    $current_screen == $key ? 'active' : '',
                    esc_url( add_query_arg( 
                        array(
                            'page' => 'shipping-manager',
                            'tpsm-setting' => $key,
                        ),
                        admin_url( 'admin.php' )
                    ) ),
                    esc_html( $value['label'] ),
                );
            }
        ?>
    </ul>
</div>