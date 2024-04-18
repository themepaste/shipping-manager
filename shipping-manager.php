<?php
/**
 * Plugin Name: Shipping Manager
 * Description: A simplified all in one shipping solution for WooCommerce.
 * Version: 1.00.0
 * Requires Plugins: woocommerce
 *
 * Requires PHP: 7.2
 */

defined('ABSPATH') || exit; // Security check

require_once "vendor/autoload.php";

\Themepaste\ShippingManager\ShippingManager::init();