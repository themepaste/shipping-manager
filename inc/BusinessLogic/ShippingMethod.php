<?php
namespace Themepaste\ShippingManager\BusinessLogic;

defined( 'ABSPATH' ) || exit;

use Themepaste\ShippingManager\Constants;
use \WC_Shipping_Method;

/**
 * Extended shipping method to be activated and applied by WooCommerce admin
 *
 * @since TSM_SINCE
 */
class ShippingMethod extends WC_Shipping_Method {

	const INSTANCE_KEY = 'tsm-shipping-manager-shipping-method';

	/**
	 * Mandatory shipping details
	 *
	 * @since TSM_SINCE
	 */
	public function __construct() {
		parent::__construct();
		$this->id                 = self::INSTANCE_KEY;
		$this->method_title       = __( 'Shipping Manager Method', 'shipping-manager' );
		$this->method_description = __( 'A very flexible shipping method to manage shipping fees in a robust way.', 'shipping-manager' );

		$this->enabled = Constants::YES;
		$this->title   = __( 'Shipping Manager Method', 'shipping-manager' );

		$this->init();
	}

	/**
	 * Initialize Shipping Manager Custom shipping method
	 *
	 * @since TSM_SINCE
	 *
	 * @return void
	 */
	function init() {
		$this->init_form_fields();
		$this->init_settings();

		add_action( 'woocommerce_update_options_shipping_' . $this->id, [ $this, 'process_admin_options' ] );
	}

	/**
	 * From fields to be filled and configured by WooCommerce admin
	 *
	 * @since TSM_SINCE
	 *
	 * @return void
	 */
	public function init_form_fields() {
		$this->form_fields = array(
			'enabled' => [
				'title'       => __( 'Enable', 'shipping-manager' ),
				'type'        => 'checkbox',
				'description' => __( 'Enable Shipping Manager' ),
				'default'     => Constants::YES,
			],
			'title' => [
				'title'       => __( 'Shipping Manager', 'shipping-manager' ),
				'type'        => 'text',
				'description' => __( 'Shipping Manager title to be displayed to user while checking out.', 'shipping-manager' ),
				'default'     => __( 'Shipping Manager Method', 'shipping-manager' ),
			],
		);
	}

	/**
	 * Calculates shipping fees for shipping manager
	 *
	 * @since TSM_SINCE
	 *
	 * @param array $package
	 *
	 * @return void
	 */
	public function calculate_shipping( $package = [] ) {
		$cost = apply_filters( 'tsm_additional_shipping_cost', 0.00, $package );
		$rate = [
			'label'   => $this->settings['title'],
			'cost'    => $cost,
			'package' => $package,
		];

		$this->add_rate( $rate );
	}
}
