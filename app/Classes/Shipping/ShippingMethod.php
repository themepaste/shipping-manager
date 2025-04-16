<?php 

namespace ThemePaste\ShippingManager\Classes\Shipping;

defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Helpers\Utility;
use ThemePaste\ShippingManager\Traits\Asset;
use ThemePaste\ShippingManager\Traits\Hook;

/**
 * Class ShippingMethod
 *
 * Registers a custom shipping method with WooCommerce.
 *
 * @package ThemePaste\ShippingManager\Classes\Shipping
 */
class ShippingMethod {

    use Hook;
    use Asset;

    /**
     * Initialize the custom shipping method registration.
     *
     * Hooks into WooCommerce to register a custom shipping method.
     *
     * @return void
     */
    public function __construct() {
        $this->filter( 'woocommerce_shipping_methods', [ $this, 'shipping_method' ] );
    }

    /**
     * Register the custom shipping method class.
     *
     * Adds the custom shipping method class to the WooCommerce shipping methods list.
     *
     * @param array $methods Existing WooCommerce shipping methods.
     * @return array Modified list of shipping methods including the custom one.
     */
    public function shipping_method( $methods ) {
        $methods[ RegisterShippingMethod::ID ] = RegisterShippingMethod::class;
        return $methods;
    }
}
