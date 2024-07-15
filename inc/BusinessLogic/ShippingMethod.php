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

		$this->supports = array(
			'shipping-zones',
			'instance-settings',
		);
	}

	function init() {
		$this->init_form_fields();
		$this->init_settings();

		add_action( 'woocommerce_update_options_shipping_' . $this->id, [ $this, 'process_admin_options' ] );
	}

	public function init_form_fields() {
		$this->form_fields = array(
			'enabled' => array(
				'title'       => __( 'Enable/Disable' ),
				'type'        => 'checkbox',
				'description' => __( 'Enable this shipping method' ),
				'default'     => 'yes',
			),
			'title' => array(
				'title'       => __( 'Title' ),
				'type'        => 'text',
				'description' => __( 'Title to be displayed in checkout' ),
				'default'     => __( 'Custom Shipping Method' ),
			),
			'cost' => array(
				'title'       => __( 'Cost' ),
				'type'        => 'text',
				'description' => __( 'Cost for this shipping method' ),
				'default'     => '10',
			),
		);
	}

	public function calculate_shipping( $package = array() ) {
		$rate = array(
			'label'   => $this->title,
			'cost'    => $this->settings['cost'],
			'package' => $package,
		);

		$this->add_rate( $rate );
	}

}