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
     * Shipping fees settings.
     *
     * @var array
     */
    public $tpsm_weight_settings;

    /**
     * Box shipping settings.
     *
     * @var array
     */
    public $tpsm_box_shipping_settings;

    /**
     * General settings.
     *
     * @var array
     */
    public $tpsm_general_settings;

    /**
     * Constructor.
     */
    public function __construct( $instance_id = 0 ) {
        $this->tpsm_general_settings        = get_option( 'tpsm-general_settings' );
        $this->tpsm_weight_settings         = tpsm_get_shipping_fees_settings();
        $this->tpsm_box_shipping_settings   = tpsm_get_box_shipping_settings();

        $this->id                   = self::ID;
        $this->instance_id          = absint( $instance_id );
        $this->method_title         = __( 'Shipping Manager', 'shipping-manager' );
        $this->method_description   = __( 'One solution for all shipping needs', 'shipping-manager' );
        $this->enabled              = $this->is_enable();
        $this->title                = $this->method_name();

        $this->supports = array(
            'settings',
            'shipping-zones',
            'instance-settings',
        );

        $this->init();
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
        $setting_page = add_query_arg(
            array(
                'page' => self::ID,
            ),
            admin_url( 'admin.php' )
        );

        $this->form_fields = array(
            'custom_button' => array(
                'type'        => 'title',
                'description' => '<a href="' . esc_url( $setting_page ) . '" class="button button-primary">' . esc_html__( 'Go to Plugin Settings', 'shipping-manager' ) . '</a>',
            ),
        );
    }

    /**
	 * Our method to initialize our form fields for separate instances.
	 *
	 * @return void
	 */
	private function init_instance_form_fields() {
		// Define some strings that will be used several times for the cost decription and link.
		$cost_desc = __( 'Enter a cost (excl. tax).', 'your_text_domain' );
		$cost_link = sprintf( '<span id="wc-shipping-advanced-costs-help-text">%s <a target="_blank" href="https://woocommerce.com/document/flat-rate-shipping/#advanced-costs">%s</a>.</span>', __( 'Charge a flat rate per item, or enter a cost formula to charge a percentage based cost or a minimum fee. Learn more about', 'your_text_domain' ), __( 'advanced costs', 'your_text_domain' ) );

		// Start the array of fields.
		$fields = array(
			'title'      => array(
				'title'       => __( 'Name', 'your_text_domain' ),
				'type'        => 'text',
				'description' => __( 'Your customers will see the name of this shipping method during checkout.', 'your_text_domain' ),
				'default'     => __( 'Your shipping method', 'your_text_domain' ),
				'placeholder' => __( 'e.g. Standard national', 'your_text_domain' ),
				'desc_tip'    => true,
			),
			'tax_status' => array(
				'title'   => __( 'Tax status', 'your_text_domain' ),
				'type'    => 'select',
				'class'   => 'wc-enhanced-select',
				'default' => 'taxable',
				'options' => array(
					'taxable' => __( 'Taxable', 'your_text_domain' ),
					'none'    => _x( 'None', 'Tax status', 'your_text_domain' ),
				),
			),
			'cost'       => array(
				'title'             => __( 'Cost', 'your_text_domain' ),
				'type'              => 'text',
				'class'             => 'wc-shipping-modal-price',
				'placeholder'       => '',
				'description'       => $cost_desc,
				'default'           => '0',
				'desc_tip'          => true,
				'sanitize_callback' => array( $this, 'sanitize_cost' ),
			),
		);

		/**
		 * Flat rate shipping has the ability to add rates per shipping class, so here we get the shipping classes and then provide fields
		 * for merchants/admins to use to be able to specify these costs. 
		 */
		$shipping_classes = WC()->shipping()->get_shipping_classes();

		if ( ! empty( $shipping_classes ) ) {
			$fields['class_costs'] = array(
				'title'       => __( 'Shipping class costs', 'your_text_domain' ),
				'type'        => 'title',
				'default'     => '',
				/* translators: %s: URL for link */
				'description' => sprintf( __( 'These costs can optionally be added based on the <a target="_blank" href="%s">product shipping class</a>. Learn more about <a target="_blank" href="https://woocommerce.com/document/flat-rate-shipping/#shipping-classes">setting shipping class costs</a>.', 'your_text_domain' ), admin_url( 'admin.php?page=wc-settings&tab=shipping&section=classes' ) ),
			);
			foreach ( $shipping_classes as $shipping_class ) {
				if ( ! isset( $shipping_class->term_id ) ) {
					continue;
				}
				$fields[ 'class_cost_' . $shipping_class->term_id ] = array(
					/* translators: %s: shipping class name */
					'title'             => sprintf( __( '"%s" shipping class cost', 'your_text_domain' ), esc_html( $shipping_class->name ) ),
					'type'              => 'text',
					'class'             => 'wc-shipping-modal-price',
					'placeholder'       => __( 'N/A', 'your_text_domain' ),
					'description'       => $cost_desc,
					'default'           => $this->get_option( 'class_cost_' . $shipping_class->slug ),
					'desc_tip'          => true,
					'sanitize_callback' => array( $this, 'sanitize_cost' ),
				);
			}

			$fields['no_class_cost'] = array(
				'title'             => __( 'No shipping class cost', 'your_text_domain' ),
				'type'              => 'text',
				'class'             => 'wc-shipping-modal-price',
				'placeholder'       => __( 'N/A', 'your_text_domain' ),
				'description'       => $cost_desc,
				'default'           => '',
				'desc_tip'          => true,
				'sanitize_callback' => array( $this, 'sanitize_cost' ),
			);

			$fields['type'] = array(
				'title'       => __( 'Calculation type', 'your_text_domain' ),
				'type'        => 'select',
				'class'       => 'wc-enhanced-select',
				'default'     => 'class',
				'options'     => array(
					'class' => __( 'Per class: Charge shipping for each shipping class individually', 'your_text_domain' ),
					'order' => __( 'Per order: Charge shipping for the most expensive shipping class', 'your_text_domain' ),
				),
				'description' => $cost_link,
			);
		}

		// And finally we set the instance_form_fields property for the Shipping API to use.
		$this->instance_form_fields = $fields;
	}

    

    /**
     * Calculate shipping cost.
     *
     * @param array $package Shipping package.
     */
    public function calculate_shipping( $package = array() ) {
        $rate = array(
            
            'label' => $this->title,
            'cost'  => $this->get_tpsm_cost(),
        );

        if ( $this->is_tpsm_plugin_taxable() ) {
            $rate['calc_tax'] = 'per_order';
        } else {
            $rate['taxes']     = false;
            $rate['calc_tax']  = '';
            $rate['tax_class'] = 'none';
        }

        // Register the shipping rate.
        $this->add_rate( $rate );
    }

    /**
     * Get the shipping method title.
     *
     * @return string
     */
    private function method_name() {
        return ! empty( $this->tpsm_general_settings['method-title'] )
            ? $this->tpsm_general_settings['method-title']
            : __( 'Shipping Manager', 'shipping-manager' );
    }

    /**
     * Check if the shipping method is enabled.
     *
     * @return string 'yes' or 'no'
     */
    private function is_enable() {
        if( ! isset( $this->tpsm_general_settings['is-plugin-enable'] )  ) {
            // initially its always true 
            return 'yes';
        }
        else if ( ! empty( $this->tpsm_general_settings['is-plugin-enable'] ) ) {
            return ( $this->tpsm_minimum_amount_setting() || $this->get_tpsm_cost() ) ? 'yes' : 'no';
        }
        return 'no';
    }

    /**
     * Get the shipping cost.
     *
     * @return float
     */
    private function get_tpsm_cost() {
        if ( $this->tpsm_minimum_amount_setting() ) {
            return 0;
        } else {
            return apply_filters( 'tpsm_shipping_fees_cost', 0 );
        }
    }

    /**
     * Check if minimum amount setting is enabled.
     *
     * @return bool
     */
    private function tpsm_minimum_amount_setting() {
        return apply_filters( 'tpsm_minimum_amount_setting', false );
    }

    /**
     * Check if the shipping method is taxable.
     *
     * @return bool
     */
    private function is_tpsm_plugin_taxable() {
        if ( ! empty( $this->tpsm_general_settings['is-plugin-taxable'] ) ) {
            return ( 'yes' === $this->tpsm_general_settings['is-plugin-taxable'] );
        }

        return false;
    }
}
