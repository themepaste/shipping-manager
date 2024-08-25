<?php
namespace Themepaste\ShippingManager\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Manages admin dashboard menu
 *
 * @since 1.1.0
 */
class Menu {
	/**
	 * Unique id for initialized object
	 *
	 * @since 1.1.0
	 */
	const INSTANCE_KEY = 'admin_menu';

	/**
	 * Initializes:
	 * 	WooCommerce submenu hook
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
	}

	/**
	 * Invokes submenu for WooCommerce main menu
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function add_menu(): void {
		add_submenu_page(
			'woocommerce',
			__( 'Shipping Manager by Themepaste', 'tps-manager' ),
			__( 'Shipping Manager', 'tps-manager' ),
			'manage_options', // @TODO Add a new role and capability named `tsm_manager` || admin > shop_manager > tsm_manager
			Routes::ROOT,
			[ $this, 'render_menu_page' ]
		);
	}

	/**
	 * Invokes Shipping Manager admin menu controller
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function render_menu_page(): void {
		do_action( 'tsm_render_admin_root_page' );
	}
}
