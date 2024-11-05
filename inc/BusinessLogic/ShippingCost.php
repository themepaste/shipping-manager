<?php
namespace Themepaste\ShippingManager\BusinessLogic;

defined( 'ABSPATH' ) || exit;

use Themepaste\ShippingManager\BusinessLogic\Rules\ProcessingFee;
use Themepaste\ShippingManager\BusinessLogic\Rules\WeightFee;
use Themepaste\ShippingManager\BusinessLogic\Rules\FreeShipping;

/**
 * Calculates Shipping cost Rules
 *
 * @since 1.2.1
 */
class ShippingCost {
	/**
	 * Unique object identifer key
	 *
	 * @since 1.2.1
	 */
	const INSTANCE_KEY = 'business_logic_shipping_cost';

	/**
	 * Initializes hooks
	 *
	 * @since 1.2.1
	 */
	public function __construct() {
		add_filter( 'tps_manager_additional_shipping_cost', [ $this, 'additional_cost' ], 10, 2 );
	}

	/**
	 * Adds additional cost for shipping
	 *
	 * @since 1.2.1
	 *
	 * @param float $cost
	 * @param array $package
	 *
	 * @return float
	 */
	public function additional_cost( float $cost, array $package ): float {
		$list_of_rules = [
			ProcessingFee::RULES_KEY => ProcessingFee::class,
			WeightFee::RULES_KEY => WeightFee::class,
			FreeShipping::RULES_KEY => FreeShipping::class,
		];

		$list_of_rules = apply_filters( 'tps_manager_shipping_cost_rules_list', $list_of_rules, $package );

		foreach( $list_of_rules as $rule ) {
			$rule_object = new $rule( $package );
			$cost = $rule_object->add( $cost );
		}
		return $cost;
	}
}
