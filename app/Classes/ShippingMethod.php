<?php 
namespace ThemePaste\ShippingManager\Classes;

defined( 'ABSPATH' ) || exit;

use \WC_Shipping_Method;

class ShippingMethod extends WC_Shipping_Method {
    /**
     * Constructor for your shipping class
     */
    public function __construct( $instance_id = 0 ) {
        $this->id           = 'shipping-manager';
        $this->instance_id  = absint( $instance_id );
        $this->method_title = __( 'Shipping Manager Method', 'shipping-manager');
        $this->method_description = __('Shipping manager Method description');
        $this->supports = array(
            'shipping-zones',
            'instance-settings',
        );
        
        $this->init();
        
        $this->enabled = isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : 'yes';
        $this->title = isset( $this->settings['title'] ) ? $this->settings['title'] : __( 'Shipping Manager', 'habib-shipping' );
    }

    /**
     * Init your settings
     */
    public function init() {
        $this->init_form_fields();
        $this->init_settings();
        
        // Save settings in admin
        add_action( 'woocommerce_update_options_shipping_' . $this->id, [$this, 'process_admin_options'] );
    }

    /**
     * Define settings field for this shipping
     */
    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title' => __( 'Enable', 'habib-shipping' ),
                'type' => 'checkbox',
                'description' => __( 'Enable this shipping method', 'habib-shipping' ),
                'default' => 'yes'
            ),
            'title' => array(
                'title' => __('Title', 'habib-shipping'),
                'type' => 'text',
                'description' => __('Title to be displayed at checkout', 'habib-shipping'),
                'default' => __('Habib Shipping', 'habib-shipping')
            ),
        );
    }

    /**
     * Calculate shipping costs
     */
    public function calculate_shipping($package = array()) {
        $rate = array(
            'id' => $this->id,
            'label' => $this->title,
            'cost' => 20,
            'calc_tax' => 'per_item'
        );
        
        $this->add_rate( $rate );
    }
}