<?php 

defined( 'ABSPATH' ) || exit; 

$prefix             = 'tpsm';
$screen_slug        = $args['current_screen'];
$submit_button      = $prefix . '-' . $screen_slug . '_submit';
$option_name        = $prefix . '-' . $screen_slug . '_' . 'settings';
$saved_settings     = get_option( $option_name );
$shipping_calculator_settings_fields = tpsm_shipping_calculator_settings_fields();
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
                            checked( (bool) $field['value'], true, false ),                    // %3$s: Proper "checked" attribute
                            esc_html( $field['desc'] )                               // %4$s: Description
                        );
                    }
                    else if( 'select' == $field['type'] ) {
                        ?>
                        <div class="tpsm-setting-row">
                            <div class="tpsm-field">
                                <div class="tpsm-field-label">
                                    <label><?php echo esc_html( $field['label'] ); ?> </label>
                                </div>
                                <div class="tpsm-field-input">
                                    <?php 
                                        $select_id = esc_attr( $prefix . '-' . $screen_slug . '_' . $key );
                                        printf( '<select name="%1$s" id="%1$s">', esc_attr( $select_id ) );

                                        $options = $field['options'];
                                        // Distinct loop variables: reusing $key here shadowed the
                                        // outer foreach's field key.
                                        foreach ( $options as $option_key => $option_label ) {
                                            printf(
                                                '<option value="%1$s" %3$s>%2$s</option>',
                                                esc_attr( strtolower( $option_key ) ),
                                                esc_html( $option_label ),
                                                selected( strtolower( $option_key ), $field['value'], false )
                                            );
                                        }
                                        ?>
                                    </select>
                                    <p class="tpsm-field-desc"><?php echo esc_html( $field['desc'] ); ?></p>
                                </div>
                            </div>
                        </div>

                        <?php 
                    }
                } 
            ?>


            <div class="tpsm-save-button">
                <button type="submit" name="<?php echo esc_attr( $submit_button ); ?>"><?php esc_html_e( 'Save Settings', 'shipping-manager' ); ?></button>
            </div>
        </form>
    </div>
</div>
<?php
/**
 * Saving is handled by Settings::handle_settings_submit() on `admin_init`.
 */
