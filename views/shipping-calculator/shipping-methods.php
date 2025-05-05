<?php 
defined( 'ABSPATH' ) || exit;

$shipping_methods = tpsm_get_available_shipping_methods(); ?>
	
<div class="product-shipping-methods">
    <h4>Available Shipping Methods:</h4>
    <ul>
        <?php
            if ( !empty( $shipping_methods['rates'] ) ) {
                foreach ( $shipping_methods['rates'] as $rate ) {
                    echo '<li>' . esc_html( $rate->get_label() ) . ' - ' . wc_price( $rate->get_cost() ) . '</li>';
                }
            } else {
                echo '<li>No shipping methods available for your location.</li>';
            }
        ?>
    </ul>
</div>