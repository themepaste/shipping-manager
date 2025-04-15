<?php 

namespace ThemePaste\ShippingManager\Classes;

defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Helpers\Utility;
use ThemePaste\ShippingManager\Traits\Asset;
use ThemePaste\ShippingManager\Traits\Hook;

class Settings {

    use Hook;
    use Asset;

    /**
     * Intialize the plugin setting page
     */
    public function init() {
        $this->action( 'admin_menu', [$this, 'shipping_manager_setting_page'] );
        $this->action( 'admin_enqueue_scripts', [$this, 'admin_enqueue_css'] );
        $this->action( 'admin_enqueue_scripts', [$this, 'admin_enqueue_scripts'] );
        $this->filter( 'woocommerce_shipping_methods', [$this, 'tpsm_add_shipping_method'] );
    }

    public function tpsm_add_shipping_method( $methods ) {
        $methods['tpsm-shipping-manager'] = ShippingMethod::class;
        return $methods;
    }

    /**
     * Load all admin stylesheet
     */
    public function admin_enqueue_css( $screen ) {
        if( 'toplevel_page_'.'shipping-manager' == $screen ) {
            $this->enqueue_style( 
                'tpsm-settings',
                TPSM_ASSETS_URL . '/admin/css/settings.css'
            );
            $this->enqueue_style( 
                'tpsm-fields',
                TPSM_ASSETS_URL . '/admin/css/fields.css'
            );
        }
    }

    /**
     * Load All admin javascrit files
     */
    public function admin_enqueue_scripts( $screen ) {
        if( 'toplevel_page_'.'shipping-manager' == $screen ) {
            $this->enqueue_script( 
                'tpsm-settings',
                TPSM_ASSETS_URL . '/admin/js/settings.js'
            );
        }
    }

    /**
     * Register/Add submenu page as shipping manager settings page 
     */
    public function shipping_manager_setting_page() {
        if ( class_exists( 'WooCommerce' ) ) {
            add_menu_page(                                           
                __( 'Shipping Manager', 'shipping-manager' ),            // Page title
                __( 'Shipping Manager', 'shipping-manager' ),            // Menu title
                'manage_options',                                        // Capability
                'shipping-manager',                                      // Menu slug
                [$this, 'settings_page_layout'],                         // Callback function
                'dashicons-airplane',
                56
            );
            add_submenu_page(     
                'shipping-manager',                                             // parent slug
                __( 'Shipping Manager Pro 🟢', 'shipping-manager' ),            // Page title
                __( 'Shipping Manager Pro 🟢', 'shipping-manager' ),            // Menu title
                'manage_options',                                               // Capability
                'shipping-manager-pro',                                         // Menu slug
                [$this, 'settings_page_layout_pro'],                            // Callback function
            );
        }
    }

    /**
     * Calling setting page layout
     */
    public function settings_page_layout() {
        if ( ! isset( $_GET['tpsm-setting'] ) ) {
            $redirect_url = add_query_arg(
                [
                    'page' => 'shipping-manager',
                    'tpsm-setting' => 'shipping-fees',
                ],
                admin_url( 'admin.php' )
            );
            wp_safe_redirect( $redirect_url );
            exit;
        }
        printf( Utility::get_template( 'settings/layout.php' ) );
    }

    /**
     * Calling setting page layout pro
     */
    public function settings_page_layout_pro() {
       esc_html_e( 'Pro Features Loading...', 'shipping-manager' );
    }
}