<?php
namespace Themepaste\ShippingManager\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Manages admin assets for admin settings
 *
 * @since TSM_SINCE
 */
class Assets {
	/**
	 * Unique id for initialized object
	 *
	 * @since TSM_SINCE
	 */
	const INSTANCE_KEY = 'admin_assets';

	/**
	 * General style handle
	 *
	 * @since TSM_SINCE
	 */
	const GENERAL_STYLE = 'tsm-general-style';

	/**
	 * Handle for free shipping page script
	 *
	 * @since TSM_SINCE
	 */
	const FREE_SHIPPING_SCRIPT = 'tsm-free-shipping-script';

	/**
	 * Initializes:
	 * Hooks for assets registration and enqueuing
	 *
	 * @since TSM_SINCE
	 *
	 * @return void
	 */
	public function __construct() {
		// Register assets
		add_action( 'admin_enqueue_scripts', [ $this, 'register_style' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'register_script' ] );

		// Load assets
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
	}

	/**
	 * Generates assets URL
	 *
	 * @since TSM_SINCE
	 *
	 * @param string $relative_url_path
	 *
	 * @return string
	 */
	private function get_assets_url( string $relative_url_path = '' ): string {
		if ( defined( 'TSM_DEVELOP') ) {
			$assets_root_url = TSM_ASSET_ROOT_URL . 'assets/build/';
		} else {
			$assets_root_url = TSM_ASSET_ROOT_URL . 'assets/';
		}

		return $assets_root_url . $relative_url_path;
	}

	/**
	 * Fetches the plugin version and returns it
	 *
	 * @since TSM_SINCE
	 *
	 * @return string
	 */
	private function get_plugin_version(): string {
		return TSM_SHIPPING_MANAGER_PLUGIN_VERSION;
	}

	/**
	 * Registers styles for admin
	 *
	 * @since TSM_SINCE
	 *
	 * @return void
	 */
	public function register_style() {
		// general.css
		wp_register_style(
			self::GENERAL_STYLE,
			$this->get_assets_url( 'admin/css/general.css' ),
			[],
			$this->get_plugin_version()
		);
	}

	/**
	 * Register scripts for admin
	 *
	 * @since TSM_SINCE
	 *
	 * @return void
	 */
	public function register_script() {
		wp_register_script(
			self::FREE_SHIPPING_SCRIPT,
			$this->get_assets_url( 'admin/js/free-shipping.js' ),
			[ 'jquery' ],
			$this->get_plugin_version(),
			true
		);
	}

	/**
	 * Enqueues assets depending on necessity
	 *
	 * @since TSM_SINCE
	 *
	 * @return void
	 */
	public function enqueue_assets() {
		if ( tsm_is_admin_dashboard() ) {
			wp_enqueue_style( self::GENERAL_STYLE );
			switch ( tsm_current_admin_settings_page() ) {
				case Routes::FREE_SHIPPING:
					wp_enqueue_script( self::FREE_SHIPPING_SCRIPT );
					break;
				default:
					break;
			}
		}
	}
}