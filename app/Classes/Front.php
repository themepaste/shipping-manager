<?php
/**
 * Frontend-specific functionality for the Shipping Manager plugin.
 *
 * Handles the shipping calculator display, and enqueues necessary assets on the frontend.
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
 * Manages frontend behavior for the Shipping Manager plugin.
 */
class Front {

	use Hook;
	use Asset;

	/**
	 * @var array|null $shipping_calculator_settings Settings for the shipping calculator.
	 */
	private $shipping_calculator_settings;

	/**
	 * @var string $is_shipping_calculator_enable Whether the shipping calculator is enabled ('yes' or '').
	 */
	private $is_shipping_calculator_enable;

	/**
	 * @var string $is_enable_location_field Whether the location field is enabled ('yes' or '').
	 */
	private $is_enable_location_field;

	

	private $shipping_calculator_position;

	/**
	 * Constructor.
	 *
	 * Initializes the class, loads settings, and registers frontend hooks.
	 */
	public function __construct() {
		$this->shipping_calculator_settings   = get_option( 'tpsm-shipping-calculator_settings' );
		$this->is_shipping_calculator_enable  = tpsm_isset( $this->shipping_calculator_settings['shipping-calculator-enable'] );
		$this->is_enable_location_field       = tpsm_isset( $this->shipping_calculator_settings['enable-location-field'] );
		$this->shipping_calculator_position   = tpsm_isset( $this->shipping_calculator_settings['shipping-calculator-position'] ); 

		// $this->shipping_calculator_settings_fields = tpsm_shipping_calculator_settings_fields();

		// Enqueue frontend assets.
		$this->action( 'wp_enqueue_scripts', [ $this, 'enqueue_css' ] );
		$this->action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		// Render the shipping calculator on single product pages if enabled.
		if ( $this->is_shipping_calculator_enable ) {
			switch ( $this->shipping_calculator_position ) {
				case 'before-add-to-cart-button':
					$this->action( 'woocommerce_before_add_to_cart_button', [ $this, 'custom_shipping_form' ] );
					break;
				case 'after-add-to-cart-button':
					$this->action( 'woocommerce_after_add_to_cart_button', [ $this, 'custom_shipping_form' ] );
					break;
				case 'using-shortcode':
					$this->shortcode( 'tpsm-shipping-calculator', [ $this, 'custom_shipping_form' ] );
					break;
			}
		}
	}

	/**
	 * Displays the shipping calculator form and available methods.
	 *
	 * Renders templates to show available shipping options and, if enabled,
	 * a location input form beneath the add-to-cart button on product pages.
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

		echo '<div class="tpsm-shipping-calculator-wrapper">';

		// Render the shipping methods template.
		printf(
			'<div class="%1$s" id="%1$s">%2$s</div>',
			'tpsm-shipping-calculator-shipping-methods',
			Utility::get_template( 'shipping-calculator/shipping-methods.php', $args )
		);

		// Render the location input form if enabled.
		if ( $this->is_enable_location_field ) {
			echo Utility::get_template( 'shipping-calculator/shipping-form.php' );
		}

		echo '</div>';
	}

	/**
	 * Enqueues the frontend CSS file.
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
	 * Enqueues the frontend JavaScript file.
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
