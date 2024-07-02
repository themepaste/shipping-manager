<?php

namespace Themepaste\ShippingManager\Admin;

use Themepaste\ShippingManager\ShippingManager;

defined( 'ABSPATH' ) || exit;

class Menu {
	const INSTANCE_KEY = __CLASS__;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
	}

	public function add_menu() {
		add_submenu_page(
			'woocommerce',
			__( 'Shipping Manager by Themepaste', 'shipping-manager' ),
			__( 'Shipping Manager', 'shipping-manager' ),
			'manage_options',
			tsm_route( Routes::ROOT ),
			[ $this, 'render_menu_page' ]
		);
	}

	public function render_menu_page() {
		echo "Hello Page";
	}
}
