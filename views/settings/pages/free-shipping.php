<?php 

defined( 'ABSPATH' ) || exit;

$currency_symbol    = get_woocommerce_currency_symbol();
$screen_slug        = $args['current_screen'];
$settings_fields = [
    'hide-other' => array(
        'label' => __( 'Hide Other', 'shipping-manager' ),
        'type'  => 'switch',
        'value' => '',
    ),  
    'free-shipping-bar' => array(
        'label' => __( 'Show Free Shipping Bar', 'shipping-manager' ),
        'type'  => 'switch',
        'value' => '',
    ),
    'minimum-amount' => array(
        'label' => __( 'Minimum Amount', 'shipping-manager' ),
        'type'  => 'switch',
        'value' => '',
    ),
    'cart-amount' => array(
        'label' => __( 'Cart Amount', 'shipping-manager' ),
        'type'  => 'text',
        'value' => '',
    )
]

?>

<div class="tpsm-setting-wrapper">
    <div class="tpsm-free-shipping-wrapper">
        <h1>Free Shipping Settings</h1>
        <br>
        <form action="">
            <?php wp_nonce_field( 'tpsm-nonce_action', 'tpsm-nonce_name' ); ?>

            <!-- Hide Other shipping when free shipping is avaiable  -->
            <?php 
                foreach ( $settings_fields as $key => $value ) {
                    if( 'switch' == $value['type'] ) {
                        printf(
                            '<div class="tpsm-setting-row">
                                <label>%1$s: </label>
                                <input class="tpsm-switch" type="checkbox" id="%2$s" name="%2$s" /><label for="%2$s" class="tpsm-switch-label"></label>
                            </div>
                            ',
                            $value['label'],
                            'tpsm-' . $screen_slug . '_' . $key
                        );
                    }
                    else if( 'text' == $value['type'] ) {
                        printf(
                            '<div class="tpsm-setting-row">
                                <label>%1$s: </label>
                                <input type="text" id="%2$s" name="%2$s" value="%3$s" />%4$s
                            </div>
                            ',
                            $value['label'],
                            'tpsm-' . $screen_slug . '_' . $key,
                            $value['value'],
                            $currency_symbol
                        );
                    }
                } 
            ?>

            <!-- Show Free Shipping Bar  -->
            <!-- <div class="tpsm-setting-row">
                <label><?php esc_html_e( 'Show Free shipping bar:', 'shipping-manager' ); ?></label>
                <input class="tpsm-switch" type="checkbox" id="tpsm-box-shipping-disable-enable" name="tpsm-box-shipping-disable-enable" /><label for="tpsm-box-shipping-disable-enable" class="tpsm-switch-label"></label>
            </div> -->

            <!-- Minimum Amount to enable free shipping  -->
            <!-- <div class="tpsm-setting-row">
                <label><?php esc_html_e( 'Minimum Amount:', 'shipping-manager' ); ?></label>
                <input class="tpsm-switch" type="checkbox" id="tpsm-box-shipping-disable-enable" name="tpsm-box-shipping-disable-enable" /><label for="tpsm-box-shipping-disable-enable" class="tpsm-switch-label"></label>
            </div> -->

            <!-- <div class="tpsm-setting-row">
                <label><?php esc_html_e( 'Cart Amount:', 'shipping-manager' ); ?></label>
                <input type="text">
            </div> -->
            

            <div class="tpsm-save-button">
                <button type="submit" name="tpsm-box-shipping_submit"><?php esc_html_e( 'Save', 'shipping-manager' ); ?></button>
            </div>
        </form>
    </div>
</div>