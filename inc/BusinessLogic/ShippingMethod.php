<?php
namespace Themepaste\ShippingManager\BusinessLogic;

defined( 'ABSPATH' ) || exit;

use Themepaste\ShippingManager\Constants;
use \WC_Shipping_Method;

class ShippingMethod extends WC_Shipping_Method {

	const INSTANCE_KEY = 'tsm-shipping-manager-shipping-method';

	public function __construct() {
		parent::__construct();
		$this->id                 = self::INSTANCE_KEY;
		$this->method_title       = __( 'Shipping Manager Method', 'shipping-manager' );
		$this->method_description = __( 'A very flexible shipping method to manage shipping fees in a robust way.', 'shipping-manager' );

		$this->enabled = Constants::YES;
		$this->title   = __( 'Shipping Manager Method', 'shipping-manager' );

		$this->init();
	}

	function init() {
		$this->init_form_fields();
		$this->init_settings();

		add_action( 'woocommerce_update_options_shipping_' . $this->id, [ $this, 'process_admin_options' ] );
	}

	public function init_form_fields() {
		$this->form_fields = array(
			'enabled' => array(
				'title'       => __( 'Enable', 'shipping-manager' ),
				'type'        => 'checkbox',
				'description' => __( 'Enable Shipping Manager' ),
				'default'     => Constants::YES,
			),
			'title' => array(
				'title'       => __( 'Shipping Manager', 'shipping-manager' ),
				'type'        => 'text',
				'description' => __( 'Shipping Manager title to be displayed to user while checking out.', 'shipping-manager' ),
				'default'     => __( 'Shipping Manager Method', 'shipping-manager' ),
			),
//			'cost' => array(
//				'title'       => __( 'Cost' ),
//				'type'        => 'text',
//				'description' => __( 'Cost for this shipping method' ),
//				'default'     => '10',
//			),
		);
	}

	public function calculate_shipping( $package = [] ) {
		$rate = array(
			'label'   => $this->title,
			'cost'    => 25.35, // $this->settings['cost'],
			'package' => $package,
		);

		$this->add_rate( $rate );
	}

}