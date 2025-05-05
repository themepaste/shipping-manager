<?php 

namespace ThemePaste\ShippingManager\Classes;
defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Helpers\Utility;
use ThemePaste\ShippingManager\Traits\Hook;
use ThemePaste\ShippingManager\Traits\Asset;

class Common {
    use Hook;
    use Asset;

    public function __construct() {
        $this->action( 'woocommerce_after_add_to_cart_button', [ $this, 'custom_shipping_form' ] );
    }

    public function custom_shipping_form() {
		printf( '%s', Utility::get_template( 'shipping-calculator/shipping-form.php' ) );
	}
}