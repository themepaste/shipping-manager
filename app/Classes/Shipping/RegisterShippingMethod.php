<?php 
namespace ThemePaste\ShippingManager\Classes\Shipping;

defined( 'ABSPATH' ) || exit;

use \WC_Shipping_Method;

class RegisterShippingMethod extends WC_Shipping_Method {

    const ID = 'shipping-manager';
    public $tpsm_weight_settings;
    public $tpsm_box_shipping_settings;
    public $tpsm_enable;

    /**
     * Constructor for your shipping class
     */
    public function __construct( ) {
        
        $this->tpsm_weight_settings = tpsm_get_shipping_fees_settings();
        $this->tpsm_box_shipping_settings = tpsm_get_box_shipping_settings();

        // $this->tpsm_enable = in_array( 'yes', array( 
        //     $a = $this->is_enable( $this->tpsm_weight_settings ),
        //     $b = $this->is_enable( $this->tpsm_box_shipping_settings )
        // ) ) ?
        // 'yes' : 'no' ;

        $this->id                   = self::ID;
        $this->method_title         = __( 'Shipping Manager Method', 'shipping-manager');
        $this->method_description   = __( 'Shipping manager Method description', 'shipping-manager' );
        $this->enabled              =  $this->get_tpsm_cost() ? 'yes' : 'no';
        $this->title                = isset( $this->settings['title'] ) ? $this->settings['title'] : __( 'Shipping Manager', 'shipping-manager' );

        $this->init();
    }

    private function is_enable( $settings ) {
        return isset( $this->$settings['enabled'] ) && ! empty( $this->$settings['enabled'] ) &&  $cost = $this->get_tpsm_cost() ? 'yes' : 'no';
    }

    private function get_tpsm_cost() {
        return apply_filters( 'tpsm_shipping_fees_cost', 0 );
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
     * calculate_shipping function.
     *
     * @access public
     * @param array $package
     * @return void
     */
    public function calculate_shipping( $package = array() ) {
        $rate = array(
            'label'     => $this->title,
            'cost'      => $this->get_tpsm_cost(),
            'calc_tax'  => 'per_item'
        );

        // Register the rate
        $this->add_rate( $rate );
    }
}