<?php 
    defined( 'ABSPATH' ) || exit; 

    $prefix             = 'tpsm';
    $currency_symbol    = get_woocommerce_currency_symbol();
    $screen_slug        = $args['current_screen'];
    $submit_button      = $prefix . '-' . $screen_slug . '_submit';
    $option_name        = $prefix . '-' . $screen_slug . '_' . 'settings';
    $saved_settings     = get_option( $option_name );

    $general_settings_fields = [
        'method-title' => array(
            'label' => __( 'Method Title', 'shipping-manager' ),
            'type'  => 'text',
            'value' => '',
            'desc'  => __( 'By default "Shipping Manager"', 'shipping-manager' ),
        ),
        'is-plugin-enable' => array(
            'label' => __( 'Disable/Enable', 'shipping-manager' ),
            'type'  => 'switch',
            'value' => '',
            'desc'  => __( 'To enable/disable method. By deafult enabled', 'shipping-manager' ),
        ),
        'is-plugin-taxable' => array(
            'label' => __( 'Taxable', 'shipping-manager' ),
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

                    if( isset( $saved_settings ) && ! empty( $saved_settings ) ) {
                        $field['value'] = isset( $saved_settings[ $key ] ) ? $saved_settings[ $key ] : '';
                    }

                    if( 'text' == $field['type'] ) {
                        printf(
                            '<div class="tpsm-setting-row">
                                <div class="tpsm-field">
                                    <div class="tpsm-field-label">
                                        <label>%1$s: </label>
                                    </div>
                                    <div class="tpsm-field-input">
                                        <div>
                                            <input type="text" type="text" name="%3$s" value="%4$s">
                                        </div>
                                        <p class="tpsm-field-desc">%2$s</p>
                                    </div>
                                </div>
                            </div>',
                            $field['label'],                                //%1$s =  Field Label
                            $field['desc'],                                 //%2$s =  Description
                            $prefix . '-' . $screen_slug . '_' . $key,      //%3$s =  Field Name
                            $field['value'],                                //%4$s =  Value
                        );
                    }
                    else if( 'switch' == $field['type'] ) {
                        printf(
                            '<div class="tpsm-setting-row">
                                <div class="tpsm-field">
                                    <div class="tpsm-field-label">
                                        <label>%1$s:</label>
                                    </div>
                                    <div class="tpsm-field-input">
                                        <div class="tpsm-switch-wrapper">
                                            <input class="tpsm-switch" type="checkbox" id="%3$s" name="%3$s" %4$s /><label for="%3$s" class="tpsm-switch-label"></label>
                                        </div>
                                        <p class="tpsm-field-desc">%2$s</p>
                                    </div>
                                </div>
                            </div>',
                            $field['label'],                            //%1$s =  Field Label
                            $field['desc'],                             //%1$s =  Description
                            $prefix . '-' . $screen_slug . '_' . $key,  //%1$s =  Field name
                            $field['value'] == 1 ? 'checked' : '',      //%1$s =  Value
                        );
                    }
                    else if( 'select' == $field['type'] ) {
                        printf(
                            '<div class="tpsm-setting-row">
                                <div class="tpsm-field">
                                    <div class="tpsm-field-label">
                                        <label>%1$s:</label>
                                    </div>
                                    <div class="tpsm-field-input">
                                        <div class="tpsm-select-wrapper">
                                            <select name="%3$s" id="%3$s">
                                                <option value="no" %4$s >No</option>
                                                <option value="yes" %5$s >Yes</option>
                                            </select>
                                        </div>
                                        <p class="tpsm-field-desc">%2$s</p>
                                    </div>
                                </div>
                            </div>',
                            $field['label'],                                //%1$s =  Field Label
                            $field['desc'],                                 //%2$s =  Description
                            $prefix . '-' . $screen_slug . '_' . $key,      //%3$s =  Field Name
                            $field['value'] == 'no' ? 'selected' : '',      //%4$s =  Value if no
                            $field['value'] == 'yes' ? 'selected' : '',     //%5$s =  Value if yes
                        );
                    }
                }
            ?>

            <div class="tpsm-save-button">
                <button type="submit" name="<?php echo $submit_button ?>"><?php esc_html_e( 'Save Settings', 'shipping-manager' ); ?></button>
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
            wp_die( __( 'Nonce verification failed.', 'shipping-manager' ) );
        }
    
        // Check capabilities if needed
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Unauthorized user', 'shipping-manager' ) );
        }

        $settings_values = [];

        foreach ( $general_settings_fields as $key => $field ) {
            $field_name = $prefix . '-' . $screen_slug . '_' . $key;

            if( 'switch' == $field['type'] ) {
                $settings_values[$key] = isset( $_POST[$field_name] ) ? 1 : 0;
            }
            else if( 'text' == $field['type'] ) {
                $settings_values[$key] = isset( $_POST[$field_name] ) ? sanitize_text_field( $_POST[$field_name] ) : '';
            }
            else if( 'select' == $field['type'] ) {
                $settings_values[$key] = isset( $_POST[$field_name] ) ? sanitize_text_field( $_POST[$field_name] ) : '';
            }
        }

        update_option( $option_name, $settings_values );

        wp_redirect( add_query_arg( 
            array(
                'page'          => 'shipping-manager',
                'tpsm-setting'  => $screen_slug,
            ),
            admin_url( 'admin.php' )
        ) );

        exit;
    }