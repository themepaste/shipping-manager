<?php 
    defined( 'ABSPATH' ) || exit; 

    $prefix             = 'tpsm';
    $currency_symbol    = get_woocommerce_currency_symbol();
    $screen_slug        = $args['current_screen'];
    $submit_button      = $prefix . '-' . $screen_slug . '_submit';
    $option_name        = $prefix . '-' . $screen_slug . '_' . 'settings';
    $saved_settings     = get_option( $option_name );
    $parent_field_key   = 'minimum-amount'; //It has a child field called "Cart Amount"

    $general_settings_fields = [
        'method-title' => array(
            'label' => __( 'Method Title', 'shipping-manager' ),
            'type'  => 'text',
            'value' => '',
            'desc'  => __( 'By default "Shipping Manager"', 'shipping-manager' ),
        ),
        'is-plugin-enable' => array(
            'label' => __( 'Disable/Enable:', 'shipping-manager' ),
            'type'  => 'switch',
            'value' => '',
            'desc'  => __( 'To enable/disable method. By deafult enabled', 'shipping-manager' ),
        ),
        'is-plugin-taxable' => array(
            'label' => __( 'Taxable:', 'shipping-manager' ),
            'type'  => 'select',
            'value' => '',
            'desc'  => __( 'Will shipping method taxable or not', 'shipping-manager' ),
        ),
    ]

?>

<div class="tpsm-setting-wrapper">
    <div class="tpsm-general-settings-wrapper">
        <h2><?php esc_html_e( 'General Settings', 'shipping-manager' ) ?></h2>
        <form method="POST">
            <?php wp_nonce_field( 'tpsm-nonce_action', 'tpsm-nonce_name' ); ?>

            <?php 
                foreach ( $general_settings_fields as $key => $field ) {
                    if( 'text' == $field['type'] ) {
                        printf(
                            '<div class="tpsm-setting-row">
                                <div class="tpsm-field">
                                    <div class="tpsm-field-label">
                                        <label>%1$s: </label>
                                    </div>
                                    <div class="tpsm-field-input">
                                        <div>
                                            <input type="text" type="text" >
                                        </div>
                                        <p class="tpsm-field-desc">%2$s</p>
                                    </div>
                                </div>
                            </div>',
                            $field['label'],
                            $field['desc']
                        );
                    }
                }
            ?>
    
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
                        <p class="tpsm-field-desc"><?php esc_html_e( 'To enable/disable method. By deafult enabled', 'shipping-manager' ); ?></p>
                    </div>
                </div>
                
                <!-- Taxable Field  -->
                <div class="tpsm-field">
                    <div class="tpsm-field-label">
                        <label><?php esc_html_e( 'Taxable: ', 'shipping-manager' ); ?></label>
                    </div>
                    <div class="tpsm-field-input">
                        <div class="tpsm-select-wrapper">
                            <select name="" id="">
                                <option value="no"><?php esc_html_e( 'No', 'shipping-manager' ); ?></option>
                                <option value="yes"><?php esc_html_e( 'Yes', 'shipping-manager' ); ?></option>
                            </select>
                        </div>
                        <p class="tpsm-field-desc"><?php esc_html_e( 'Will shipping method taxable or not', 'shipping-manager' ); ?></p>
                    </div>
                </div>

            </div>

            <div class="tpsm-save-button">
                <button type="submit" name="<?php echo $submit_button ?>"><?php esc_html_e( 'Save Settings', 'shipping-manager' ); ?></button>
            </div>
        </form>
    </div>
</div>
