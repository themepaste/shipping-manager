<?php
namespace Themepaste\ShippingManager\BusinessLogic\Rules;

defined( 'ABSPATH' ) || exit;

/**
 * Enforces rules interface
 *
 * @since 1.2.1
 */
interface RulesInterface {
	/**
	 * Adds fees to the cost
	 *
	 * @since 1.2.1
	 *
	 * @param float $cost
	 *
	 * @return float
	 */
	public function add( float $cost ): float;

	/**
	 * Removes fee from cost
	 *
	 * @since 1.2.1
	 *
	 * @param float $cost
	 *
	 * @return float
	 */
	public function remove( float $cost ): float;

	/**
	 * Calculates and returns shipping costs
	 *
	 * @since 1.2.1
	 *
	 * @return float
	 */
	public function calculate():float;
}
