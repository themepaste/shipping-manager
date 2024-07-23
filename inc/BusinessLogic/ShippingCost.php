<?php
namespace Themepaste\ShippingManager\BusinessLogic;

use Themepaste\ShippingManager\BusinessLogic\Rules\ProcessingFee;

defined( 'ABSPATH' ) || exit;

/**
 * Calculates Shipping cost Rules
 *
 * @since TSM_SINCE
 */
class ShippingCost {
	/**
	 * Unique object identifer key
	 *
	 * @since TSM_SINCE
	 */
	const INSTANCE_KEY = 'business_logic_shipping_cost';

	/**
	 * Initializes hooks
	 *
	 * @since TSM_SINCE
	 */
	public function __construct() {
		add_filter( 'tsm_additional_shipping_cost', [ $this, 'additional_cost' ], 10, 2 );
	}

	/**
	 * Adds additional cost for shipping
	 *
	 * @since TSM_SINCE
	 *
	 * @param float $cost
	 * @param array $package
	 *
	 * @return float
	 */
	public function additional_cost( float $cost, array $package ): float {
		$list_of_rules = [
			ProcessingFee::RULES_KEY => ProcessingFee::class
		];

		$list_of_rules = apply_filters( 'tsm_shipping_cost_rules_list', $list_of_rules, $package );

		foreach( $list_of_rules as $rule ) {
			$rule_object = new $rule( $package );
			$cost = $rule_object->add( $cost );
		}
		return $cost;
	}
}
