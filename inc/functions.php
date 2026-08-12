<?php

defined( 'ABSPATH' ) || exit;

require_once TPSM_PLUGIN_DIRNAME . '/inc/settings-fields.php';

/**
 * Shipping Manager Plugin - Settings and Shipping Methods
 *
 * @package ShippingManager
 */

/**
 * Returns the available settings options for the Shipping Manager plugin.
 *
 * @return array Associative array of settings options.
 */
if ( !function_exists( 'tpsm_settings_options' ) ) {
    function tpsm_settings_options() {
        return apply_filters(
            'tpsm_settings_options',
            array(
                // 'free-shipping' => array(
                //     'label' => __( 'Free Shipping', 'shipping-manager' ),
                //     'class' => '',
                // ),
                // 'shipping-calculator' => array(
                //     'label' => __( 'Shipping Calculator', 'shipping-manager' ),
                //     'class' => '',
                // ),
            )
        );
    }
}

/**
 * Get settings for Shipping Fees.
 *
 * @return mixed Option value from the database.
 */
if ( !function_exists( 'tpsm_get_shipping_fees_settings' ) ) {
    function tpsm_get_shipping_fees_settings() {
        return get_option( 'tpsm-shipping-fees_settings' );
    }
}

/**
 * Get settings for Box Shipping.
 *
 * @return mixed Option value from the database.
 */
if ( !function_exists( 'tpsm_get_box_shipping_settings' ) ) {
    function tpsm_get_box_shipping_settings() {
        return get_option( 'tpsm-box-shipping_settings' );
    }
}

/**
 * Get settings for Free Shipping.
 *
 * @return mixed Option value from the database.
 */
if ( !function_exists( 'tpsm_get_free_shipping_settings' ) ) {
    function tpsm_get_free_shipping_settings() {
        return get_option( 'tpsm-free-shipping_settings' );
    }
}

/**
 * Whether the frontend shipping calculator is switched on.
 *
 * Used to keep the calculator's CSS/JS (and its jQuery dependency) off pages
 * where the calculator is never rendered.
 *
 * @return bool
 */
if ( !function_exists( 'tpsm_is_shipping_calculator_enabled' ) ) {
    function tpsm_is_shipping_calculator_enabled() {
        $settings = get_option( 'tpsm-shipping-calculator_settings' );

        return is_array( $settings ) && !empty( $settings['shipping-calculator-enable'] );
    }
}

/**
 * Calculate and return available shipping methods for a single product.
 *
 * This function simulates a shipping package using the provided product ID and optional
 * destination details (country, state, postcode, city). It uses WooCommerce's internal
 * shipping method calculations to return a list of available shipping methods based on
 * the current settings and the destination.
 *
 * @param string|null $country   Optional. Destination country code. Defaults to the customer's shipping country.
 * @param string|null $state     Optional. Destination state code. Defaults to the customer's shipping state.
 * @param string|null $postcode  Optional. Destination postcode. Defaults to the customer's shipping postcode.
 * @param string|null $city      Optional. Destination city. Defaults to the customer's shipping city.
 * @param int|null    $product_id Required. ID of the WooCommerce product.
 *
 * @return array|false Returns an array of available shipping rates or false if not a valid product context.
 */
if ( !function_exists( 'tpsm_get_available_shipping_methods' ) ) {
    function tpsm_get_available_shipping_methods( $country = null, $state = null, $postcode = null, $city = null, $product_id = null ) {
        if ( !$product_id || !function_exists( 'wc_get_product' ) ) {
            return false;
        }

        $product = wc_get_product( $product_id );
        if ( !$product || !$product->needs_shipping() ) {
            return false;
        }

        // WC()->customer is only populated once WooCommerce has initialised its
        // session, so fall back to the store base location when it is missing.
        $customer = function_exists( 'WC' ) ? WC()->customer : null;

        if ( $customer ) {
            $country  = $country ?: $customer->get_shipping_country();
            $state    = $state ?: $customer->get_shipping_state();
            $postcode = $postcode ?: $customer->get_shipping_postcode();
            $city     = $city ?: $customer->get_shipping_city();
        }

        if ( !$country ) {
            $base     = wc_get_base_location();
            $country  = isset( $base['country'] ) ? $base['country'] : '';
            $state    = $state ?: ( isset( $base['state'] ) ? $base['state'] : '' );
        }

        $package = array(
            'contents'        => array(
                array(
                    'data'     => $product,
                    'quantity' => 1,
                ),
            ),
            'destination'     => array(
                'country'   => $country,
                'state'     => $state,
                'postcode'  => $postcode,
                'city'      => $city,
                'address'   => '',
                'address_2' => '',
            ),
            'user'            => array(),
            'contents_cost'   => $product->get_price(),
            'applied_coupons' => array(),
        );

        if ( !class_exists( 'WC_Shipping' ) ) {
            return false;
        }

        $shipping = WC_Shipping::instance();
        $shipping->load_shipping_methods();

        return $shipping->calculate_shipping_for_package( $package );
    }
}

/**
 * Outputs a disabled select dropdown field for the "Taxable" setting in the admin interface.
 *
 * This function generates an HTML select field for the taxable option (Yes/No) and marks the
 * correct value as selected. The field is rendered as disabled, serving as a read-only visual
 * indicator. To modify the value, users are instructed to change it via the Shipping Manager settings.
 *
 * @param string|null $is_taxable Optional. Whether the field is taxable ('yes' or 'no'). Defaults to 'no' if not provided.
 *
 * @return void Outputs HTML directly.
 */
if ( !function_exists( 'tpsm_taxable_field' ) ) {
    function tpsm_taxable_field( $is_taxable = null ) {
        if ( is_null( $is_taxable ) ) {
            $is_taxable = 'no';
        }?>
<div class="tpsm-field">
    <div class="tpsm-field-label">
        <label><?php esc_html_e( 'Taxable: ', 'shipping-manager' ); ?></label>
    </div>
    <div class="tpsm-field-input">
        <div class="tpsm-switch-wrapper">
            <?php
printf(
            '<select disabled>
                                    <option value="yes" %3$s>%1$s</option>
                                    <option value="no" %4$s>%2$s</option>
                                </select>',
            esc_html__( 'Yes', 'shipping-manager' ),
            esc_html__( 'No', 'shipping-manager' ),
            selected( $is_taxable, 'yes', false ),
            selected( $is_taxable, 'no', false ),
        );
        ?>

        </div>
        <p class="tpsm-field-desc">
            <?php esc_html_e( "'Yes' = Tax Included / 'No' = Tax excluded / Change it from Shipping Manager Settings", 'shipping-manager' ); ?>
        </p>
    </div>
</div>
<?php
}
}

/**
 * Checks if a value is set and returns it, or returns an empty string if not.
 *
 * This function acts as a safe helper for accessing potentially undefined values.
 * It avoids PHP notices or warnings that may occur when attempting to access
 * unset variables, especially in templating or dynamic settings contexts.
 *
 * @since 1.0.0
 *
 * @param mixed $value The value to check.
 * @return mixed|string Returns the original value if set, or an empty string if not set.
 */
if ( !function_exists( 'tpsm_isset' ) ) {
    function tpsm_isset( $value ) {
        if ( !isset( $value ) ) {
            return '';
        }

        return $value;
    }
}

/**
 * Retrieves a list of shipping condition types.
 *
 * @since 1.0.0
 *
 * @return array Associative array with condition types as keys and their labels as values.
 */
if ( !function_exists( 'tpsm_get_conditions_data' ) ) {
    function tpsm_get_conditions_data() {
        return [
            'tpsm-flat-rate'       => 'Flat Rate',
            'tpsm-cart-quantity'   => 'Quantity',
            'tpsm-sub-total-price' => 'Subtotal',
            'tpsm-total-price'     => 'Total',
            'tpsm-per-weight-unit' => 'Per Weight Unit (' . get_option( 'woocommerce_weight_unit' ) . ')',
            'tpsm-total-weight'    => 'Total Weight',
            'tpsm-shipping-class'  => 'Shipping Class',
        ];
    }
}

if ( !function_exists( 'tpsm_get_filter_operators' ) ) {
    function tpsm_get_filter_operators() {
        return [
            [
                'value' => 'equals',
                'label' => __( 'Equals', 'shipping-manager' ),
            ],
            [
                'value' => 'not-equals',
                'label' => __( 'Not equal', 'shipping-manager' ),
            ],
            [
                'value' => 'greater',
                'label' => __( 'Greater than', 'shipping-manager' ),
            ],
            [
                'value' => 'less',
                'label' => __( 'Less than', 'shipping-manager' ),
            ],
            [
                'value' => 'greater-equal',
                'label' => __( 'Greater than or equal to', 'shipping-manager' ),
            ],
            [
                'value' => 'less-equal',
                'label' => __( 'Less than or equal to', 'shipping-manager' ),
            ],
        ];
    }
}

/**
 * Plain-language description of what a rule condition compares.
 *
 * Used by the setup guide on the Shipping Manager settings section.
 *
 * @param string $condition Condition slug.
 * @return string
 */
if ( !function_exists( 'tpsm_get_condition_description' ) ) {
    function tpsm_get_condition_description( $condition ) {
        $descriptions = [
            'tpsm-flat-rate'       => __( 'always applies; use it for a base charge.', 'shipping-manager' ),
            'tpsm-cart-quantity'   => __( 'compares the number of items in the cart against a value.', 'shipping-manager' ),
            'tpsm-sub-total-price' => __( 'matches when the cart subtotal falls in a range.', 'shipping-manager' ),
            'tpsm-total-price'     => __( 'matches when the cart total falls in a range.', 'shipping-manager' ),
            'tpsm-per-weight-unit' => __( 'multiplies the cost by the total cart weight.', 'shipping-manager' ),
            'tpsm-total-weight'    => __( 'matches when the total cart weight falls in a range.', 'shipping-manager' ),
            'tpsm-shipping-class'  => __( 'applies when the cart contains any of the selected shipping classes.', 'shipping-manager' ),
        ];

        return $descriptions[$condition] ?? '';
    }
}

/**
 * Find every shipping zone the Shipping Manager method has been added to.
 *
 * Only ever called while rendering the admin settings section — it walks all
 * zones, which is too costly to do during rate calculation.
 *
 * @return array List of ['zone_name', 'method_title', 'rule_count', 'edit_url'].
 */
if ( !function_exists( 'tpsm_get_zone_usage' ) ) {
    function tpsm_get_zone_usage() {
        if ( !class_exists( 'WC_Shipping_Zones' ) ) {
            return [];
        }

        $method_id = \ThemePaste\ShippingManager\Classes\Shipping\RegisterShippingMethod::ID;
        $zones     = WC_Shipping_Zones::get_zones();

        // get_zones() omits the catch-all "Locations not covered" zone (ID 0).
        $zones[] = [
            'zone_name'        => WC_Shipping_Zones::get_zone( 0 )->get_zone_name(),
            'shipping_methods' => WC_Shipping_Zones::get_zone( 0 )->get_shipping_methods(),
        ];

        $usage = [];

        foreach ( $zones as $zone ) {
            if ( empty( $zone['shipping_methods'] ) ) {
                continue;
            }

            foreach ( $zone['shipping_methods'] as $method ) {
                if ( $method->id !== $method_id ) {
                    continue;
                }

                $rules = json_decode( (string) $method->get_option( 'tpsm_hidden' ), true );

                $usage[] = [
                    'zone_name'    => $zone['zone_name'],
                    'method_title' => $method->get_title(),
                    'rule_count'   => is_array( $rules ) ? count( $rules ) : 0,
                    'edit_url'     => add_query_arg(
                        [
                            'page'        => 'wc-settings',
                            'tab'         => 'shipping',
                            'instance_id' => $method->get_instance_id(),
                        ],
                        admin_url( 'admin.php' )
                    ),
                ];
            }
        }

        return $usage;
    }
}

/**
 * Back-compat aliases.
 *
 * The original names were unprefixed and generic enough to collide with other
 * plugins or themes in the global namespace. They are kept so that an older Pro
 * add-on keeps working, but new code should use the prefixed versions.
 */
if ( !function_exists( 'get_conditions_data' ) ) {
    function get_conditions_data() {
        return tpsm_get_conditions_data();
    }
}

if ( !function_exists( 'get_filter_operators' ) ) {
    function get_filter_operators() {
        return tpsm_get_filter_operators();
    }
}

if ( !function_exists( 'tpsm_saved_remote_data' ) ) {

/**
 * Sends the current user's information to a remote server.
 *
 * This function retrieves the currently logged-in user's full name,
 * email address, and the site URL, and sends this data to a specified
 * remote server endpoint using a POST request. This is intended to
 * collect user data for integration with a remote service.
 *
 * @return void
 */
    function tpsm_saved_remote_data() {
        $current_user = wp_get_current_user();

        // Check if a user is logged in
        if ( !$current_user || 0 === $current_user->ID ) {
            return;
        }

        // Get full name (first + last or fallback to display name)
        $full_name = trim( $current_user->first_name . ' ' . $current_user->last_name );
        if ( empty( $full_name ) ) {
            $full_name = $current_user->display_name;
        }

        $email_address = $current_user->user_email;
        $site_url = get_site_url();

        // Fire and forget: the opt-in redirect must not stall (or fail) because
        // the remote endpoint is slow or unreachable.
        wp_remote_post( 'https://themepaste.com/wp-json/v2/collect-email/shipping-manager', [
            'timeout'  => 5,
            'blocking' => false,
            'headers'  => [
                'X-Auth-Token' => 'c7fc312817194d30c79da538204eaec3',
                'Content-Type' => 'application/json',
            ],
            'body'     => wp_json_encode( [
                'email_address' => $email_address,
                'full_name'     => $full_name,
                'site_url'      => $site_url,
            ] ),
        ] );

    }
}