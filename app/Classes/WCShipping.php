<?php 
namespace ThemePaste\ShippingManager\Classes;

defined( 'ABSPATH' ) || exit;

class WCShipping extends \WC_Shipping_Method {
    
    public function __construct() {
        $this->id = 'habib_shipping';
        $this->method_title = __('Habib Shipping', 'woocommerce');
        $this->method_description = __('Custom flat rate shipping method.', 'woocommerce');

        $this->enabled = "yes";
        $this->title = "Habib Shipping";

        $this->init();
    }

    function init() {
        // Load settings API
        $this->init_form_fields();
        $this->init_settings();

        $this->title = $this->get_option('title');

        // Save settings
        add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
    }

    public function init_form_fields() {
        $this->form_fields = array(
            'title' => array(
                'title'       => __('Method Title', 'woocommerce'),
                'type'        => 'text',
                'description' => __('Title to be displayed on site', 'woocommerce'),
                'default'     => __('Habib Shipping', 'woocommerce')
            )
        );
    }

    public function calculate_shipping($package = array()) {
        $rate = array(
            'label' => $this->title,
            'cost' => 10.00,
            'calc_tax' => 'per_order'
        );

        // Add the rate
        $this->add_rate($rate);
    }
}