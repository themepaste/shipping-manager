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
    public function __construct() {
        $this->tpsm_general_settings        = get_option( 'tpsm-general_settings' );
        $this->tpsm_weight_settings         = tpsm_get_shipping_fees_settings();
        $this->tpsm_box_shipping_settings   = tpsm_get_box_shipping_settings();

        $this->id                   = self::ID;
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
