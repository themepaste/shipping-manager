<?php
namespace Themepaste\ShippingManager\BusinessLogic\Rules;

defined( 'ABSPATH' ) || exit;

/**
 * Rules abstraction
 *
 * @since TPS_MANAGER_SINCE
 */
abstract class AbstractRules implements RulesInterface {
	/**
	 * Shipping package
	 *
	 * @since TPS_MANAGER_SINCE
	 *
	 * @var array
	 */
	protected array $package = [];

	/**
	 * Initializes shipping package
	 *
	 * @since TPS_MANAGER_SINCE
	 */
	public function __construct( array $package = [] ) {
		$this->package = $package;
	}

	/**
	 * Adds specific rule cost to overall shipping cost
	 *
	 * @since TPS_MANAGER_SINCE
	 *
	 * @param float $cost Total cost of shipping before additional shipping cost is calculated
	 *
	 * @return float
	 */
	public function add( float $cost ): float {
		return $cost + $this->calculate();
	}

	/**
	 * Removes specific rule cost to overall shipping cost
	 *
	 * @since TPS_MANAGER_SINCE
	 *
	 * @param float $cost Total cost of shipping after additional shipping cost is calculated
	 *
	 * @return float
	 */
	public function remove( float $cost ): float {
		return $cost - $this->calculate();
	}
}
