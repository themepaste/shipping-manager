<?php
/**
 * Plugin Name: Shipping Manager
 * Version: 1.2.1
 * Description: An simple plugin to manage your shipping
 * Requires Plugins: woocommerce
 * Text Domain: tps-manager
 * License: GPLv3-or-later
 */

defined( 'ABSPATH' ) || exit;

require 'vendor/autoload.php';

use Themepaste\ShippingManager\ShippingManager;

defined( 'TPS_MANAGER_SHIPPING_MANAGER_PLUGIN_VERSION' ) || define( 'TPS_MANAGER_SHIPPING_MANAGER_PLUGIN_VERSION', '1.1.0' );
defined( 'TPS_MANAGER_PLUGIN_ROOT_PATH' ) || define( 'TPS_MANAGER_PLUGIN_ROOT_PATH', dirname( __FILE__ ) );
defined( 'TPS_MANAGER_ASSET_ROOT_URL' ) || define( 'TPS_MANAGER_ASSET_ROOT_URL', plugin_dir_url( __FILE__ ) );

ShippingManager::get_instance();