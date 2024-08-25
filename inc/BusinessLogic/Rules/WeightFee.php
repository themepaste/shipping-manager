<?php
namespace Themepaste\ShippingManager\BusinessLogic\Rules;

use Themepaste\ShippingManager\Models\ShippingFeesSettings;

defined( 'ABSPATH' ) || exit;

/**
 * Enforces rules interface
 *
 * @since TSM_SINCE
 */
class WeightFee extends AbstractRules implements RulesInterface {
	/**
	 * Unique name for rules
	 *
	 * @since TSM_SINCE
	 */
	const RULES_KEY = 'tsm-weight-fee';

	/**
	 * Calculates current rules fees
	 *
	 * @since TSM_SINCE
	 *
	 * @return float
	 */
	public function calculate(): float {
		$cost = 0.00;
		$shipping_fees = new ShippingFeesSettings();
		if ( tps_manager_is_checked( $shipping_fees->fetch()->get( ShippingFeesSettings::ENABLE_WEIGHT_BASED_FEES ), false ) ) {
			$fee_per_unit = $shipping_fees->get( ShippingFeesSettings::WEIGHT_BASED_PER_UNIT_AMOUNT_FEES );
			foreach( $this->package['contents'] as $product_key => $product_data ) {
				$quantity = $product_data['quantity'];
				$weight = $product_data['data']->get_weight();
				$total_weight = $quantity * (float) $weight;
				$cost += $total_weight * $fee_per_unit;
			}
		}
		return $cost;
	}
}
