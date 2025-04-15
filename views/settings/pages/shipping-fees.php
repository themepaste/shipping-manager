<?php 
defined( 'ABSPATH' ) || exit; 
$tpsm_shipping_fees_settings = get_option( 'tpsm-shipping-fees_settings' );

// echo "<pre>";
// print_r( $tpsm_shipping_fees_settings );
// echo "</pre>";
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
                <input type="radio" id="tpsm-flat-rate-fee" name="tpsm-shipping-fee_type" value="tpsm-unit-weight-fee" checked>
                <label for="tpsm-flat-rate-fee"><?php esc_html_e( 'Unit Weight Fee', 'shipping-manager' ) ?></label>
            </div>
            <div class="tpsm-shipping-radio">
                <input type="radio" id="tpsm-weight-base-fee" name="tpsm-shipping-fee_type" value="tpsm-weight-range-fee">
                <label for="tpsm-weight-base-fee"><?php esc_html_e( 'Weight Range Pricing', 'shipping-manager' ); ?></label>
            </div>
        </div>

        <div class="tpsm-shipping-fees-container tpsm-setting-flat-rat-container" id="tpsm-unit-weight-fee">
            <label for="tpsm-shipping-fees-flat-rate-amount"><?php esc_html_e( 'Flat rate Per Unit:', 'shipping-manager' ); ?></label>
            <input type="text" id="tpsm-shipping-fees-flat-rate-amount" name="tpsm-shipping-fees-flat-rate-amount" value="<?php echo $tpsm_shipping_fees_settings['falt-rate'] ?>">
        </div>

        <div class="tpsm-shipping-fees-container tpsm-setting-weight-base-container" id="tpsm-weight-range-fee" style="display: none;">
            
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
            'falt-rate' => $tpsm_shipping_fee_flat_rate_amount,
        ];
        
        update_option( 'tpsm-shipping-fees_settings', $tpsm_shipping_fees_settings_values );
        wp_redirect( admin_url( 'admin.php?page=shipping-manager' ) );
        exit;
    }
?>