<?php 
    defined( 'ABSPATH' ) || exit; 
?>
 
<div class="tpsm-setting-wrapper">
    <div class="tpsm-box-shipping-wrapper">
        <form method="POST">
            <?php wp_nonce_field( 'tpsm-nonce_action', 'tpsm-nonce_name' ); ?>
    
            <!-- Switch for enable disable  -->
            <div class="tpsm-setting-row">
                <label><?php esc_html_e( 'Disable/Enable:', 'shipping-manager' ); ?></label>
                <input class="tpsm-switch" type="checkbox" id="tpsm-box-shipping-disable-enable" name="tpsm-box-shipping-disable-enable" /><label for="tpsm-box-shipping-disable-enable" class="tpsm-switch-label"></label>
            </div>
    
            <div class="tpsm-save-button">
                <button type="submit" name="tpsm-box-shipping_submit"><?php esc_html_e( 'Save', 'shipping-manager' ); ?></button>
            </div>
    
        </form>
    
    </div>
</div>