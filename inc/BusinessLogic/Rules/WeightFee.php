<?php
namespace Themepaste\ShippingManager\BusinessLogic\Rules;

defined( 'ABSPATH' ) || exit;

use Themepaste\ShippingManager\Models\ShippingFeesSettings;

/**
 * Enforces rules interface
 *
 * @since TPS_MANAGER_SINCE
 */
class WeightFee extends AbstractRules implements RulesInterface {
	/**
	 * Unique name for rules
	 *
	 * @since TPS_MANAGER_SINCE
	 */
	const RULES_KEY = 'tsm-weight-fee';

	/**
	 * Calculates current rules fees
	 *
	 * @since TPS_MANAGER_SINCE
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
