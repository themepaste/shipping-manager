<?php
/**
 * Shipping Manager Settings Fields
 *
 * @package ShippingManager
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'tpsm_get_settings_fields_for_screen' ) ) {
    /**
     * Map a settings screen slug to its field definitions.
     *
     * Returns an empty array for screens this plugin does not own (for example
     * tabs registered by the Pro add-on), so their own handlers stay in charge.
     *
     * @param string $screen_slug Settings screen slug.
     * @return array
     */
    function tpsm_get_settings_fields_for_screen( $screen_slug ) {
        switch ( $screen_slug ) {
            case 'free-shipping':
                return tpsm_free_shipping_settings_fields();
            case 'shipping-calculator':
                return tpsm_shipping_calculator_settings_fields();
        }

        return array();
    }
}

if ( ! function_exists( 'tpsm_sanitize_settings_fields' ) ) {
    /**
     * Read and sanitize a set of settings fields out of $_POST.
     *
     * Nonce and capability checks are the caller's responsibility.
     *
     * @param array  $fields      Field definitions keyed by setting name.
     * @param string $screen_slug Settings screen slug, used to build input names.
     * @return array Sanitized values keyed by setting name.
     */
    function tpsm_sanitize_settings_fields( $fields, $screen_slug ) {
        $values = array();

        foreach ( $fields as $key => $field ) {
            $field_name = 'tpsm-' . $screen_slug . '_' . $key;
            $type       = isset( $field['type'] ) ? $field['type'] : 'text';
            // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Nonce verified by the caller; each branch below sanitizes.
            $raw = isset( $_POST[ $field_name ] ) ? wp_unslash( $_POST[ $field_name ] ) : null;

            // Reject non-scalar submissions outright; sanitize_hex_color() is a
            // TypeError on PHP 8 if it is handed an array.
            $scalar = is_scalar( $raw ) ? (string) $raw : '';

            switch ( $type ) {
                case 'switch':
                    // An unchecked checkbox is simply absent from the request.
                    $values[ $key ] = null === $raw ? 0 : 1;
                    break;

                case 'picker':
                    $color          = sanitize_hex_color( $scalar );
                    $values[ $key ] = $color ? $color : '';
                    break;

                default:
                    $values[ $key ] = sanitize_text_field( $scalar );
                    break;
            }
        }

        return $values;
    }
}

if ( ! function_exists( 'tpsm_free_shipping_settings_fields' ) ) {
    /**
     * Free Shipping Settings Fields
     *
     * @return array
     */
    function tpsm_free_shipping_settings_fields() {
        return array(
            'hide-other' => array(
                'label' => __( 'Hide Other', 'shipping-manager' ),
                'type'  => 'switch',
                'value' => '',
                'desc'  => __( 'Hide other shipping methods when Free shipping is available.', 'shipping-manager' ),
            ),
            'minimum-amount' => array(
                'label' => __( 'Minimum Amount', 'shipping-manager' ),
                'type'  => 'switch',
                'value' => '',
                'desc'  => __( 'Enable custom minimum amount for free shipping. When disabled, standard WooCommerce settings will apply.', 'shipping-manager' ),
            ),
            'cart-amount' => array(
                'label' => __( 'Cart Amount', 'shipping-manager' ),
                'type'  => 'text',
                'value' => '',
                'desc'  => __( 'Cart minimum amount required for Free shipping.', 'shipping-manager' ),
            ),
            'free-shipping-bar' => array(
                'label' => __( 'Show Free Shipping Progress Bar', 'shipping-manager' ),
                'type'  => 'switch',
                'value' => '',
                'desc'  => __( 'Enable a Progress bar showing customers their Progress towards free shipping.', 'shipping-manager' ),
                'child-fields' => array(
                    'shipping-bar-message' => array(
                        'label' => __( 'Message', 'shipping-manager' ),
                        'type'  => 'text',
                        'value' => '',
                        'desc'  => __( 'Use [left_price] as a placeholder to show the remaining amount to qualify for free shipping.', 'shipping-manager' ),
                    ),
                    'shipping-bar-position' => array(
                        'label'   => __( 'Position', 'shipping-manager' ),
                        'type'    => 'select',
                        'value'   => '',
                        'desc'    => __( 'Position of the Progress bar.', 'shipping-manager' ),
                        'options' => array(
                            'Bottom' => __( 'Bottom', 'shipping-manager' ),
                            'Top'    => __( 'Top', 'shipping-manager' ),
                        ),
                    ),
                    'shipping-bar-alignment' => array(
                        'label'   => __( 'Alignment', 'shipping-manager' ),
                        'type'    => 'select',
                        'value'   => '',
                        'desc'    => __( 'Alignment of the Progress bar.', 'shipping-manager' ),
                        'options' => array(
                            'Left'   => __( 'Left', 'shipping-manager' ),
                            'Center' => __( 'Center', 'shipping-manager' ),
                            'Right'  => __( 'Right', 'shipping-manager' ),
                        ),
                    ),
                    'shipping-bar-text-color' => array(
                        'label' => __( 'Text Color', 'shipping-manager' ),
                        'type'  => 'picker',
                        'value' => '',
                        'desc'  => __( 'Text color of the Progress bar.', 'shipping-manager' ),
                    ),
                    'shipping-bar-background-color' => array(
                        'label' => __( 'Background Color', 'shipping-manager' ),
                        'type'  => 'picker',
                        'value' => '',
                        'desc'  => __( 'Background color of the Progress bar.', 'shipping-manager' ),
                    ),
                ),
            ),
        );
    }
}

if ( ! function_exists( 'tpsm_shipping_calculator_settings_fields' ) ) {
    /**
     * Shipping Calculator Settings Fields
     *
     * @return array
     */
    function tpsm_shipping_calculator_settings_fields() {
        return array(
            'shipping-calculator-enable' => array(
                'label' => __( 'Disable/Enable', 'shipping-manager' ),
                'type'  => 'switch',
                'value' => '',
                'desc'  => __( 'Enable or disable the shipping calculator. Default: enabled.', 'shipping-manager' ),
            ),
            'enable-location-field' => array(
                'label' => __( 'Location Form', 'shipping-manager' ),
                'type'  => 'switch',
                'value' => '',
                'desc'  => __( 'Enable or disable the location form. Default: enabled.', 'shipping-manager' ),
            ),
            'shipping-calculator-position' => array(
                'label'   => __( 'Position', 'shipping-manager' ),
                'type'    => 'select',
                'value'   => '',
                'desc'    => __( 'Placement of the shipping calculator.', 'shipping-manager' ),
                'options' => array(
                    'before-add-to-cart-button' => __( 'Before Add to Cart button', 'shipping-manager' ),
                    'after-add-to-cart-button'  => __( 'After Add to Cart button', 'shipping-manager' ),
                    'using-shortcode'           => __( '[tpsm-shipping-calculator/] Using Shortcode', 'shipping-manager' ),
                ),
            ),
        );
    }
}
