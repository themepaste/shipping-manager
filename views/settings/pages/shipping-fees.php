<?php 
defined( 'ABSPATH' ) || exit; 
$tpsm_shipping_fees_settings = get_option( 'tpsm-shipping-fees_settings' );
$tpsm_shipping_fees_settings_values = $tpsm_shipping_fees_settings ? $tpsm_shipping_fees_settings : [
    'enabled'   => 0,
    'type'      => 'tpsm-unit-weight-fee',
    'flat-rate' => '',
    'weight-range-price' => []
];
$weight_unit = get_option( 'woocommerce_weight_unit' );
$currency_symbol = get_woocommerce_currency_symbol();
?>

<div class="tpsm-setting-wrapper">
    <div class="tpsm-shipping-fees-wrapper">
        <h2><?php esc_html_e( 'Shipping Fees Settings', 'shipping-manager' ) ?></h2>
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
                            <input class="tpsm-switch" type="checkbox" id="tpsm-shipping-fees-disable-enable" name="tpsm-shipping-fees-disable-enable" <?php echo $tpsm_shipping_fees_settings_values['enabled'] ? 'checked' : ''; ?> /><label for="tpsm-shipping-fees-disable-enable" class="tpsm-switch-label"></label>
                        </div>
                        <p class="tpsm-field-desc"><?php esc_html_e( 'To enable/disable this feature.', 'shipping-manager' ); ?></p>
                    </div>
                </div>
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
    
            <!-- Shipping Fees Type -->
            <div class="tpsm-setting-row">

                <div class="tpsm-field">
                    <div class="tpsm-field-label">
                        <label><?php esc_html_e( 'Charges: ', 'shipping-manager' ); ?></label>
                    </div>
                    <div class="tpsm-field-input">
                        <div class="tpsm-setting-radio-fields-wrapper">
                            <div class="tpsm-shipping-radio">
                                <input type="radio" id="tpsm-flat-rate-fee" name="tpsm-shipping-fee_type" value="tpsm-unit-weight-fee" <?php echo $tpsm_shipping_fees_settings_values['type'] == 'tpsm-unit-weight-fee' ? 'checked' : ''; ?>>
                                <label for="tpsm-flat-rate-fee"><?php esc_html_e( 'Unit Weight Fee', 'shipping-manager' ) ?></label>
                            </div>
                            <div class="tpsm-shipping-radio">
                                <input type="radio" id="tpsm-weight-base-fee" name="tpsm-shipping-fee_type" value="tpsm-weight-range-fee" <?php echo $tpsm_shipping_fees_settings_values['type'] == 'tpsm-weight-range-fee' ? 'checked' : ''; ?>>
                                <label for="tpsm-weight-base-fee"><?php esc_html_e( 'Weight Range Fee', 'shipping-manager' ); ?></label>
                            </div>
                        </div>
                        <p class="tpsm-field-desc"><?php esc_html_e( 'You can set only one weight base shipping rule at a time.', 'shipping-manager' ); ?></p>
                    </div>
                </div>
            </div>
    
            <!-- Unit Weight Fee Container -->
            <div class="tpsm-shipping-fees-container tpsm-setting-flat-rat-container" id="tpsm-unit-weight-fee" style="<?php echo $tpsm_shipping_fees_settings_values['type'] == 'tpsm-unit-weight-fee' ? '' : 'display:none'?>">

                <div class="tpsm-field">
                    <div class="tpsm-field-label">
                        <label><?php esc_html_e( 'Flat rate per Unit/' . $weight_unit . ' :', 'shipping-manager' ); ?></label>
                    </div>
                    <div class="tpsm-field-input">
                        <?php echo $currency_symbol ?>
                        <input type="text" id="tpsm-shipping-fees-flat-rate-amount" name="tpsm-shipping-fees-flat-rate-amount" value="<?php echo $tpsm_shipping_fees_settings_values['flat-rate'] ?>">
                        <p class="tpsm-field-desc"><?php esc_html_e( 'Adds per unit/' . $weight_unit . ' a flat processing fee to process the shipment.', 'shipping-manager' ); ?></p>
                    </div>
                </div>
                
            </div>
    
            <!-- Price Range Container  -->
            <div class="tpsm-shipping-fees-container tpsm-setting-weight-base-container" id="tpsm-weight-range-fee" style="<?php echo $tpsm_shipping_fees_settings_values['type'] == 'tpsm-weight-range-fee' ? '' : 'display:none'?>">
                <div class="tpsm-field">
                    <div class="tpsm-field-label">
                        <label><?php esc_html_e( 'Flat rate for Unit ranges: ', 'shipping-manager' ); ?></label>
                    </div>
                    <div class="tpsm-field-input"></div>
                </div>
                <div>
                    <table class="tpsm-shipping-fees-repeater">
                        <thead>
                            <tr>
                                <th>From</th>
                                <th>To</th>
                                <th>Fee</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $tpsm_weight_price_range_settings = isset( $tpsm_shipping_fees_settings['weight-range-price'] ) ? $tpsm_shipping_fees_settings['weight-range-price'] : '';
            
                                if( is_null( $tpsm_weight_price_range_settings ) || empty( $tpsm_weight_price_range_settings ) || ! isset( $tpsm_weight_price_range_settings ) ) {
                                    ?>
                                        <tr class="tpsm-repeater-row">
                                            <td>
                                                <input type="text" name="from[]">
                                                <?php echo $weight_unit; ?>
                                            </td>
                                            <td>
                                                <input type="text" name="to[]">
                                                <?php echo $weight_unit; ?>
                                            </td>
                                            <td>
                                                <?php echo $currency_symbol; ?>
                                                <input type="text" name="fee[]">
                                            </td>
                                            <td>
                                                <button type="button" class="delete-row"><?php esc_html_e( 'Delete', 'shipping-manager' ); ?></button>
                                            </td>
                                        </tr>
                                    <?php
                                }
                                else {
                                    foreach ( $tpsm_weight_price_range_settings as $key => $value) {
                                        ?>
                                            <tr class="tpsm-repeater-row">
                                                <td>
                                                    <input type="text" name="from[]" value="<?php echo $value['from']; ?>">
                                                    <?php echo $weight_unit; ?>
                                                </td>
                                                <td>
                                                    <input type="text" name="to[]" value="<?php echo $value['to']; ?>">
                                                    <?php echo $weight_unit; ?>
                                                </td>
                                                <td>
                                                    <?php echo $currency_symbol; ?>
                                                    <input type="text" name="fee[]" value="<?php echo $value['fee']; ?>">
                                                </td>
                                                <td>
                                                    <button type="button" class="delete-row"><?php esc_html_e( 'Delete', 'shipping-manager' ); ?></button>
                                                </td>
                                            </tr>
                                        <?php 
                                    }
                                }
                            ?>
                      </tbody>
                    </table>
                </div>
    
                <div class="tpsm-addrow-button">
                    <button type="button" id="tpsm-weight-range-pricing-add">Add New</button>
                </div>
    
            </div>
    
            <div class="tpsm-save-button">
                <button type="submit" name="tpsm-shipping-fees_submit"><?php esc_html_e( 'Save Settings', 'shipping-manager' ); ?></button>
            </div>
        </form>
    
    </div>
</div>

<?php 
    /**
     * Proccessing the form 
     * 
     * Save shipping fees setting option
     */
    if( isset( $_POST['tpsm-shipping-fees_submit'] ) ) {

        if ( ! isset( $_POST['tpsm-nonce_name'] ) || ! wp_verify_nonce( $_POST['tpsm-nonce_name'], 'tpsm-nonce_action' ) ) {
            wp_die( __( 'Nonce verification failed.', 'shipping-manager' ) );
        }
    
        // Check capabilities if needed
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Unauthorized user', 'shipping-manager' ) );
        }

        $shipping_fees_enabled  = isset( $_POST['tpsm-shipping-fees-disable-enable'] ) ? 1 : 0;
        $tpsm_shipping_fee_type = isset( $_POST['tpsm-shipping-fee_type'] ) ? sanitize_text_field( $_POST['tpsm-shipping-fee_type'] ) : '';
        $tpsm_shipping_fee_flat_rate_amount = isset( $_POST['tpsm-shipping-fees-flat-rate-amount'] ) ? sanitize_text_field( $_POST['tpsm-shipping-fees-flat-rate-amount'] ) : '';

        $tpsm_shipping_fees_settings_values['enabled'] = $shipping_fees_enabled;
        $tpsm_shipping_fees_settings_values['type'] = $tpsm_shipping_fee_type;


        /**
         * If type is weight range
         */
        if( 'tpsm-weight-range-fee' == $tpsm_shipping_fee_type ) {
            $from = $_POST['from'] ?? [];
            $to   = $_POST['to'] ?? [];
            $fee  = $_POST['fee'] ?? [];

            $shipping_data = [];

            foreach ( $from as $index => $from_location ) {
                // Sanitize each field using WordPress functions
                $from_sanitized = sanitize_text_field( $from_location );
                $to_sanitized   = sanitize_text_field( $to[$index] ?? '' );
                $fee_raw        = $fee[$index] ?? '';

                // Validate the fee as a numeric value (you can also use is_numeric or regex)
                $fee_sanitized = is_numeric( $fee_raw ) ? floatval($fee_raw) : 0;

                // Only include if at least 'from' and 'to' have values (you can modify this logic)
                if ( !empty( $from_sanitized ) && !empty( $to_sanitized ) ) {
                    $shipping_data[] = [
                        'from' => $from_sanitized,
                        'to'   => $to_sanitized,
                        'fee'  => $fee_sanitized,
                    ];
                }
            }
            $tpsm_shipping_fees_settings_values['weight-range-price'] = $shipping_data;
        }
        else if( 'tpsm-unit-weight-fee' == $tpsm_shipping_fee_type ) {
            $tpsm_shipping_fees_settings_values['flat-rate'] = $tpsm_shipping_fee_flat_rate_amount;
        }
        
        update_option( 'tpsm-shipping-fees_settings', $tpsm_shipping_fees_settings_values );
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