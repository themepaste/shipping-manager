<?php
namespace Themepaste\ShippingManager;

defined( 'ABSPATH' ) || exit;

/**
 * Manages the assets
 *
 * @since TSM_SINCE
 */
class Assets {
	const INSTANCE_KEY = 'Assets';
	const ADMIN_SHIPPING_SETTINGS_SCRIPT = 'tsm_admin_shipping_settings_script';
	const ADMIN_SHIPPING_SETTINGS_STYLE = 'tsm_admin_shipping_settings_style';

	/**
	 * Initializes the object
	 *
	 * @since TSM_SINCE
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'register_admin_scripts' ] );
		add_action('admin_enqueue_scripts', [ $this, 'register_admin_styles' ]);
	}

	/**
	 * Registers all admin scripts
	 *
	 * @since TSM_SINCE
	 */
	function register_admin_scripts() {

		wp_register_script(
			self::ADMIN_SHIPPING_SETTINGS_SCRIPT,
			TSM_ROOT_FOLDER_URL . 'assets/js/admin/shipping-settings.js'
		);
	}

	function register_admin_styles() {
		wp_enqueue_style(
			self::ADMIN_SHIPPING_SETTINGS_STYLE,
			TSM_ROOT_FOLDER_URL . 'assets/css/admin/shipping-settings.css'
		);
	}
}

