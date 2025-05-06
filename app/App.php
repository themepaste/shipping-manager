<?php
/**
 * Main application class for initializing the plugin.
 *
 * @package ThemePaste\ShippingManager
 */

namespace ThemePaste\ShippingManager;

defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Traits\Hook;

/**
 * Final Class App
 *
 * Responsible for bootstrapping all necessary plugin components.
 */
final class App {

	use Hook;

	/**
	 * Initialize all plugin hooks and core components.
	 *
	 * This method sets up both frontend and backend functionalities.
	 *
	 * @return void
	 */
	public static function hooks() {

		// Register activation-related setup such as DB installation, version check, etc.
		new Classes\Install();

		// Load common functionality (AJAX, scripts, etc.)
		new Classes\Common();

		// Register shipping methods setup.
		new Classes\Shipping\ShippingMethod();

		// Register admin-specific hooks and classes.
		if ( is_admin() ) {
			new Classes\Admin();
		}

		// Register frontend-specific hooks and classes.
		if ( ! is_admin() ) {
			new Classes\Front();
		}

		// You may add any other classes that run in both admin and frontend here.
	}
}
