<?php 
namespace ThemePaste\ShippingManager\Classes\Shipping;

defined( 'ABSPATH' ) || exit;

use \WC_Shipping_Method;

class RegisterShippingMethod extends WC_Shipping_Method {

    const ID = 'shipping-manager';
    public $tpsm_settings;

    /**
     * Constructor for your shipping class
     */
    public function __construct( ) {
        $this->tpsm_settings    = tpsm_get_shipping_fees_settings();
        $this->id               = self::ID;
        $this->method_title     = __( 'Shipping Manager Method', 'shipping-manager');
        $this->method_description = __( 'Shipping manager Method description', 'shipping-manager' );
        $this->enabled          = isset( $this->tpsm_settings['enabled'] ) && !empty( $this->tpsm_settings['enabled'] ) ? 'yes' : 'no';
        $this->title            = isset( $this->settings['title'] ) ? $this->settings['title'] : __( 'Shipping Manager', 'shipping-manager' );

        $this->init();
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
    // public function init_form_fields() {
    //     $this->form_fields = array(
    //         'enabled' => array(
    //             'title' => __( 'Enable', 'shipping-manager' ),
    //             'type' => 'checkbox',
    //             'description' => __( 'Enable this shipping method', 'shipping-manager' ),
    //             'default' => 'yes'
    //         ),
    //         'title' => array(
    //             'title' => __( 'Title', 'shipping-manager' ),
    //             'type' => 'text',
    //             'description' => __( 'Title to be displayed at checkout', 'shipping-manager' ),
    //             'default' => __( 'Shipping Manager', 'shipping-manager' )
    //         ),
    //     );
    // }

    /**
     * calculate_shipping function.
     *
     * @access public
     * @param array $package
     * @return void
     */
    public function calculate_shipping( $package = array() ) {
        $cost = apply_filters( 'tpsm_shipping_fees_cost', 0.00 );
        $rate = array(
            'label'     => $this->title,
            'cost'      => $cost,
            'calc_tax'  => 'per_item'
        );

        // Register the rate
        $this->add_rate( $rate );
    }
}