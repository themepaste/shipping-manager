<?php 

namespace ThemePaste\ShippingManager\Classes\Shipping;

defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Helpers\Utility;
use ThemePaste\ShippingManager\Traits\Asset;
use ThemePaste\ShippingManager\Traits\Hook;
use ThemePaste\ShippingManager\Classes\Woocommerce\Logic;

/**
 * Class ShippingMethod
 *
 * Registers a custom shipping method with WooCommerce.
 *
 * @package ThemePaste\ShippingManager\Classes\Shipping
 */
class ShippingMethods {

    use Hook;
    use Asset;

    /**
     * General settings.
     *
     * @var array
     */
    static public $tpsm_general_settings = [ 'is-plugin-enable' => '' ];

    /**
     * Initialize the custom shipping method registration.
     *
     * Hooks into WooCommerce to register a custom shipping method.
     *
     * @return void
     */
    public function __construct() {
        self::$tpsm_general_settings = get_option( 'tpsm-general_settings' );
        $cart = new Logic();
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
        
        if( in_array( self::$tpsm_general_settings['is-plugin-enable'], ['yes', '1', 1], true ) ) {
            $methods[ RegisterShippingMethod::ID ] = RegisterShippingMethod::class;
        }
        return $methods;
    }
}
