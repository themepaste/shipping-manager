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
    'enable-location-field' => array(
        'label' => __( 'Off Location form', 'shipping-manager' ),
        'type'  => 'switch',
        'value' => '',
        'desc'  => __( 'To enable/disable features. By deafult enabled', 'shipping-manager' ),
    ),
]


?>

<div class="tpsm-setting-wrapper">
    <div class="tpsm-shipping-calculator-wrapper">
        <!-- Settings Title -->
        <h2><?php esc_html_e( 'Shipping Calculator Settings', 'shipping-manager' ) ?></h2>
        <form method="POST">
            <?php wp_nonce_field( 'tpsm-nonce_action', 'tpsm-nonce_name' ); ?>
            
            <?php 
                foreach ( $shipping_calculator_settings_fields as $key => $field ) {
                    if( isset( $saved_settings ) && ! empty( $saved_settings ) ) {
                        $field['value'] = isset( $saved_settings[ $key ] ) ? $saved_settings[ $key ] : '';
                    }

                    // Check Field Type 
                    if( 'switch' == $field['type'] ) {
                        printf(
                            '<div class="tpsm-field">
                                <div class="tpsm-field-label">
                                    <label>%1$s: </label>
                                </div>
                                <div class="tpsm-field-input">
                                    <div class="tpsm-switch-wrapper">
                                        <input class="tpsm-switch" type="checkbox" id="%2$s" name="%2$s" %3$s /><label for="%2$s" class="tpsm-switch-label"></label>
                                    </div>
                                    <p class="tpsm-field-desc">%4$s</p>
                                </div>
                            </div>',
                            esc_html( $field['label'] ),                             // %1$s: Label
                            esc_attr( $prefix . '-' . $screen_slug . '_' . $key ),   // %2$s: Safe for id/name
                            checked( $field['value'], 1, false ),                    // %3$s: Proper "checked" attribute
                            esc_html( $field['desc'] )                               // %4$s: Description
                        );
                    }
                    else if( 'text' == $field['type'] ) {
                        printf(
                            '<div class="tpsm-setting-row %5$s" style="display:%6$s;">
                                <div class="tpsm-field">
                                    <div class="tpsm-field-label">
                                        <label>%1$s: </label>
                                    </div>
                                    <div class="tpsm-field-input">
                                        %4$s<input type="text" id="%2$s" name="%2$s" value="%3$s" />
                                        <p class="tpsm-field-desc">%7$s</p>
                                    </div>
                                </div>
                            </div>',
                            esc_html( $field['label'] ),                                                       // %1$s: Field Label
                            esc_attr( $prefix . '-' . $screen_slug . '_' . $key ),                             // %2$s: Field ID & Name
                            esc_attr( $field['value'] ),                                                       // %3$s: Field Value
                            esc_html( $currency_symbol ),                                                      // %4$s: Currency Symbol
                            esc_attr( $prefix . '-' . $screen_slug . '_' . $key . '_wrapper' ),                // %5$s: Wrapper Class
                            esc_attr( isset( $saved_settings[$parent_field_key] ) && $saved_settings[$parent_field_key] == 1 ? 'block' : 'none' ), // %6$s: Display Value
                            esc_html__( 'Cart minimum amount for free shipping.', 'shipping-manager' )         // %7$s: Description (translated and escaped)
                        );
                    }
                } 
            ?>


            <div class="tpsm-save-button">
                <button type="submit" name="<?php echo esc_attr( $submit_button ); ?>"><?php esc_html_e( 'Save Settings', 'shipping-manager' ); ?></button>
            </div>
        </form>
    </div>
</div>