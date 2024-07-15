<?php
namespace Themepaste\ShippingManager\BusinessLogic;

defined( 'ABSPATH' ) || exit;

class Manager {

	const INSTANCE_KEY = 'business_logic_manager';

	public function __construct() {
		add_filter( 'woocommerce_shipping_methods', [ $this, 'register_shipping_method' ] );
	}

	public function register_shipping_method( $methods ) {
		$methods[ ShippingMethod::INSTANCE_KEY ] = ShippingMethod::class;
		return $methods;
	}

}
