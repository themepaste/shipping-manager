<?php defined( 'ABSPATH' ) || exit; ?>

<div class="tpsm-shipping-fees-wrapper">
    <form method="POST">
        <div class="tpsm-setting-row">
            <label><?php esc_html_e( 'Disable/Enable:', 'shipping-manager' ); ?></label>
            <input class="tpsm-switch" type="checkbox" id="tpsm-shipping-fees-disable-enable" /><label for="tpsm-shipping-fees-disable-enable" class="tpsm-switch-label"></label>
        </div>

        <div class="tpsm-setting-row tpsm-setting-radio-wrapper">
            <div class="tpsm-shipping-radio">
                <input type="radio" id="tpsm-flat-rate-fee" name="tpsm-shipping-fee" value="tpsm-flat-rate-fee_value">
                <label for="tpsm-flat-rate-fee"><?php esc_html_e( 'Unit Weight Fee', 'shipping-manager' ) ?></label>
            </div>
            <div class="tpsm-shipping-radio">
                <input type="radio" id="tpsm-weight-base-fee" name="tpsm-shipping-fee" value="tpsm-flat-weight-bas-fee_value">
                <label for="tpsm-weight-base-fee"><?php esc_html_e( 'Weight Range Pricing', 'shipping-manager' ); ?></label>
            </div>
        </div>

        <div class="tpsm-shipping-fees-container tpsm-setting-flat-rat-container" id="tpsm-flat-rate-fee_value">
            <label for="tpsm-flat-rate-amount"><?php esc_html_e( 'Flat rate:', 'shipping-manager' ); ?></label>
            <input type="text" id="tpsm-flat-rate-amount">
        </div>

        <div class="tpsm-shipping-fees-container tpsm-setting-weight-base-container" id="tpsm-flat-weight-bas-fee_value" style="display: none;">
            
        </div>


        <div class="tpsm-save-button">
            <button type="submit"><?php esc_html_e( 'Save', 'shipping-manager' ); ?></button>
        </div>
    </form>
    
</div>