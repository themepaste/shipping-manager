<?php
namespace Themepaste\ShippingManager\Models;

defined( 'ABSPATH' ) || exit;

use Themepaste\ShippingManager\Constants;

/**
 * Datasource for free shipping
 *
 * @since 1.1.0
 */
class DistanceShippingSettings extends Model {
	/**
	 * List of keys
	 *
	 * @since 1.1.0
	 */
	const ENABLE_ZONE_DISTANCE = 'enable-zone-distance';
	const ZONE_DISTANCE_FEE = 'zone-distance-fee';
	const UNIT_FEE_DISTANCE = 'enable-unit-fee-distance';
	const UNIT_FEE_DISTANCE_RANGE = 'unit-fee-distance-range';

	/**
	 * Declaring default settings
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	protected array $settings = [
		self::ENABLE_ZONE_DISTANCE => Constants::NO,
		self::ZONE_DISTANCE_FEE => 0.00,
		self::UNIT_FEE_DISTANCE => Constants::NO,
		self::UNIT_FEE_DISTANCE_RANGE => [],
	];

	/**
	 * Initializing option keys
	 *
	 * @since 1.1.0
	 */
	public function __construct() {
		parent::__construct( 'tps_manager_distance_shipping' );
	}

	/**
	 * Checks if the data is valid
	 *
	 * @since 1.1.0
	 *
	 * @return bool
	 */
	public function is_valid(): bool {
		if ( parent::is_valid() ) {

		} else  {
			return false;
		}
		return true;
	}
}