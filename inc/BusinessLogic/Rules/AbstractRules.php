<?php
namespace Themepaste\ShippingManager\BusinessLogic\Rules;

defined( 'ABSPATH' ) || exit;

/**
 * Rules abstraction
 *
 * @since 1.1.1
 */
abstract class AbstractRules implements RulesInterface {
	/**
	 * Shipping package
	 *
	 * @since 1.1.1
	 *
	 * @var array
	 */
	protected array $package = [];

	/**
	 * Initializes shipping package
	 *
	 * @since 1.1.1
	 */
	public function __construct( array $package = [] ) {
		$this->package = $package;
	}

	/**
	 * Adds specific rule cost to overall shipping cost
	 *
	 * @since 1.1.1
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
	 * @since 1.1.1
	 *
	 * @param float $cost Total cost of shipping after additional shipping cost is calculated
	 *
	 * @return float
	 */
	public function remove( float $cost ): float {
		return $cost - $this->calculate();
	}
}
