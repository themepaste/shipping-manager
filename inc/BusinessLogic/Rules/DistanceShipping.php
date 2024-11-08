<?php
namespace Themepaste\ShippingManager\BusinessLogic\Rules;

defined( 'ABSPATH' ) || exit;

use Themepaste\ShippingManager\Admin\Form\ShippingFees;
use Themepaste\ShippingManager\Models\DistanceShippingSettings;

/**
 * Enforces rules interface
 *
 * @since 1.2.1
 */
class DistanceShipping extends AbstractRules implements RulesInterface {
	/**
	 * Unique name for rules
	 *
	 * @since 1.2.1
	 */
	const RULES_KEY = 'tsm-distance-shipping';

	/**
	 * Calculates current rules fees
	 *
	 * @since 1.2.1
	 *
	 * @return float
	 */
	public function calculate(): float {
		$cost = 0.00;
		
		return $cost;
	}

	

}
