<?php
/**
 * Display available shipping methods.
 *
 * This template shows the available shipping rates for a product,
 * pulling from the `$shipping_methods` array.
 *
 * @package Shipping_Manager
 */

defined( 'ABSPATH' ) || exit;

// Retrieve shipping methods from the provided arguments.
// tpsm_get_available_shipping_methods() returns false when it cannot build a
// package, so this is not guaranteed to be an array.
$shipping_methods = isset( $args['shipping-methods'] ) && is_array( $args['shipping-methods'] )
    ? $args['shipping-methods']
    : array();

?>

<div class="product-shipping-methods">
    <h4><?php esc_html_e( 'Available shipping methods for your location:', 'shipping-manager' ); ?></h4>
    <ul>
        <?php
        // Check if shipping rates are available.
        if ( ! empty( $shipping_methods['rates'] ) ) {
            foreach ( $shipping_methods['rates'] as $rate ) {
                // Output each shipping method's label and cost.
                printf(
                    '<li>%s - %s</li>',
                    esc_html( $rate->get_label() ),
                    wp_kses_post( wc_price( $rate->get_cost() ) ) // wc_price() returns markup.
                );
            }
        } else {
            // Message when no shipping methods are available.
            echo '<li>' . esc_html__( 'No shipping methods available for your location.', 'shipping-manager' ) . '</li>';
        }
        ?>
    </ul>
</div>
