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
        $this->action( 'wp_enqueue_scripts', [$this, 'enqueue_scripts'] );
        $this->action( 'woocommerce_after_add_to_cart_button', [ $this, 'custom_shipping_form' ] );
        $this->ajax( 'shipping_calculator', [$this, 'shipping_calculator_ajax'] );
    }

    public function shipping_calculator_ajax() {

		check_ajax_referer( 'tpsm-nonce_action', 'security' );

		$country  = sanitize_text_field( $_POST['country'] ?? '' );
		$state    = sanitize_text_field( $_POST['state'] ?? '' );
		$city     = sanitize_text_field( $_POST['city'] ?? '' );
		$postcode = sanitize_text_field( $_POST['postcode'] ?? '' );

		wp_send_json_success( array(
			'message' => "Shipping to {$city}, {$state}, {$country}, {$postcode}"
		) );
	}

    public function custom_shipping_form() {
		printf( '%s', Utility::get_template( 'shipping-calculator/shipping-methods.php' ) );
		printf( '%s', Utility::get_template( 'shipping-calculator/shipping-form.php' ) );
	}

    public function enqueue_scripts() {
        $this->enqueue_script(
            'tpsm-common',
            TPSM_ASSETS_URL . '/common/js/common.js',
            array( 'jquery' )
        );
        $this->localize_script( 'tpsm-common', 'TPSM', [
			'ajax' 		=> admin_url( 'admin-ajax.php' ),
			'nonce'    	=> wp_create_nonce( 'tpsm-nonce_action' ),
		] );
    }
}