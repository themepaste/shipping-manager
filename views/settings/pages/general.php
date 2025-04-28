<?php 
    defined( 'ABSPATH' ) || exit; 

    // $tpsm_box_shipping_settings = get_option( 'tpsm-box-shipping_settings' );
    // $tpsm_box_shipping_settings_values = $tpsm_box_shipping_settings ? $tpsm_box_shipping_settings : [
    //     'enabled'   => 0,
    //     'box-shipping' => []
    // ];

    // $weight_unit        = get_option( 'woocommerce_weight_unit' );
    // $dimension_unit     = get_option( 'woocommerce_dimension_unit' );
    // $currency_symbol    = get_woocommerce_currency_symbol();
?>

<div class="tpsm-setting-wrapper">
    <div class="tpsm-general-settings-wrapper">
        <h2><?php esc_html_e( 'General Settings', 'shipping-manager' ) ?></h2>
        <form method="POST">
            <?php wp_nonce_field( 'tpsm-nonce_action', 'tpsm-nonce_name' ); ?>

            <div class="tpsm-setting-row">
                
                <!-- Switch for enable disable  -->
                <div class="tpsm-field">
                    <div class="tpsm-field-label">
                        <label><?php esc_html_e( 'Method Title', 'shipping-manager' ); ?></label>
                    </div>
                    <div class="tpsm-field-input">
                        <div class="tpsm-switch-wrapper">
                            <input type="text" type="text">
                        </div>
                        <p class="tpsm-field-desc"><?php esc_html_e( 'By default "Shipping Manager"', 'shipping-manager' ); ?></p>
                    </div>
                </div>
            </div>
    
            <div class="tpsm-setting-row">
                
                <!-- Switch for enable disable  -->
                <div class="tpsm-field">
                    <div class="tpsm-field-label">
                        <label><?php esc_html_e( 'Disable/Enable:', 'shipping-manager' ); ?></label>
                    </div>
                    <div class="tpsm-field-input">
                        <div class="tpsm-switch-wrapper">
                            <input class="tpsm-switch" type="checkbox" id="tpsm-box-shipping-disable-enable" name="tpsm-box-shipping-disable-enable" /><label for="tpsm-box-shipping-disable-enable" class="tpsm-switch-label"></label>
                        </div>
                        <p class="tpsm-field-desc"><?php esc_html_e( 'To enable/disable plugin all functionality', 'shipping-manager' ); ?></p>
                    </div>
                </div>
                
                <!-- Taxable Field  -->
                <div class="tpsm-field">
                    <div class="tpsm-field-label">
                        <label><?php esc_html_e( 'Taxable: ', 'shipping-manager' ); ?></label>
                    </div>
                    <div class="tpsm-field-input">
                        <div class="tpsm-switch-wrapper">
                            <select name="" id="">
                                <option value="yes"><?php esc_html_e( 'Yes', 'shipping-manager' ); ?></option>
                                <option value="no"><?php esc_html_e( 'No', 'shipping-manager' ); ?></option>
                            </select>
                        </div>
                        <p class="tpsm-field-desc"><?php esc_html_e( 'Will shipping method taxable or not', 'shipping-manager' ); ?></p>
                    </div>
                </div>

            </div>

            <div class="tpsm-save-button">
                <button type="submit" name="tpsm-box-shipping_submit"><?php esc_html_e( 'Save Settings', 'shipping-manager' ); ?></button>
            </div>
        </form>
    </div>
</div>
