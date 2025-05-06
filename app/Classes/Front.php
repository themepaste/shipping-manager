<?php
/**
 * Frontend-specific functionality for the Shipping Manager plugin.
 *
 * @package ThemePaste\ShippingManager\Classes
 */

namespace ThemePaste\ShippingManager\Classes;

defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Helpers\Utility;
use ThemePaste\ShippingManager\Traits\Hook;
use ThemePaste\ShippingManager\Traits\Asset;

/**
 * Class Front
 *
 * Handles all frontend hooks such as enqueuing styles/scripts and rendering shipping forms.
 */
class Front {

	use Hook;
	use Asset;

	/**
	 * Constructor.
	 *
	 * Registers all frontend-specific actions.
	 */
	public function __construct() {
		// Enqueue styles and scripts on the frontend.
		$this->action( 'wp_enqueue_scripts', [ $this, 'enqueue_css' ] );
		$this->action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		// Display custom shipping form after the add to cart button.
		$this->action( 'woocommerce_after_add_to_cart_button', [ $this, 'custom_shipping_form' ] );
	}

	/**
	 * Outputs the custom shipping calculator UI on single product pages.
	 *
	 * This includes both the shipping methods list and the form for user input.
	 *
	 * @return void
	 */
	public function custom_shipping_form() {
		$args = [
			'shipping-methods' => tpsm_get_available_shipping_methods(
				null,
				null,
				null,
				null,
				get_the_ID()
			),
		];

		// Render shipping methods container with template output.
		printf(
			'<div class="%1$s" id="%1$s">%2$s</div>',
			'tpsm-shipping-calculator-shipping-methods',
			Utility::get_template( 'shipping-calculator/shipping-methods.php', $args )
		);

		// Render shipping form template.
		echo Utility::get_template( 'shipping-calculator/shipping-form.php' );
	}

	/**
	 * Enqueue frontend CSS file.
	 *
	 * @return void
	 */
	public function enqueue_css() {
		$this->enqueue_style(
			'tpsm-front',
			TPSM_ASSETS_URL . '/front/css/front.css'
		);
	}

	/**
	 * Enqueue frontend JavaScript file.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		$this->enqueue_script(
			'tpsm-front',
			TPSM_ASSETS_URL . '/front/js/front.js',
			[ 'jquery' ]
		);
	}
}
