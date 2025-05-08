<?php 

defined( 'ABSPATH' ) || exit;

$prefix             = 'tpsm';
$currency_symbol    = get_woocommerce_currency_symbol();
$screen_slug        = $args['current_screen'];
$submit_button      = $prefix . '-' . $screen_slug . '_submit';
$option_name        = $prefix . '-' . $screen_slug . '_' . 'settings';
$on_shipping_bar    = $prefix . '-free-shipping-bar' . $screen_slug . '_' . 'settings'; //on = option name
$saved_settings     = get_option( $option_name );
$saved_styles       = get_option( $on_shipping_bar );
$parent_field_key   = 'minimum-amount'; //It has a child field called "Cart Amount"

/**
 * Defined All Fields
 */
$settings_fields = tpsm_free_shipping_settings_fields();
$shipping_bar_style_fields = $settings_fields['free-shipping-bar']['child-fields'];
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
                            esc_html( $field['label'] ),                             // %1$s: Label
                            esc_attr( $prefix . '-' . $screen_slug . '_' . $key ),   // %2$s: Safe for id/name
                            checked( (bool) $field['value'], true, false ),                    // %3$s: Proper "checked" attribute
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

            <!-- Shipping bar styles  -->
            <div id="tpsm-shipping-bar-styles-container" class="tpsm-shipping-bar-styles-wrapper" style="display:<?php echo esc_attr( isset( $saved_settings['free-shipping-bar'] ) && $saved_settings['free-shipping-bar'] == 1 ? 'block' : 'none' ); ?>;">
                <h2><?php esc_html_e( 'Progress  Bar Styles & Message', 'shipping-manager' ); ?></h2>
                <?php
                    foreach ( $shipping_bar_style_fields as $key => $field ) {
                        if( isset( $saved_styles ) && ! empty( $saved_styles ) ) {
                            $field['value'] = isset( $saved_styles[ $key ] ) ? $saved_styles[ $key ] : '';
                        }

                        if( 'text' == $field['type'] ) {
                            printf(
                                '<div class="tpsm-setting-row">
                                    <div class="tpsm-field">
                                        <div class="tpsm-field-label">
                                            <label>%1$s: </label>
                                        </div>
                                        <div class="tpsm-field-input">
                                            <input type="text" id="%2$s" name="%2$s" value="%3$s" />
                                            <p class="tpsm-field-desc">%4$s</p>
                                        </div>
                                    </div>
                                </div>',
                                esc_html( $field['label'] ),                                    // %1$s: Label text
                                esc_attr( $prefix . '-' . $screen_slug . '_' . $key ),          // %2$s: ID and name attributes
                                esc_attr( $field['value'] ),                                    // %3$s: Value attribute
                                esc_html( $field['desc'] )                                      // %4$s: Field description
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
                                            foreach ( $options as $option ) {
                                                printf(
                                                    '<option value="%1$s" %3$s>%2$s</option>',
                                                    esc_attr( strtolower( $option ) ),
                                                    esc_html( $option ),
                                                    selected( strtolower( $option ), $field['value'], false )
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
                        else if( 'picker' == $field['type'] ) {
                            ?>
                            <div class="tpsm-setting-row">
                                <div class="tpsm-field">
                                    <div class="tpsm-field-label">
                                        <label><?php echo esc_html( $field['label'] ); ?></label>
                                    </div>
                                    <div class="tpsm-field-input">
                                        <?php 
                                            printf(
                                                '<div class="tpsm-color-field">
                                                    <input type="color" class="colorpicker" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" name="color" value="%1$s"> 
                                                    <input type="text" name="%2$s" class="hexcolor" value="%1$s" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" placeholder="#000000">
                                                </div>
                                                <p class="tpsm-field-desc">%3$s</p>',
                                                esc_attr( sanitize_hex_color( $field['value'] ) ),
                                                esc_attr( $prefix . '-' . $screen_slug . '_' . $key ),
                                                esc_html( $field['desc'] )
                                            );
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                ?>

            </div>

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
        foreach ( $settings_fields as $key => $field ) {
            $field_name = $prefix . '-' . $screen_slug . '_' . $key;

            if( 'switch' == $field['type'] ) {
                $settings_values[$key] = isset( $_POST[$field_name] ) ? 1 : 0;
            }
            else if( 'text' == $field['type'] ) {
                $settings_values[$key] = isset( $_POST[$field_name] ) ? sanitize_text_field( $_POST[$field_name] ) : '';
            }
        }

        // Save setting to database 
        update_option( $option_name, $settings_values );

        

        // Shipping Bar style settings 
        $shipping_bar_styles_values = [];
        foreach ( $shipping_bar_style_fields as $key => $field ) {
            $field_name = $prefix . '-' . $screen_slug . '_' . $key;

            $shipping_bar_styles_values[$key] = isset( $_POST[$field_name] ) ? sanitize_text_field( $_POST[$field_name] ) : '';
        }

        update_option( $on_shipping_bar, $shipping_bar_styles_values );

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