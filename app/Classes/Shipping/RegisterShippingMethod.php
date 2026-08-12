<?php
/**
 * Register Custom Shipping Method - Shipping Manager
 *
 * @package ThemePaste\ShippingManager
 */

namespace ThemePaste\ShippingManager\Classes\Shipping;

defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Helpers\Utility;
use WC_Shipping_Method;

/**
 * Class RegisterShippingMethod
 *
 * Handles the custom shipping method: Shipping Manager.
 */
class RegisterShippingMethod extends WC_Shipping_Method {

    /**
     * Shipping Method ID
     */
    const ID = 'shipping-manager';

    /**
     * Constructor.
     */
    public function __construct( $instance_id = 0 ) {
        $this->id                   = self::ID;
        $this->instance_id          = absint( $instance_id );
        // The per-instance "Method Name" field below overrides this, so there is
        // no separate site-wide title setting.
        $this->title                = __( 'Shipping Manager', 'shipping-manager' );
        $this->method_title         = __( 'Shipping Manager', 'shipping-manager' );
        $this->method_description   = __( 'One solution for all shipping needs', 'shipping-manager' );
        $this->enabled              = 'yes';

        $this->supports = array(
            'settings',
            'shipping-zones',
            'instance-settings',
        );

        $this->init();

        $shipping_method_name   = $this->get_option( 'method_name', $this->method_title );
        $shipping_method_desc   = $this->get_option( 'method_description', $this->method_description );

        $this->title              = $shipping_method_name;
        $this->method_title       = $shipping_method_name;
        $this->method_description = $shipping_method_desc;

        // WC_Shipping_Method::add_rate() stamps $this->tax_status onto every
        // rate and uses it in is_taxable(); without this it always stayed at
        // the class default regardless of the merchant's choice.
        $this->tax_status = $this->get_option( 'tax_status', 'taxable' );
    }

    /**
     * Initialize settings and hooks.
     */
    public function init() {
        $this->init_form_fields();
        $this->init_settings();
        $this->init_instance_form_fields();

        // Hook to save admin settings.
        add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
    }

    /**
     * Define form fields for admin settings.
     *
     * The global (non-instance) section renders a setup guide via
     * admin_options() instead of a settings form, so there are no fields here.
     */
    public function init_form_fields() {
        $this->form_fields = array();
    }

    /**
     * Render the settings screen for this method.
     *
     * WooCommerce calls this for BOTH screens:
     *  - the global "Shipping Manager" section (instance_id 0), where we show
     *    the setup guide;
     *  - a zone's method instance (instance_id > 0), which must keep rendering
     *    WooCommerce's normal settings form — method name, tax status,
     *    Import/Export and the container the React rules table mounts into.
     *
     * @return void
     */
    public function admin_options() {
        if ( $this->instance_id ) {
            parent::admin_options();
            return;
        }

        $shipping_zones_url = add_query_arg(
            array(
                'page' => 'wc-settings',
                'tab'  => 'shipping',
            ),
            admin_url( 'admin.php' )
        );

        echo Utility::get_template( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Template escapes at the point of use.
            'settings/method-guide.php',
            array(
                'zone_usage'  => tpsm_get_zone_usage(),
                'conditions'  => tpsm_get_conditions_data(),
                'zones_url'   => $shipping_zones_url,
                'classes_url' => add_query_arg( array( 'section' => 'classes' ), $shipping_zones_url ),
                'tax_url'     => add_query_arg(
                    array(
                        'page' => 'wc-settings',
                        'tab'  => 'tax',
                    ),
                    admin_url( 'admin.php' )
                ),
                'store'       => array(
                    'currency'         => get_woocommerce_currency(),
                    'weight_unit'      => get_option( 'woocommerce_weight_unit' ),
                    'shipping_classes' => count( WC()->shipping()->get_shipping_classes() ),
                    'taxes_enabled'    => wc_tax_enabled(),
                ),
            )
        );
    }

    /**
	 * Our method to initialize our form fields for separate instances.
	 *
	 * @return void
	 */
	private function init_instance_form_fields() {

		// Start the array of fields.
		$fields = array(
			'method_name'      => array(
				'title'       => __( 'Method Name', 'shipping-manager' ),
				'type'        => 'text',
				'description' => __( 'Your customers will see the name of this shipping method during checkout.', 'shipping-manager' ),
				'default'     => __( 'Shipping Manager', 'shipping-manager' ),
				'placeholder' => __( 'e.g. Standard national', 'shipping-manager' ),
				'desc_tip'    => true,
			),
            'method_description'      => array(
				'title'       => __( 'Method Description', 'shipping-manager' ),
				'type'        => 'text',
				'description' => __( 'Your customers will see the description of this shipping method during checkout.', 'shipping-manager' ),
				'default'     => __( 'Shipping Manager Description', 'shipping-manager' ),
				'placeholder' => __( 'e.g. Standard national', 'shipping-manager' ),
				'desc_tip'    => true,
			),
			'tax_status' => array(
				'title'   => __( 'Tax status', 'shipping-manager' ),
				'type'    => 'select',
				'class'   => 'wc-enhanced-select',
				'default' => 'taxable',
				'options' => array(
					'taxable' => __( 'Taxable', 'shipping-manager' ),
					'none'    => _x( 'None', 'Tax status', 'shipping-manager' ),
				),
			),
            //This is actual value that is need to be calucate to getting actual cost
            'tpsm_hidden' => array(
                'title'       => 'Import/Export',
                'type'        => 'text',
                'default'     => '',
                'description' => 'Paste rule data copied from another shipping method to quickly import all rules.',
            ),
            //This is a div to show the react container
            'custom_repeater_ui' => array(
                'type'        => 'title',
                'title'       => 'Shipping Rules',
                'description' => '<div id="tpsm-shipping-rules-wrapper"></div>',
            ),
		);

		// And finally we set the instance_form_fields property for the Shipping API to use.
		$this->instance_form_fields = $fields;
	}

    /**
     * Calculate shipping cost.
     *
     * @param array $package Shipping package.
     */
    public function calculate_shipping( $package = array() ) {

        $tax_status = $this->get_option( 'tax_status', 'taxable' );
        $data       = $this->get_option( 'tpsm_hidden' ); // Here we get a json format all condition and data

        $cost = (float) $this->get_tpsm_cost( $data );

        if ( $cost <= 0 ) {
            return;
        }

        $rate = array(
            'id'    => $this->id . ':' . $this->instance_id,
            'label' => $this->title,
            'cost'  => $cost,
            'calc_tax' => 'per_order',
        );

         // Apply tax only if tax_status is 'taxable'
        if ( $tax_status === 'taxable' ) {
            $rate['taxes'] = ''; // Let WooCommerce calculate taxes
        } else {
            $rate['taxes'] = false; // No tax applied
        }

        // Register the shipping rate.
        $this->add_rate( $rate );
    }

    /**
     * Get the shipping cost.
     *
     * @return float
     */
    private function get_tpsm_cost( $data ) {

        return apply_filters( 'tpsm_shipping_fees_cost', $data );
    }

    /**
     * Check if minimum amount setting is enabled.
     *
     * @return bool
     */
    private function tpsm_minimum_amount_setting() {
        return apply_filters( 'tpsm_minimum_amount_setting', false );
    }
}
