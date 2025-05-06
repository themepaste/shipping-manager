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
     * Proccessing the form 
     * 
     * Save Free Shipping Setting option
     */
    if( isset( $_POST[$submit_button] ) ) {

        if ( ! isset( $_POST['tpsm-nonce_name'] ) || ! wp_verify_nonce( $_POST['tpsm-nonce_name'], 'tpsm-nonce_action' ) ) {
            wp_die( esc_html__( 'Nonce verification failed.', 'shipping-manager' ) );
        }
    
        // Check capabilities if needed
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Unauthorized user', 'shipping-manager' ) );
        }

        $settings_values = [];

        // Main Setting 
        foreach ( $shipping_calculator_settings_fields as $key => $field ) {
            $field_name = $prefix . '-' . $screen_slug . '_' . $key;

            if( 'switch' == $field['type'] ) {
                $settings_values[$key] = isset( $_POST[$field_name] ) ? 1 : 0;
            }
            // else if( 'text' == $field['type'] ) {
            //     $settings_values[$key] = isset( $_POST[$field_name] ) ? sanitize_text_field( $_POST[$field_name] ) : '';
            // }
        }

        // Save setting to database 
        update_option( $option_name, $settings_values );


        //Redirect url
        wp_redirect( add_query_arg( 
            array(
                'page'          => 'shipping-manager',
                'tpsm-setting'  => $screen_slug,
            ),
            admin_url( 'admin.php' )
        ) );

        exit;
    }
?>