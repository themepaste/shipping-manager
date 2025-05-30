<?php
namespace Themepaste\ShippingManager\BusinessLogic\Rules;

defined( 'ABSPATH' ) || exit;

use Themepaste\ShippingManager\Admin\Form\ShippingFees;
use Themepaste\ShippingManager\Models\ShippingFeesSettings;

/**
 * Enforces rules interface
 *
 * @since 1.2.1
 */
class WeightFee extends AbstractRules implements RulesInterface {
	/**
	 * Unique name for rules
	 *
	 * @since 1.2.1
	 */
	const RULES_KEY = 'tsm-weight-fee';

	/**
	 * Calculates current rules fees
	 *
	 * @since 1.2.1
	 *
	 * @return float
	 */
	public function calculate(): float {
		$cost = 0.00;
		$shipping_fees = new ShippingFeesSettings();
		if ( tps_manager_is_checked( $shipping_fees->fetch()->get( ShippingFeesSettings::ENABLE_WEIGHT_BASED_FEES ), false ) ) {
			$fee_type = $shipping_fees->fetch()->get( ShippingFeesSettings::WEIGHT_BASED_SHIPPING_FEES_TYPE );
			if ( $fee_type === 'weight-per-unit' ) {
				$fee_per_unit = $shipping_fees->get( ShippingFeesSettings::WEIGHT_BASED_PER_UNIT_AMOUNT_FEES );
				foreach( $this->package['contents'] as $product_key => $product_data ) {
					$quantity = $product_data['quantity'];
					$cost += $quantity * $fee_per_unit;
				}
			}

			if ( $fee_type === 'weight-range-unit' ) {
				$fee_range_unit_rules = $shipping_fees->get( ShippingFeesSettings::WEIGHT_BASED_RANGE_UNIT_RULES );
				$total_weight = 0;
				foreach ( $this->package['contents'] as $product_key => $product_data ) {
					$weight = $product_data['data']->get_weight();
					$total_weight += (float) $weight;
				}
				foreach ( $fee_range_unit_rules as $key => $value ) {
					$from = isset( $value[0] ) ? $value[0] : 0;
					$to = isset( $value[1] ) ? $value[1] : INF;
					$fee = isset( $value[2] ) ? $value[2] : 0;
					if ( ( $total_weight >= $from ) && ( $total_weight <= $to ) ) {
						$cost += (float) $fee;
						break;
					}
				}
			}
		}
		return $cost;
	}
}
