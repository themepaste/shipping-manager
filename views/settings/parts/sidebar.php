<?php 
    $settings_option = tpsm_settings_options();
?>
<div class="tpsm-siderbar-wrapper">
    <ul>
        <?php 
            foreach ( $settings_option as $key => $value ) {
                printf( '<li><a href="%1$s">%2$s</a></li>', 
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
        <!-- <li><a class="active" href="#">Shipping Fees</a></li>
        <li><a href="#">Free Shipping</a></li>
        <li><a href="#">Per Product Shipping</a></li> -->
    </ul>
</div>