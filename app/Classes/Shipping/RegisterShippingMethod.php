<?php 
namespace ThemePaste\ShippingManager\Classes\Shipping;

defined( 'ABSPATH' ) || exit;

use \WC_Shipping_Method;

class RegisterShippingMethod extends WC_Shipping_Method {

    const ID = 'shipping-manager';
    public $tpsm_weight_settings;
    public $tpsm_box_shipping_settings;
    public $tpsm_enable;
    public $tpsm_general_settings;

    /**
     * Constructor for your shipping class
     */
    public function __construct( ) {
        
        $this->tpsm_general_settings        = get_option( 'tpsm-general_settings' );
        $this->tpsm_weight_settings         = tpsm_get_shipping_fees_settings();
        $this->tpsm_box_shipping_settings   = tpsm_get_box_shipping_settings();

        $this->id                   = self::ID;
        $this->method_title         = __( 'Shipping Manager', 'shipping-manager');
        $this->method_description   = __( 'Shipping manager Method description', 'shipping-manager' );
        $this->enabled              = $this->is_enable();
        $this->title                = $this->method_name();

        $this->init();
    }

    private function method_name() {
        return ! empty( $this->tpsm_general_settings['method-title'] ) && isset( $this->tpsm_general_settings['method-title'] ) ? $this->tpsm_general_settings['method-title'] : __( 'Shipping Manager', 'shipping-manager');
    }

    private function is_enable() {
        return ! empty( $this->tpsm_general_settings['is-plugin-enable'] ) && isset( $this->tpsm_general_settings['is-plugin-enable'] ) ? ( $this->tpsm_minimum_amount_setting() || $this->get_tpsm_cost() ? 'yes' : 'no' ) : 'no';
    }

    private function get_tpsm_cost() {
        if ( $is_enable = $this->tpsm_minimum_amount_setting() ) {
            return 0;
        }else {
            return apply_filters( 'tpsm_shipping_fees_cost', 0 );
        }
    }

    // if it true than minimun order will be fire and calculate the minimumn order 
    private function tpsm_minimum_amount_setting() {
        return apply_filters( 'tpsm_minimum_amount_setting', false );
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

    public function init_form_fields() {

        $setting_page = add_query_arg( 
            array(
                'page'          => self::ID,
            ),
            admin_url( 'admin.php' )
        );

        $this->form_fields = array(
            'custom_button' => array(
                // 'title'       => __( 'Set up your store Global Shipping Rules', 'your-text-domain' ),
                'type'        => 'title',
                'description' => '<a href="' . $setting_page . '" class="button button-primary">Go to Plugin Settings</a>',
            ),
        );
    } 

    private function is_tpsm_plugin_taxable() {
        if( ! empty( $this->tpsm_general_settings['is-plugin-taxable'] ) && isset( $this->tpsm_general_settings['is-plugin-taxable'] ) ) {
            if( 'yes' == $this->tpsm_general_settings['is-plugin-taxable'] ) {
                return true;
            }
        }
        return false;
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
        );

        if( $this->is_tpsm_plugin_taxable() ) {
            $rate['calc_tax'] = 'per_order';
        } else {
            $rate['taxes']     = false;
            $rate['calc_tax']  = '';
            $rate['tax_class'] = 'none';
        }

        // Register the rate
        $this->add_rate( $rate );
    }
}