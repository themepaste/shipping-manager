<?php 
    defined( 'ABSPATH' ) || exit; 
    $weight_unit        = get_option( 'woocommerce_weight_unit' );
    $dimension_unit     = get_option( 'woocommerce_dimension_unit' );
    $currency_symbol    = get_woocommerce_currency_symbol();
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

            <!-- Box size Shipping -->
            <div class="tpsm-box-size-shipping-wrapper">
                <h1>Box Size Shipping Settings</h1>

                <div class="tpsm-box-size-repeater tpsm-box-size-shipping-table-wrapper">
                    <div class="tpsm-box-size-repeater-row">
                        <div class="tpsm-column-1">Length (<?php echo $dimension_unit;?> )</div>
                        <div class="tpsm-column-2">Width (<?php echo $dimension_unit;?> )</div>
                        <div class="tpsm-column-3">Height (<?php echo $dimension_unit;?> )</div>
                        <div class="tpsm-column-4">Dimensions (<?php echo $dimension_unit;?><sup>3</sup> )</div>
                        <div class="tpsm-column-4">Fee (<?php echo $currency_symbol;?> )</div>
                        <div class="tpsm-column-4">Action</div>
                        <!-- <div class="tpsm-column-4">Fees per kg</div> -->
                    </div>
                    <div class="tpsm-box-size-repeater-row">
                        <div class="tpsm-column-1">
                            <input type="text">
                        </div>
                        <div class="tpsm-column-2">
                            <input type="text">
                        </div>
                        <div class="tpsm-column-3">
                            <input type="text">
                        </div>
                        <div class="tpsm-column-4">
                            <input type="text">
                        </div>
                        <div class="tpsm-column-4">
                            <input type="text">
                        </div>
                        <div class="tpsm-column-4">
                            <button>Delete</button>
                        </div>
                        <!-- <div class="tpsm-column-4">Fees per kg</div> -->
                    </div>
                </div>

                <div class="tpsm-addrow-button">
                    <button type="button" id="tpsm-weight-range-pricing-add">Add New</button>
                </div>
            </div>
    
            <div class="tpsm-save-button">
                <button type="submit" name="tpsm-box-shipping_submit"><?php esc_html_e( 'Save', 'shipping-manager' ); ?></button>
            </div>
    
        </form>
    
    </div>
</div>