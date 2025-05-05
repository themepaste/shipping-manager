<?php
/**
 * Shipping Calculator Location Form
 *
 * This template renders a shipping calculator form with country, state, city, and postcode fields.
 * It is designed for use in a WooCommerce-based WordPress site.
 *
 * @package ShippingManager
 */

defined( 'ABSPATH' ) || exit;

?>

<div id="tpsm-shipping-calculator-location-form">
    <form action="" method="post">
        <p>
            <label for="tpsm-shipping-calculator-country"><?php esc_html_e( 'Country', 'shipping-manager' ); ?></label>
            <select name="tpsm-shipping-calculator-country" id="tpsm-shipping-calculator-country" class="tpsm-shipping-calculator-country">
                <?php
                // Get all countries from WooCommerce
                $countries = WC()->countries->get_countries();

                // Loop through each country and display as an option
                foreach ( $countries as $code => $name ) {
                    $selected = ( $code === 'BD' ) ? 'selected' : '';
                    printf(
                        '<option value="%1$s" %2$s>%3$s</option>',
                        esc_attr( $code ),
                        esc_attr( $selected ),
                        esc_html( $name )
                    );
                }
                ?>
            </select>
        </p>

        <p>
            <label for="tpsm-shipping-calculator_state"><?php esc_html_e( 'State', 'shipping-manager' ); ?></label>
            <input type="text" id="tpsm-shipping-calculator_state" name="tpsm-shipping-calculator_state" placeholder="<?php esc_attr_e( 'State', 'shipping-manager' ); ?>" />
        </p>

        <p>
            <label for="tpsm-shipping-calculator_city"><?php esc_html_e( 'City', 'shipping-manager' ); ?></label>
            <input type="text" id="tpsm-shipping-calculator_city" name="tpsm-shipping-calculator_city" placeholder="<?php esc_attr_e( 'City', 'shipping-manager' ); ?>" />
        </p>

        <p>
            <label for="tpsm-shipping-calculator_postcode"><?php esc_html_e( 'Postcode', 'shipping-manager' ); ?></label>
            <input type="text" id="tpsm-shipping-calculator_postcode" name="tpsm-shipping-calculator_postcode" placeholder="<?php esc_attr_e( 'Postcode', 'shipping-manager' ); ?>" />
        </p>

        <button type="button" id="tpsm-shipping-calculator_save_location_btn">
            <?php esc_html_e( 'Update Location', 'shipping-manager' ); ?>
        </button>
    </form>
</div>
