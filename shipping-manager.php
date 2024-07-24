<?php
/**
 * Plugin Name: Shipping Manager
 * Version: 1.0.0
 * Description: An simple plugin to manage your shipping
 * Text Domain: shipping-manager
 */

require 'vendor/autoload.php';

use Themepaste\ShippingManager\ShippingManager;

defined( 'TSM_SHIPPING_MANAGER_PLUGIN_VERSION' ) || define( 'TSM_SHIPPING_MANAGER_PLUGIN_VERSION', '1.0.0' );
defined( 'TSM_PLUGIN_ROOT_PATH' ) || define( 'TSM_PLUGIN_ROOT_PATH', dirname( __FILE__ ) );
defined( 'TSM_ASSET_ROOT_URL' ) || define( 'TSM_ASSET_ROOT_URL', plugin_dir_url( __FILE__ ) );

ShippingManager::get_instance();
