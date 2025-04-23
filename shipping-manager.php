<?php 
/*
 * Plugin Name:       Shipping Manager
 * Plugin URI:        https://themepaste.com/product/wordpress-plugins/shipping-manager-for-woocommerce
 * Description:       An simple plugin to manage your woocommerce store shipping 
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Requires Plugins:  woocommerce
 * Author:            ThemePaste
 * Author URI:        https://themepaste.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       shipping-manager
 */

if (!defined('ABSPATH')) {
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
        $this->include();
        $this->define();
        ThemePaste\ShippingManager\App::hooks();
    }

     /**
     * Include all needed files
     */
    private function include() {
        require_once( dirname( __FILE__ ) . '/inc/functions.php' );
        require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );
    }

    /**
     * define all constant
     */
    private function define() {
       define( "DEVS", true ); // 'true' | is development mode on

       define( 'TPSM_PLUGIN_FILE', __FILE__ );
       define( 'TPSM_PLUGIN_VERSION', '1.0.0' );
       define( 'TPSM_PLUGIN_BASENAME', plugin_basename( TPSM_PLUGIN_FILE ) );
       define( 'TPSM_PLUGIN_DIR', plugin_dir_path( TPSM_PLUGIN_FILE ) );
       define( 'TPSM_PLUGIN_URL', plugin_dir_url( TPSM_PLUGIN_FILE ) );
       define( 'TPSM_ASSETS_URL', plugins_url( 'assets', TPSM_PLUGIN_FILE ) );

       if( DEVS ) {
           define( 'TPSM_ASSETS_VERSION', time() );
       }
       else {
           define( 'TPSM_ASSETS_VERSION', TPSM_PLUGIN_VERSION );
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