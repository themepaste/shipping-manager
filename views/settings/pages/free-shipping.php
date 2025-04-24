<?php 

defined( 'ABSPATH' ) || exit;

$prefix             = 'tpsm';
$currency_symbol    = get_woocommerce_currency_symbol();
$screen_slug        = $args['current_screen'];
$submit_button      = $prefix . '-' . $screen_slug . '_submit';
$option_name        = $prefix . '-' . $screen_slug . '_' . 'settings';
$saved_settings     = get_option( $option_name );
$parent_field_key   = 'minimum-amount'; //It has a child field called "Cart Amount"

/**
 * Defined All Fields
 */
$settings_fields = [
    'hide-other' => array(
        'label' => __( 'Hide Other', 'shipping-manager' ),
        'type'  => 'switch',
        'value' => '',
        'desc'  => __( 'Hide other shipping methods while free shipping is available.', 'shipping-manager' ),
    ),  
    'free-shipping-bar' => array(
        'label' => __( 'Show Free Shipping Bar', 'shipping-manager' ),
        'type'  => 'switch',
        'value' => '',
        'desc'  => __( 'Enable free shipping bar for customers. Customers will see a bar for target to achieve free shipping.', 'shipping-manager' ),
    ),
    'minimum-amount' => array(
        'label' => __( 'Minimum Amount', 'shipping-manager' ),
        'type'  => 'switch',
        'value' => '',
        'desc'  => __( 'This will enable a custom minimum amount for free shipping. Otherwise it will use default minimum amount set in WooCommerce free shipping.', 'shipping-manager' ),
    ),
    'cart-amount' => array(
        'label' => __( 'Cart Amount', 'shipping-manager' ),
        'type'  => 'text',
        'value' => '',
        'desc'  => __( 'Cart minimum amount for free shipping.', 'shipping-manager' ),
    )
];
?>

<div class="tpsm-setting-wrapper">
    <div class="tpsm-free-shipping-wrapper">
        <!-- Settings Title -->
        <h2><?php esc_html_e( 'Free Shipping Settings', 'shipping-manager' ) ?></h2>
        <form method="POST">
            <?php wp_nonce_field( 'tpsm-nonce_action', 'tpsm-nonce_name' ); ?>

            <!-- Print All Field releated to this screen -->
            <?php 
                foreach ( $settings_fields as $key => $field ) {
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
                            $field['label'],                                // Field Label 
                            $prefix . '-' . $screen_slug . '_' . $key,      // Field Name
                            $field['value'] == 1 ? 'checked' : '',          // Field Value || Checked
                            $field['desc']                                  // Description
                        );
                    }
                    else if( 'text' == $field['type'] ) {
                        printf(
                            '<div class="tpsm-setting-row %5$s" style="display:%6$s"; >
                                <label>%1$s: </label>
                                <input type="text" id="%2$s" name="%2$s" value="%3$s" />%4$s
                            </div>
                            ',
                            $field['label'],                                    // Field Label
                            $prefix . '-' . $screen_slug . '_' . $key,          // Field Name
                            $field['value'],                                    // Field Value
                            $currency_symbol,                                   // Woocommerce Currency Symbol
                            $prefix . '-' . $screen_slug . '_' . $key . '_wrapper', // Whole Field Wrapper
                            isset( $saved_settings[$parent_field_key] ) ? ( $saved_settings[$parent_field_key] == 1 ? 'block' : 'none' ) : 'none',
                        );
                    }
                } 
            ?>

            <div class="tpsm-save-button">
                <button type="submit" name="<?php echo $submit_button ?>"><?php esc_html_e( 'Save', 'shipping-manager' ); ?></button>
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

        foreach ( $settings_fields as $key => $field ) {
            $field_name = $prefix . '-' . $screen_slug . '_' . $key;

            if( 'switch' == $field['type'] ) {
                $settings_values[$key] = isset( $_POST[$field_name] ) ? 1 : 0;
            }
            else if( 'text' == $field['type'] ) {
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
?>