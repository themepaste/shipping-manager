<?php 

namespace ThemePaste\ShippingManager\Classes;

defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Traits\Hook;
use ThemePaste\ShippingManager\Traits\Asset;

class Front {

    use Hook;
    use Asset;

    public function __construct() {
        $this->action( 'wp_enqueue_scripts', [$this, 'enqueue_css'] );
        $this->action( 'wp_enqueue_scripts', [$this, 'enqueue_scripts'] );
        $this->action( 'woocommerce_after_add_to_cart_button', [ $this, 'show_shipping_methods_on_product_page' ] );
    }

    function show_shipping_methods_on_product_page() {
		$shipping_methods = tpsm_get_available_shipping_methods();
	
		echo '<div class="product-shipping-methods"><h4>Available Shipping Methods:</h4><ul>';
	
		if ( !empty( $shipping_methods['rates'] ) ) {
			foreach ( $shipping_methods['rates'] as $rate ) {
				echo '<li>' . esc_html( $rate->get_label() ) . ' - ' . wc_price( $rate->get_cost() ) . '</li>';
			}
		} else {
			echo '<li>No shipping methods available for your location.</li>';
		}
	
		echo '</ul></div>';
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