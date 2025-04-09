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
        $this->action( 'admin_enqueue_scripts', [$this, 'admin_enqueue_scripts'] );
    }

    /**
     * Load all admin stylesheet
     */
    public function admin_enqueue_scripts() {
        $this->enqueue_style( 
            'tpsm-settings',
            TPSM_ASSETS_URL . '/admin/settings.css'
        );
    }

    /**
     * Register/Add submenu page as shipping manager settings page 
     */
    public function shipping_manager_setting_page() {
        if ( class_exists( 'WooCommerce' ) ) {
            add_submenu_page(
                'woocommerce',                                           // Parent slug
                __( 'Shipping Manager', 'shipping-manager' ),            // Page title
                __( 'Shipping Manager', 'shipping-manager' ),            // Menu title
                'manage_options',                                        // Capability
                'shipping-manager',                            // Menu slug
                [$this, 'settings_page_layout']                          // Callback function
            );
        }
    }

    /**
     * Calling setting page layout
     */
    public function settings_page_layout() {
        echo Utility::get_template( 'settings/layout.php' );
    }
}