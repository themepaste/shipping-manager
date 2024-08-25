<?php
namespace Themepaste\ShippingManager\BusinessLogic\Rules;

defined( 'ABSPATH' ) || exit;

/**
 * Enforces rules interface
 *
 * @since TPS_MANAGER_SINCE
 */
interface RulesInterface {
	/**
	 * Adds fees to the cost
	 *
	 * @since TPS_MANAGER_SINCE
	 *
	 * @param float $cost
	 *
	 * @return float
	 */
	public function add( float $cost ): float;

	/**
	 * Removes fee from cost
	 *
	 * @since TPS_MANAGER_SINCE
	 *
	 * @param float $cost
	 *
	 * @return float
	 */
	public function remove( float $cost ): float;

	/**
	 * Calculates and returns shipping costs
	 *
	 * @since TPS_MANAGER_SINCE
	 *
	 * @return float
	 */
	public function calculate():float;
}
