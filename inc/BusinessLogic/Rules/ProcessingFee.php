<?php
namespace Themepaste\ShippingManager\BusinessLogic\Rules;

defined( 'ABSPATH' ) || exit;

/**
 * Enforces rules interface
 *
 * @since TSM_SINCE
 */
class ProcessingFee extends AbstractRules implements RulesInterface {
	const RULES_KEY = 'tsm-processing-fee';

	public function calculate(): float {
		return 0.00;
	}
}
