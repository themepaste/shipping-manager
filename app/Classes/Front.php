<?php 

namespace ThemePaste\ShippingManager\Classes;

defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Helpers\Utility;
use ThemePaste\ShippingManager\Traits\Hook;
use ThemePaste\ShippingManager\Traits\Asset;

class Front {

    use Hook;
    use Asset;

    public function __construct() {
        $this->action( 'wp_enqueue_scripts', [$this, 'enqueue_css'] );
        $this->action( 'wp_enqueue_scripts', [$this, 'enqueue_scripts'] );
        $this->action( 'woocommerce_after_add_to_cart_button', [ $this, 'custom_shipping_form' ] );
    }

	public function custom_shipping_form() {
		$args = [
			'shipping-methods' => tpsm_get_available_shipping_methods(
				null, null, null, null, get_the_ID()
			),
		];
		printf( 
			'<div class="%1$s" id="%1$s">
				%2$s
			</div>', 
			'tpsm-shipping-calculator-shipping-methods',
			Utility::get_template( 'shipping-calculator/shipping-methods.php', $args ) 
		);
		printf( '%s', Utility::get_template( 'shipping-calculator/shipping-form.php' ) );
	}


    public function enqueue_css() {
        $this->enqueue_style( 
            'tpsm-front',
            TPSM_ASSETS_URL . '/front/css/front.css',
            
        );
    }

    public function enqueue_scripts() {
        $this->enqueue_script(
            'tpsm-front',
            TPSM_ASSETS_URL . '/front/js/front.js',
            array( 'jquery' )
        );
    }

}