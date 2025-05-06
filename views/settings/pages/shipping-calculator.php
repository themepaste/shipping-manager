<?php 

defined( 'ABSPATH' ) || exit; 

$prefix             = 'tpsm';
$screen_slug        = $args['current_screen'];
$is_taxable         = $args['general_settings']['is-plugin-taxable'];
$submit_button      = $prefix . '-' . $screen_slug . '_submit';
$option_name        = $prefix . '-' . $screen_slug . '_' . 'settings';
$saved_settings     = get_option( $option_name );


$shipping_calculator_settings_fields = [
    'shipping-calculator-enable' => array(
        'label' => __( 'Disable/Enable', 'shipping-manager' ),
        'type'  => 'switch',
        'value' => '',
        'desc'  => __( 'To enable/disable features. By deafult enabled', 'shipping-manager' ),
    ),
]


?>

<div class="tpsm-setting-wrapper">
    <div class="tpsm-free-shipping-wrapper">
        <!-- Settings Title -->
        <h2><?php esc_html_e( 'Shipping Calculator Settings', 'shipping-manager' ) ?></h2>
        <form method="POST">
            <?php wp_nonce_field( 'tpsm-nonce_action', 'tpsm-nonce_name' ); ?>
            

            <!-- Switch for enable disable  -->
            <div class="tpsm-setting-row">
                <div class="tpsm-field">
                    <div class="tpsm-field-label">
                        <label><?php esc_html_e( 'Disable/Enable:', 'shipping-manager' ); ?></label>
                    </div>
                    <div class="tpsm-field-input">
                        <div class="tpsm-switch-wrapper">
                            <input class="tpsm-switch" type="checkbox" id="tpsm-shipping-calculator-disable-enable" name="tpsm-shipping-calculator-disable-enable" /><label for="tpsm-shipping-calculator-disable-enable" class="tpsm-switch-label"></label>
                        </div>
                        <p class="tpsm-field-desc"><?php esc_html_e( 'To enable/disable this feature.', 'shipping-manager' ); ?></p>
                    </div>
                </div>
                
                <!-- Taxable Field  -->
                <?php tpsm_taxable_field( $is_taxable ); ?>

            </div>


            <div class="tpsm-save-button">
                <button type="submit" name="<?php echo esc_attr( $submit_button ); ?>"><?php esc_html_e( 'Save Settings', 'shipping-manager' ); ?></button>
            </div>
        </form>
    </div>
</div>