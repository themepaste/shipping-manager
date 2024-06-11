<?php
/**
 * Plugin Name: Shipping Manager
 * Version: 1.0.0
 * Description: An simple plugin to manage your shipping
 * Text Domain: shipping-manager
 */

require 'vendor/autoload.php';

use Themepaste\ShippingManager\ShippingManager;

ShippingManager::get_instance();
