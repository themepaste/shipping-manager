<?php
/*
 * Plugin Name:       Shipping Manager
 * Plugin URI:        https://themepaste.com/product/wordpress-plugins/shipping-manager-for-woocommerce
 * Description:       Powerful WooCommerce shipping plugin with table rate, weight-based rates, shipping class support, and advanced shipping rules.
 * Version:           1.2.7
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Requires Plugins:  woocommerce
 * Author:            ThemePaste
 * Author URI:        https://themepaste.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       shipping-manager
 * WC requires at least: 6.6
 * WC tested up to:   11.0
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

/**
 * Plugin Main Class
 */
final class ShippingManager {
    static $instance = false;

    /**
     * Class Constructor
     */
    private function __construct() {
        $this->define();
        $this->include();

        // WooCommerce is a hard dependency. The "Requires Plugins" header covers
        // WP 6.5+, but older installs (and manual WooCommerce deactivation) can
        // still leave this plugin running alone, so bail out gracefully instead
        // of fataling on the first WC() call.
        add_action( 'plugins_loaded', [$this, 'boot'] );
        add_action( 'before_woocommerce_init', [$this, 'declare_woocommerce_compatibility'] );
    }

    /**
     * Boot the plugin once all plugins are loaded.
     */
    public function boot() {
        if ( !$this->is_woocommerce_active() ) {
            add_action( 'admin_notices', [$this, 'woocommerce_missing_notice'] );
            return;
        }

        ThemePaste\ShippingManager\App::hooks();
    }

    /**
     * Whether WooCommerce is available.
     *
     * @return bool
     */
    private function is_woocommerce_active() {
        return class_exists( 'WooCommerce' ) && function_exists( 'WC' );
    }

    /**
     * Tell WooCommerce this plugin is compatible with HPOS and the block-based
     * cart/checkout, otherwise WooCommerce flags it as incompatible.
     */
    public function declare_woocommerce_compatibility() {
        if ( !class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
            return;
        }

        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', TPSM_PLUGIN_FILE, true );
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', TPSM_PLUGIN_FILE, true );
    }

    /**
     * Admin notice shown when WooCommerce is not available.
     */
    public function woocommerce_missing_notice() {
        if ( !current_user_can( 'activate_plugins' ) ) {
            return;
        }

        printf(
            '<div class="notice notice-error"><p>%s</p></div>',
            esc_html__( 'Shipping Manager requires WooCommerce to be installed and active.', 'shipping-manager' )
        );
    }

    /**
     * define all constant
     */
    private function define() {
        define( "TPSM_DEVS", false ); // 'true' | is development mode on

        define( 'TPSM_PLUGIN_FILE', __FILE__ );
        define( 'TPSM_PLUGIN_VERSION', '1.2.7' );
        define( 'TPSM_PLUGIN_DIRNAME', dirname( TPSM_PLUGIN_FILE ) );
        define( 'TPSM_PLUGIN_BASENAME', plugin_basename( TPSM_PLUGIN_FILE ) );
        define( 'TPSM_PLUGIN_DIR', plugin_dir_path( TPSM_PLUGIN_FILE ) );
        define( 'TPSM_PLUGIN_URL', plugin_dir_url( TPSM_PLUGIN_FILE ) );
        define( 'TPSM_ASSETS_URL', plugins_url( 'assets', TPSM_PLUGIN_FILE ) );
        define( 'TPSM_REAL_PATH', realpath( dirname( TPSM_PLUGIN_DIR ) ) );

        if ( TPSM_DEVS ) {
            define( 'TPSM_ASSETS_VERSION', time() );
        } else {
            define( 'TPSM_ASSETS_VERSION', TPSM_PLUGIN_VERSION );
        }
    }

    /**
     * Include all needed files
     */
    private function include() {
        // Include custom helper functions from the inc/functions.php file
        require_once dirname( __FILE__ ) . '/inc/functions.php';

        /**
         * Check if the Composer autoloader class for TPShippingManager exists.
         *
         * The class name usually includes the suffix defined in the composer.json
         * file, typically something like 'ComposerAutoloaderInitTPShippingManager'.
         *
         * If the class does not exist, include the Composer autoloader file to
         * register the necessary autoload mappings.
         */
        if ( !class_exists( 'ComposerAutoloaderInitTPShippingManager' ) ) {
            require_once dirname( __FILE__ ) . '/vendor/autoload.php';
        }
    }

    /**
     * Singleton Instance
     */
    static function get_instance() {

        if ( !self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}

/**
 * Plugin Start
 */
ShippingManager::get_instance();

/**
 * Activation hook.
 *
 * Registered on the main plugin file rather than from inside a class that is
 * only instantiated on `plugins_loaded`, so first-time activation defaults are
 * always written even though the plugin boots late.
 */
register_activation_hook( __FILE__, [\ThemePaste\ShippingManager\Classes\Install::class, 'bootstrapping'] );
