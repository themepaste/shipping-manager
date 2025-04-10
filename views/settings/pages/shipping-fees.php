<?php defined( 'ABSPATH' ) || exit; ?>

<div class="tpsm-shipping-fees-wrapper">
    <form method="POST">
        <div class="tpsm-setting-row">
            <label>Disable/Enable:</label>
            <input class="tpsm-switch" type="checkbox" id="tpsm-shipping-fees-disable-enable" /><label for="tpsm-shipping-fees-disable-enable" class="tpsm-switch-label"></label>
        </div>

        <div class="tpsm-setting-row tpsm-setting-radio-wrapper">
            <div class="tpsm-shipping-radio">
                <input type="radio" id="tpsm-flat-rate-fee" name="tpsm-shipping-fee">
                <label for="tpsm-flat-rate-fee">Flat Rate Fee</label>
            </div>
            <div class="tpsm-shipping-radio">
                <input type="radio" id="tpsm-weight-base-fee" name="tpsm-shipping-fee">
                <label for="tpsm-weight-base-fee">Weight Base Fee</label>
            </div>
        </div>

        <div class="tpsm-setting-flat-rat-container">
            <label for="tpsm-flat-rate-amount">Flat rate Amount:</label>
            <input type="text" id="tpsm-flat-rate-amount">
        </div>


        <div class="tpsm-save-button">
            <button type="submit"><?php esc_html_e( 'Save', 'shipping-manager' ); ?></button>
        </div>
    </form>
    
</div>