<?php
/**
 * Common class for handling front-end AJAX and script enqueuing.
 *
 * @package ThemePaste\ShippingManager\Classes
 */

namespace ThemePaste\ShippingManager\Classes;

defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Helpers\Utility;
use ThemePaste\ShippingManager\Traits\Hook;
use ThemePaste\ShippingManager\Traits\Asset;

/**
 * Class Common
 *
 * Handles front-end script loading and AJAX request for shipping calculation.
 */
class Common {

	// Use traits for hooks and asset management
	use Hook;
	use Asset;

	/**
	 * Constructor.
	 *
	 * Registers actions and AJAX handlers.
	 */
	public function __construct() {
		// Enqueue front-end scripts.
		$this->action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		// Register AJAX handler for shipping calculator.
		$this->ajax( 'shipping_calculator', [ $this, 'shipping_calculator_ajax' ] );
	}

	/**
	 * AJAX callback for calculating available shipping methods.
	 *
	 * @return void Outputs JSON response and terminates execution.
	 */
	public function shipping_calculator_ajax() {
		// Verify the nonce for security.
		check_ajax_referer( 'tpsm-nonce_action', 'security' );

		// Sanitize input fields.
		$country    = sanitize_text_field( $_POST['country'] ?? '' );
		$state      = sanitize_text_field( $_POST['state'] ?? '' );
		$city       = sanitize_text_field( $_POST['city'] ?? '' );
		$postcode   = sanitize_text_field( $_POST['postcode'] ?? '' );
		$product_id = absint( $_POST['product_id'] ?? 0 );

		// Get available shipping methods using helper function.
		$args = [
			'shipping-methods' => tpsm_get_available_shipping_methods(
				$country,
				$state,
				$postcode,
				$city,
				$product_id
			),
		];

		// Render the shipping methods HTML.
		$html = sprintf(
			'%s',
			Utility::get_template( 'shipping-calculator/shipping-methods.php', $args )
		);

		// Send JSON success response.
		wp_send_json_success(
			[
				'html' => $html,
				'data' => [ $country, $state, $city, $postcode ],
			]
		);
	}

	/**
	 * Enqueue front-end scripts and localize AJAX variables.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		$this->enqueue_script(
			'tpsm-common',
			TPSM_ASSETS_URL . '/common/js/common.js',
			[ 'jquery' ]
		);

		// Pass data to JavaScript using localized script.
		$this->localize_script(
			'tpsm-common',
			'TPSM',
			[
				'ajax'       => admin_url( 'admin-ajax.php' ),
				'nonce'      => wp_create_nonce( 'tpsm-nonce_action' ),
				'product_id' => get_the_ID(),
			]
		);
	}
}
