<?php
namespace Themepaste\ShippingManager\BusinessLogic\Rules;

defined( 'ABSPATH' ) || exit;

/**
 * Enforces rules interface
 *
 * @since TSM_SINCE
 */
interface RulesInterface {
	/**
	 * Adds fees to the cost
	 *
	 * @since TSM_SINCE
	 *
	 * @param float $cost
	 *
	 * @return float
	 */
	public function add( float $cost ): float;

	/**
	 * Removes fee from cost
	 *
	 * @since TSM_SINCE
	 *
	 * @param float $cost
	 *
	 * @return float
	 */
	public function remove( float $cost ): float;

	/**
	 * Calculates and returns shipping costs
	 *
	 * @since TSM_SINCE
	 *
	 * @return float
	 */
	public function calculate():float;
}
