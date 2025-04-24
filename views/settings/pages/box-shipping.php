<?php 
    defined( 'ABSPATH' ) || exit; 

    $tpsm_box_shipping_settings = get_option( 'tpsm-box-shipping_settings' );
    $tpsm_box_shipping_settings_values = $tpsm_box_shipping_settings ? $tpsm_box_shipping_settings : [
        'enabled'   => 0,
        'box-shipping' => []
    ];

    $weight_unit        = get_option( 'woocommerce_weight_unit' );
    $dimension_unit     = get_option( 'woocommerce_dimension_unit' );
    $currency_symbol    = get_woocommerce_currency_symbol();
?>
 
<div class="tpsm-setting-wrapper">
    <div class="tpsm-box-shipping-wrapper">
        <h2><?php esc_html_e( 'Box Size Shipping Settings', 'shipping-manager' ) ?></h2>
        <form method="POST">
            <?php wp_nonce_field( 'tpsm-nonce_action', 'tpsm-nonce_name' ); ?>
    
            <div class="tpsm-setting-row">
                
                <!-- Switch for enable disable  -->
                <div class="tpsm-field">
                    <div class="tpsm-field-label">
                        <label><?php esc_html_e( 'Disable/Enable:', 'shipping-manager' ); ?></label>
                    </div>
                    <div class="tpsm-field-input">
                        <div class="tpsm-switch-wrapper">
                            <input class="tpsm-switch" type="checkbox" id="tpsm-box-shipping-disable-enable" name="tpsm-box-shipping-disable-enable" <?php echo $tpsm_box_shipping_settings_values['enabled'] ? 'checked' : ''; ?> /><label for="tpsm-box-shipping-disable-enable" class="tpsm-switch-label"></label>
                        </div>
                        <p class="tpsm-field-desc"><?php esc_html_e( 'To enable/disable this feature.', 'shipping-manager' ); ?></p>
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
                        <p class="tpsm-field-desc"><?php esc_html_e( 'Will it taxable or not', 'shipping-manager' ); ?></p>
                    </div>
                </div>

            </div>

            <!-- Box size Shipping -->
            <div class="tpsm-field">
                <div class="tpsm-field-label">
                    <label><?php esc_html_e( 'Boxes:', 'shipping-manager' ); ?></label>
                </div>
                <div class="tpsm-field-input"></div>
            </div>
            <div class="tpsm-box-size-shipping-wrapper">
                <div class="tpsm-box-size-repeater tpsm-box-size-shipping-table-wrapper">
                    <div class="tpsm-box-size-repeater-row">
                        <div class="tpsm-column-1">Length (<?php echo $dimension_unit;?> )</div>
                        <div class="tpsm-column-2">Width (<?php echo $dimension_unit;?> )</div>
                        <div class="tpsm-column-3">Height (<?php echo $dimension_unit;?> )</div>
                        <div class="tpsm-column-4">Fee (<?php echo $currency_symbol;?>)</div>
                        <div class="tpsm-column-4">Action</div>
                    </div>
                    <?php 
                        $tpsm_box_size_shipping_settings = $tpsm_box_shipping_settings_values['box-shipping'];
    
                        if( is_null( $tpsm_box_size_shipping_settings ) || empty( $tpsm_box_size_shipping_settings ) || ! isset( $tpsm_box_size_shipping_settings ) ) {
                            ?>
                                <div class="tpsm-box-size-repeater-row">
                                    <div class="tpsm-column-1">
                                        <input type="text" name="tpsm-box-size-length[]">
                                    </div>
                                    <div class="tpsm-column-2">
                                        <input type="text" name="tpsm-box-size-width[]">
                                    </div>
                                    <div class="tpsm-column-3">
                                        <input type="text" name="tpsm-box-size-height[]">
                                    </div>
                                    <div class="tpsm-column-4">
                                        <input type="text" name="tpsm-box-size-fee[]">
                                    </div>
                                    <div class="tpsm-column-4">
                                        <button type="button" class="delete-row"><?php esc_html_e( 'Delete', 'shipping-manager' ); ?></button>
                                    </div>
                                </div>
                            <?php
                        } else {
                            foreach ( $tpsm_box_size_shipping_settings as $key => $value ) {
                                ?>
                                    <div class="tpsm-box-size-repeater-row">
                                        <div class="tpsm-column-1">
                                            <input type="text" name="tpsm-box-size-length[]" value="<?php echo $value['length']; ?>">
                                        </div>
                                        <div class="tpsm-column-2">
                                            <input type="text" name="tpsm-box-size-width[]" value="<?php echo $value['width']; ?>">
                                        </div>
                                        <div class="tpsm-column-3">
                                            <input type="text" name="tpsm-box-size-height[]" value="<?php echo $value['height']; ?>">
                                        </div>
                                        <div class="tpsm-column-4">
                                            <input type="text" name="tpsm-box-size-fee[]" value="<?php echo $value['fee']; ?>">
                                        </div>
                                        <div class="tpsm-column-4">
                                            <button type="button" class="delete-row"><?php esc_html_e( 'Delete', 'shipping-manager' ); ?></button>
                                        </div>
                                    </div>
                                <?php
                            }
                        }
                    ?>
                </div>

                <div class="tpsm-addrow-button">
                    <button type="button" id="tpsm-dimension-add">Add New</button>
                </div>
            </div>
    
            <div class="tpsm-save-button">
                <button type="submit" name="tpsm-box-shipping_submit"><?php esc_html_e( 'Save Settings', 'shipping-manager' ); ?></button>
            </div>
    
        </form>
    
    </div>
</div>


<?php 
    /**
     * Proccessing the form 
     * 
     * Save box size shipping setting option
     */
    if( isset( $_POST['tpsm-box-shipping_submit'] ) ) {

        if ( ! isset( $_POST['tpsm-nonce_name'] ) || ! wp_verify_nonce( $_POST['tpsm-nonce_name'], 'tpsm-nonce_action' ) ) {
            wp_die( __( 'Nonce verification failed.', 'shipping-manager' ) );
        }
    
        // Check capabilities if needed
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Unauthorized user', 'shipping-manager' ) );
        }

        $shipping_fees_enabled  = isset( $_POST['tpsm-box-shipping-disable-enable'] ) ? 1 : 0;
        $tpsm_box_shipping_settings_values['enabled'] = $shipping_fees_enabled;
        /**
         * If type is weight range
         */
        
        $length = $_POST['tpsm-box-size-length'] ?? [];
        $width  = $_POST['tpsm-box-size-width'] ?? [];
        $height = $_POST['tpsm-box-size-height'] ?? [];
        $fee    = $_POST['tpsm-box-size-fee'] ?? [];

        $box_size_data = [];

        foreach ( $length as $index => $length_value ) {
            // Sanitize each field using WordPress functions
            $length_sanitized   = sanitize_text_field( $length_value );
            $width_sanitized    = sanitize_text_field( $width[$index] ?? '' );
            $height_sanitized   = sanitize_text_field( $height[$index] ?? '' );
            $fee_raw            = $fee[$index] ?? '';

            // Validate the fee as a numeric value (you can also use is_numeric or regex)
            $fee_sanitized = is_numeric( $fee_raw ) ? floatval($fee_raw) : 0;

            // Only include if at least 'from' and 'to' have values (you can modify this logic)
            if ( !empty( $length_sanitized ) && !empty( $width_sanitized ) && !empty( $height_sanitized ) ) {
                $dimension       = $length_sanitized * $width_sanitized * $height_sanitized;
                $box_size_data[] = [
                    'length'    => $length_sanitized,
                    'width'     => $width_sanitized,
                    'height'    => $height_sanitized,
                    'fee'       => $fee_sanitized,
                    'dimension' => $dimension,
                ];
            }
        }
        $tpsm_box_shipping_settings_values['box-shipping'] = $box_size_data;
        
        update_option( 'tpsm-box-shipping_settings', $tpsm_box_shipping_settings_values );

        wp_redirect( add_query_arg( 
            array(
                'page'          => 'shipping-manager',
                'tpsm-setting'  => $args['current_screen'],
            ),
            admin_url( 'admin.php' )
        ) );

        exit;
    }
?>