<?php
/**
 * Register Custom Shipping Method - Shipping Manager
 *
 * @package ThemePaste\ShippingManager
 */

namespace ThemePaste\ShippingManager\Classes\Shipping;

defined( 'ABSPATH' ) || exit;

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
     * General settings.
     *
     * @var array
     */
    static public $tpsm_general_settings = [ 'method-title' => '' ];

    /**
     * Constructor.
     */
    public function __construct( $instance_id = 0 ) {
        self::$tpsm_general_settings = get_option( 'tpsm-general_settings' );

        $this->id                   = self::ID;
        $this->instance_id          = absint( $instance_id );
        $this->title                = __( 'Shipping Manager', 'shipping-manager' ); 
        $this->method_title         = self::$tpsm_general_settings['method-title'] ?? __( 'Shipping Manager', 'shipping-manager' );
        $this->method_description   = __( 'One solution for all shipping needs', 'shipping-manager' );;
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
        $this->method_description = $shipping_method_desc ;
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
     */
    public function init_form_fields() {
        // Link to your plugin settings page
        $plugin_settings_url = add_query_arg(
            array(
                'page' => self::ID,
            ),
            admin_url( 'admin.php' )
        );

        // Link to WooCommerce Shipping Zones settings
        $shipping_zones_url = admin_url( 'admin.php?page=wc-settings&tab=shipping' );

        $this->form_fields = array(
            'custom_buttons_section' => array(
                'title'       => __( 'Choose your settings', 'shipping-manager' ),
                'type'        => 'title',
                'description' => 
                    '<a href="' . esc_url( $plugin_settings_url ) . '" class="button button-primary" style="margin-right: 10px;">' . 
                        esc_html__( 'Plugin Settings', 'shipping-manager' ) . 
                    '</a>' .
                    '<a href="' . esc_url( $shipping_zones_url ) . '" class="button button-secondary">' . 
                        esc_html__( 'Zone Specific Settings', 'shipping-manager' ) . 
                    '</a>',
            ),
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
                'description' => 'Import/Export the data',
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

        if ( ! $this->get_tpsm_cost( $data ) || $this->get_tpsm_cost( $data ) == 0 ) {
            return;
        }

        $rate = array(
            'id'    => $this->id . ':' . $this->instance_id,
            'label' => $this->title,
            'cost'  => $this->get_tpsm_cost( $data ),
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
