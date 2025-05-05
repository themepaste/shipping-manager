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
        // $this->action( 'woocommerce_after_add_to_cart_button', [ $this, 'show_shipping_methods_on_product_page' ] );

		
    }

    function show_shipping_methods_on_product_page() {
		if (!is_product()) return;
	
		// Get current product
		global $product;
	
		// Simulated customer location — you can replace with real IP-based detection or logged-in user's address
		$country 	= WC()->customer->get_shipping_country();
		$state 		= WC()->customer->get_shipping_state();
		$postcode 	= WC()->customer->get_shipping_postcode();
		$city 		= WC()->customer->get_shipping_city();
	
		// Set up a fake package with this product
		$package = array(
			'contents' => array(
				array(
					'data' => $product,
					'quantity' => 1,
				)
			),
			'destination' => array(
				'country'   => $country,
				'state'     => $state,
				'postcode'  => $postcode,
				'city'      => $city,
				'address'   => '', // Optional
				'address_2' => '',
			),
			'user' => array(),
			'contents_cost' => $product->get_price(),
			'applied_coupons' => array(),
		);
	
		// Load shipping methods
		$shipping = \WC_Shipping::instance();
		$shipping->load_shipping_methods();
	
		// Calculate shipping rates
		$shipping_methods = $shipping->calculate_shipping_for_package($package);
	
		echo '<div class="product-shipping-methods"><h4>Available Shipping Methods:</h4><ul>';
	
		if (!empty($shipping_methods['rates'])) {
			foreach ($shipping_methods['rates'] as $rate) {
				echo '<li>' . esc_html($rate->get_label()) . ' - ' . wc_price($rate->get_cost()) . '</li>';
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