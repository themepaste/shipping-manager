<?php
namespace Themepaste\ShippingManager\BusinessLogic\Rules;

use Themepaste\ShippingManager\Models\ShippingFeesSettings;

defined( 'ABSPATH' ) || exit;

/**
 * Enforces rules interface
 *
 * @since TSM_SINCE
 */
class ProcessingFee extends AbstractRules implements RulesInterface {
	/**
	 * Unique name for rules
	 *
	 * @since TSM_SINCE
	 */
	const RULES_KEY = 'tsm-processing-fee';

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
		if ( tsm_is_checked( $shipping_fees->fetch()->get( ShippingFeesSettings::ENABLE_PROCESSING_FEES ), false ) ) {
			$cost = $shipping_fees->get( ShippingFeesSettings::PROCESSING_FEES_AMOUNT );
		}
		return $cost;
	}
}
