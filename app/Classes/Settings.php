<?php
/**
 * Settings Page Class
 *
 * Handles the settings page registration and assets for the Shipping Manager plugin.
 *
 * @package ThemePaste\ShippingManager
 */

namespace ThemePaste\ShippingManager\Classes;

defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Helpers\Utility;
use ThemePaste\ShippingManager\Traits\Asset;
use ThemePaste\ShippingManager\Traits\Hook;

/**
 * Class Settings
 *
 * Register settings page, enqueue assets, and add settings link in plugin list.
 */
class Settings {

    use Hook;
    use Asset;

    /**
     * Settings Page Slug
     */
    const SETTING_PAGE_ID = 'shipping-manager';
    public $setting_page_url;

    /**
     * Initialize the plugin settings page and hook into WordPress actions/filters.
     *
     * @return void
     */
    public function init() {

        $this->setting_page_url = add_query_arg(
            [
                'page' => self::SETTING_PAGE_ID,
            ],
            admin_url( 'admin.php' )
        );

        $this->action( 'admin_head', [$this, 'remove_save_button'] );
        $this->action( 'admin_init', [$this, 'redirect_to_settings'] );
        $this->action( 'admin_menu', [ $this, 'shipping_manager_setting_page' ] );
        $this->action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_css' ] );
        $this->action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
        $this->filter( 'plugin_action_links_' . TPSM_PLUGIN_BASENAME, [ $this, 'settings_link' ] );
        $this->filter( 'tpsm_settings_options', [$this, 'add_setting_option_in_setting_page'] );
    }

    public function add_setting_option_in_setting_page( $options ) {
        $options['general'] = array(
            'label' => __( 'Settings', 'shipping-manager' ),
            'class' => '',
        );

        return $options;
    }

    public function remove_save_button() {
        $screen = get_current_screen();
        if ( $screen && $screen->id === 'woocommerce_page_wc-settings' && isset( $_GET['section'] ) && $_GET['section'] === self::SETTING_PAGE_ID ) {
            echo '<style>
                .woocommerce-save-button { display: none !important; }
            </style>';
        }
    }

    public function redirect_to_settings() {
        if ( get_transient( 'tpsm_do_activation_redirect' ) ) {
            delete_transient('tpsm_do_activation_redirect');
    
            // Avoid redirecting during multi-site network admin activation
            if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
                return;
            }
    
            // Redirect to the plugin settings page
            wp_safe_redirect( $this->setting_page_url );
            exit;
        }
    }

    /**
     * Add a 'Settings' link on the plugins page.
     *
     * @param array $links Existing plugin action links.
     * @return array Modified plugin action links.
     */
    public function settings_link( $links ) {
        $settings_link = sprintf(
            '<a href="%1$s">%2$s</a>',
            esc_url( $this->setting_page_url ),
            esc_html__( 'Settings', 'shipping-manager' )
        );

        array_unshift( $links, $settings_link );

        return $links;
    }

    /**
     * Enqueue admin CSS styles on the settings page.
     *
     * @param string $screen Current admin screen ID.
     * @return void
     */
    public function admin_enqueue_css( $screen ) {
        if ( 'toplevel_page_' . self::SETTING_PAGE_ID === $screen ) {
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
     * Enqueue admin JavaScript files on the settings page.
     *
     * @param string $screen Current admin screen ID.
     * @return void
     */
    public function admin_enqueue_scripts( $screen ) {
        if ( 'toplevel_page_' . self::SETTING_PAGE_ID === $screen ) {
            $this->enqueue_script(
                'tpsm-settings',
                TPSM_ASSETS_URL . '/admin/js/settings.js'
            );
        }
    }

    /**
     * Register the top-level menu page for the Shipping Manager settings.
     *
     * @return void
     */
    public function shipping_manager_setting_page() {
        if ( class_exists( 'WooCommerce' ) ) {
            add_menu_page(
                esc_html__( 'Shipping Manager', 'shipping-manager' ),
                esc_html__( 'Shipping Manager', 'shipping-manager' ),
                'manage_options',
                self::SETTING_PAGE_ID,
                [ $this, 'settings_page_layout' ],
                'dashicons-airplane',
                56
            );
        }
    }

    /**
     * Load the main settings page layout.
     *
     * @return void
     */
    public function settings_page_layout() {
        if ( ! isset( $_GET['tpsm-setting'] ) ) {
            $redirect_url = add_query_arg(
                [
                    'tpsm-setting'  => 'shipping-fees',
                ],
                $this->setting_page_url
            );

            wp_safe_redirect( $redirect_url );
            exit;
        }

        printf( '%s', Utility::get_template( 'settings/layout.php' ) );
    }

    /**
     * Placeholder for Pro settings layout.
     *
     * @return void
     */
    public function settings_page_layout_pro() {
        esc_html_e( 'Pro Features Loading...', 'shipping-manager' );
    }
}
