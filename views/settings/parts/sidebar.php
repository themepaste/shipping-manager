<?php 
    $settings_option = tpsm_settings_options();
    $current_screen  = isset( $_GET['tpsm-setting']) ? sanitize_text_field($_GET['tpsm-setting']) : null;
?>

<div class="tpsm-siderbar-wrapper">
    <ul>
        <?php 
            foreach ( $settings_option as $key => $value ) {
                printf( '<li><a class="%1$s" href="%2$s">%3$s</a></li>', 
                    $current_screen == $key ? 'active' : '',
                    add_query_arg( 
                        array(
                            'page' => 'shipping-manager',
                            'tpsm-setting' => $key,
                        ),
                        admin_url( 'admin.php' )
                    ),
                    $value['label']
                );
            }
        ?>
    </ul>
</div>