<?php
/**
 * Plugin Name: Shipping Manager
 * Description: A simplified all in one shipping solution for WooCommerce.
 * Version: 1.00.0
 * Requires Plugins: woocommerce
 *
 * Requires PHP: 7.2
 * Text Domain: tsm-shipping-manager
 */

defined('ABSPATH') || exit; // Security check

defined( 'TSM_ROOT_FILE_PATH' ) || define( 'TSM_ROOT_FILE_PATH', __DIR__ );
defined( 'TSM_ROOT_FOLDER_URL' ) || define( 'TSM_ROOT_FOLDER_URL', plugin_dir_url( __FILE__ ) );

require_once "vendor/autoload.php";

\Themepaste\ShippingManager\ShippingManager::init();