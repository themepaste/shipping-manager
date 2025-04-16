<?php 
defined( 'ABSPATH' ) || exit; 
$tpsm_shipping_fees_settings = get_option( 'tpsm-shipping-fees_settings' );
?>

<div class="tpsm-shipping-fees-wrapper">
    <form method="POST">
        <?php wp_nonce_field( 'tpsm-nonce_action', 'tpsm-nonce_name' ); ?>
        <div class="tpsm-setting-row">
            <label><?php esc_html_e( 'Disable/Enable:', 'shipping-manager' ); ?></label>
            <input class="tpsm-switch" type="checkbox" id="tpsm-shipping-fees-disable-enable" name="tpsm-shipping-fees-disable-enable" <?php echo $tpsm_shipping_fees_settings['enabled'] ? 'checked' : ''; ?> /><label for="tpsm-shipping-fees-disable-enable" class="tpsm-switch-label"></label>
        </div>

        <div class="tpsm-setting-row tpsm-setting-radio-wrapper">
            <div class="tpsm-shipping-radio">
                <input type="radio" id="tpsm-flat-rate-fee" name="tpsm-shipping-fee_type" value="tpsm-unit-weight-fee">
                <label for="tpsm-flat-rate-fee"><?php esc_html_e( 'Unit Weight Fee', 'shipping-manager' ) ?></label>
            </div>
            <div class="tpsm-shipping-radio">
                <input type="radio" id="tpsm-weight-base-fee" name="tpsm-shipping-fee_type" value="tpsm-weight-range-fee">
                <label for="tpsm-weight-base-fee"><?php esc_html_e( 'Weight Range Pricing', 'shipping-manager' ); ?></label>
            </div>
        </div>

        <div class="tpsm-shipping-fees-container tpsm-setting-flat-rat-container" id="tpsm-unit-weight-fee">
            <label for="tpsm-shipping-fees-flat-rate-amount"><?php esc_html_e( 'Flat rate Per Unit:', 'shipping-manager' ); ?></label>
            <input type="text" id="tpsm-shipping-fees-flat-rate-amount" name="tpsm-shipping-fees-flat-rate-amount" value="<?php echo $tpsm_shipping_fees_settings['flat-rate'] ?>">
        </div>

        <div class="tpsm-shipping-fees-container tpsm-setting-weight-base-container" id="tpsm-weight-range-fee" style="display: none;">
            <h1>Weight Range Price</h1>

            <div class="tpsm-shipping-fees-repeater">
                <div class="tpsm-repeater-row">
                    <div class="tpsm-column-1">Form</div>
                    <div class="tpsm-column-2">To</div>
                    <div class="tpsm-column-3">Fee</div>
                    <div class="tpsm-column-4">Action</div>
                </div>
                <?php 
                    $tpsm_weight_price_range_settings = $tpsm_shipping_fees_settings['weight-range-price'];

                    foreach ( $tpsm_weight_price_range_settings as $key => $value) {
                        ?>
                            <div class="tpsm-repeater-row">
                                <div class="tpsm-column-1">
                                    <input type="text" name="from[]" value="<?php echo $value['from']; ?>">
                                </div>
                                <div class="tpsm-column-2">
                                    <input type="text" name="to[]" value="<?php echo $value['to']; ?>">
                                </div>
                                <div class="tpsm-column-3">
                                    <input type="text" name="fee[]" value="<?php echo $value['fee']; ?>">
                                </div>
                                <div class="tpsm-column-4">
                                    <button type="button">Edit</button>
                                    <button type="button" class="delete-row">Delete</button>
                                </div>
                            </div>
                        <?php 
                    }
                ?>

               
            </div>
            <button type="button" id="addRowBtn">Add New</button>

        </div>

        <div class="tpsm-save-button">
            <button type="submit" name="tpsm-shipping-fees_submit"><?php esc_html_e( 'Save', 'shipping-manager' ); ?></button>
        </div>
    </form>

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
        $tpsm_shipping_fee_type = isset( $_POST['tpsm-shipping-fee_type'] ) ? sanitize_text_field( $_POST['tpsm-shipping-fee_type'] ) : 'tpsm-unit-weight-fee';
        $tpsm_shipping_fee_flat_rate_amount = isset( $_POST['tpsm-shipping-fees-flat-rate-amount'] ) ? sanitize_text_field( $_POST['tpsm-shipping-fees-flat-rate-amount'] ) : '';

        $tpsm_shipping_fees_settings_values = [
            'enabled'   => $shipping_fees_enabled,
            'type'      => $tpsm_shipping_fee_type,
            'flat-rate' => $tpsm_shipping_fee_flat_rate_amount,
            'weight-range-price' => [],
        ];

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
                $fee_sanitized = is_numeric($fee_raw) ? floatval($fee_raw) : 0;

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
        
        update_option( 'tpsm-shipping-fees_settings', $tpsm_shipping_fees_settings_values );
        wp_redirect( admin_url( 'admin.php?page=shipping-manager' ) );
        exit;
    }
?>