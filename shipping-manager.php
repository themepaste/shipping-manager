<?php 
/*
 * Plugin Name:       Shipping Manager
 * Plugin URI:        https://themepaste.com/product/wordpress-plugins/shipping-manager-for-woocommerce
 * Description:       Powerful WooCommerce shipping plugin with table rate, weight-based rates, shipping class support, and advanced shipping rules. 
 * Version:           1.1.3
 * Requires at least: 5.8
 * Requires PHP:      7.0
 * Requires Plugins:  woocommerce
 * Author:            ThemePaste
 * Author URI:        https://themepaste.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       shipping-manager
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
        ThemePaste\ShippingManager\App::hooks();
    }

    /**
     * define all constant
     */
    private function define() {
       define( "TPSM_DEVS", false ); // 'true' | is development mode on

       define( 'TPSM_PLUGIN_FILE', __FILE__ );
       define( 'TPSM_PLUGIN_VERSION', '1.1.3' );
       define( 'TPSM_PLUGIN_DIRNAME', dirname( TPSM_PLUGIN_FILE ) );
       define( 'TPSM_PLUGIN_BASENAME', plugin_basename( TPSM_PLUGIN_FILE ) );
       define( 'TPSM_PLUGIN_DIR', plugin_dir_path( TPSM_PLUGIN_FILE ) );
       define( 'TPSM_PLUGIN_URL', plugin_dir_url( TPSM_PLUGIN_FILE ) );
       define( 'TPSM_ASSETS_URL', plugins_url( 'assets', TPSM_PLUGIN_FILE ) );
       define( 'TPSM_REAL_PATH', realpath( dirname( TPSM_PLUGIN_DIR ) ) );

       if( TPSM_DEVS ) {
           define( 'TPSM_ASSETS_VERSION', time() );
       }
       else {
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
        if ( ! class_exists( 'ComposerAutoloaderInitTPShippingManager' ) ) {
            require_once dirname( __FILE__ ) . '/vendor/autoload.php';
        }
    }

    /**
     * Singleton Instance
    */
    static function get_instance() {
        
        if( ! self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}

/**
 * Plugin Start
 */
ShippingManager::get_instance();