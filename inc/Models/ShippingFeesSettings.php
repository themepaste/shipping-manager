<?php
namespace Themepaste\ShippingManager\Models;

use Themepaste\ShippingManager\Constants;

defined( 'ABSPATH' ) || exit;

/**
 * Datasource for free shipping
 *
 * @since TPS_MANAGER_SINCE
 */
class ShippingFeesSettings extends Model {
	/**
	 * List of keys
	 *
	 * @since TPS_MANAGER_SINCE
	 */
	const ENABLE_PROCESSING_FEES = 'enable-processing-fees';
	const PROCESSING_FEES_AMOUNT = 'processing-fees-amount';
	const ENABLE_WEIGHT_BASED_FEES = 'enable-weight-based-fees';
	const WEIGHT_BASED_SHIPPING_FEES_TYPE = 'weight-based-shipping-fees-type'; // value: per-unit, unit-range
	const WEIGHT_BASED_PER_UNIT_AMOUNT_FEES = 'weight-based-per-unit-amount-fees';
	const WEIGHT_PER_UNIT = 'weight-per-unit';
	const WEIGHT_RANGE_UNIT = 'weight-range-unit';
	const WEIGHT_BASED_RANGE_UNIT_RULES = 'weight-based-range-unit-rules';
	const WEIGHT_FROM = 'weight-from';
	const WEIGHT_TO = 'weight-to';
	const WEIGHT_RANGE_FEE = 'weight-range-fee';

	/**
	 * Declaring default settings
	 *
	 * @since TPS_MANAGER_SINCE
	 *
	 * @var array
	 */
	protected array $settings = [
		self::ENABLE_PROCESSING_FEES => Constants::NO,
		self::PROCESSING_FEES_AMOUNT => 0.00,
		self::ENABLE_WEIGHT_BASED_FEES => Constants::NO,
		self::WEIGHT_BASED_SHIPPING_FEES_TYPE => self::WEIGHT_PER_UNIT,
		self::WEIGHT_BASED_PER_UNIT_AMOUNT_FEES => 0.00,
		self::WEIGHT_BASED_RANGE_UNIT_RULES => [],
	];

	/**
	 * Initializing option keys
	 *
	 * @since TPS_MANAGER_SINCE
	 */
	public function __construct() {
		parent::__construct( 'tps_manager_shipping_fees' );
	}
}