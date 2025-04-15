<?php defined( 'ABSPATH' ) || exit; ?>

<div class="tpsm-shipping-fees-wrapper">
    <form method="POST">
        <?php wp_nonce_field( 'tpsm-nonce_action', 'tpsm-nonce_name' ); ?>
        <div class="tpsm-setting-row">
            <label><?php esc_html_e( 'Disable/Enable:', 'shipping-manager' ); ?></label>
            <input class="tpsm-switch" type="checkbox" id="tpsm-shipping-fees-disable-enable" name="tpsm-shipping-fees-disable-enable" /><label for="tpsm-shipping-fees-disable-enable" class="tpsm-switch-label"></label>
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
            <label for="tpsm-flat-rate-amount"><?php esc_html_e( 'Flat rate Per Unit:', 'shipping-manager' ); ?></label>
            <input type="text" id="tpsm-flat-rate-amount">
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

        $tpsm_nonce = isset( $_POST['tpsm-nonce_name'] ) ? sanitize_text_field( $_POST['tpsm-nonce_name'] ) : '';
        $tpsm_nonce = isset( $_POST['tpsm-nonce_name'] ) ? sanitize_text_field( $_POST['tpsm-nonce_name'] ) : '';

    }
?>