<?php
namespace Themepaste\ShippingManager\BusinessLogic\Rules;

defined( 'ABSPATH' ) || exit;

use Themepaste\ShippingManager\Models\ShippingFeesSettings;

/**
 * Enforces rules interface
 *
 * @since TPS_MANAGER_SINCE
 */
class ProcessingFee extends AbstractRules implements RulesInterface {
	/**
	 * Unique name for rules
	 *
	 * @since TPS_MANAGER_SINCE
	 */
	const RULES_KEY = 'tsm-processing-fee';

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
		if ( tps_manager_is_checked( $shipping_fees->fetch()->get( ShippingFeesSettings::ENABLE_PROCESSING_FEES ), false ) ) {
			$cost = $shipping_fees->get( ShippingFeesSettings::PROCESSING_FEES_AMOUNT );
		}
		return $cost;
	}
}
