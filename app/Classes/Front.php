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
		$this->is_shipping_calculator_enable  = tpsm_isset( $this->shipping_calculator_settings['shipping-calculator-enable'] ?? '' );
		$this->is_enable_location_field       = tpsm_isset( $this->shipping_calculator_settings['enable-location-field'] ?? '' );
		$this->shipping_calculator_position   = tpsm_isset( $this->shipping_calculator_settings['shipping-calculator-position'] ?? '' ); 

		// Enqueue frontend assets.
		$this->action( 'wp_enqueue_scripts', [ $this, 'enqueue_css' ] );
		$this->action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		// Render the shipping calculator on single product pages if enabled.
		if ( $this->is_shipping_calculator_enable ) {
			// Fall back to the first option the settings dropdown shows, so an
			// enabled calculator that was never given a position still renders.
			$position = $this->shipping_calculator_position ?: 'before-add-to-cart-button';

			switch ( $position ) {
				case 'before-add-to-cart-button':
					$this->action( 'woocommerce_before_add_to_cart_button', [ $this, 'render_shipping_form' ] );
					break;
				case 'after-add-to-cart-button':
					$this->action( 'woocommerce_after_add_to_cart_button', [ $this, 'render_shipping_form' ] );
					break;
				case 'using-shortcode':
					$this->shortcode( 'tpsm-shipping-calculator', [ $this, 'custom_shipping_form' ] );
					break;
			}
		}
	}

	/**
	 * Builds the shipping calculator markup.
	 *
	 * Returns (rather than echoes) the markup, because this is also used as a
	 * shortcode callback — a shortcode that echoes gets its output flushed to
	 * the top of the page instead of appearing where the shortcode was placed.
	 *
	 * @return string
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

		$output = '<div class="tpsm-shipping-calculator-wrapper">';

		// Render the shipping methods template.
		$output .= sprintf(
			'<div class="%1$s" id="%1$s">%2$s</div>',
			'tpsm-shipping-calculator-shipping-methods',
			Utility::get_template( 'shipping-calculator/shipping-methods.php', $args )
		);

		// Render the location input form if enabled.
		if ( $this->is_enable_location_field ) {
			$output .= Utility::get_template( 'shipping-calculator/shipping-form.php' );
		}

		$output .= '</div>';

		return $output;
	}

	/**
	 * Echoes the shipping calculator, for use as an action callback.
	 *
	 * @return void
	 */
	public function render_shipping_form() {
		echo $this->custom_shipping_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Template output is escaped at the point of use.
	}

	/**
	 * Enqueues the frontend CSS file.
	 *
	 * @return void
	 */
	public function enqueue_css() {
		if ( ! tpsm_is_shipping_calculator_enabled() ) {
			return;
		}

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
		if ( ! tpsm_is_shipping_calculator_enabled() ) {
			return;
		}

		$this->enqueue_script(
			'tpsm-front',
			TPSM_ASSETS_URL . '/front/js/front.js',
			[ 'jquery' ]
		);
	}
}
