<?php defined( 'ABSPATH' ) || exit; ?>  

<div id="tpsm-shipping-calculator-location-form">
    <form action="">
        <p>
            <select name="tpsm-shipping-calculator-country" id="tpsm-shipping-calculator-country" class="tpsm-shipping-calculator-country">
                <?php
                $countries = WC()->countries->get_countries();
                foreach ( $countries as $code => $name ) {
                    $selected = $code === 'BD' ? 'selected' : '';
                    printf( '<option value="%1$s" %2$s>%3$s</option>', $code, $selected, $name ); 
                    echo "";
                }
                ?>
            </select>
        </p>
        <p><input type="text" id="tpsm-shipping-calculator_state" placeholder="State"></p>
        <p><input type="text" id="tpsm-shipping-calculator_city" placeholder="City"></p>
        <p><input type="text" id="tpsm-shipping-calculator_postcode" placeholder="Postcode"></p>
        <button type="button" id="tpsm-shipping-calculator_save_location_btn"><?php esc_html_e( 'Update Location', 'shipping-manager' ); ?></button>
       
    </form>
</div>