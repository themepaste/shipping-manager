<?php
namespace Themepaste\ShippingManager\Models;

defined( 'ABSPATH' ) || exit;

use Themepaste\ShippingManager\Constants;

/**
 * Datasource for free shipping
 *
 * @since 1.1.0
 */
class BoxShippingSettings extends Model {
	/**
	 * List of keys
	 *
	 * @since 1.1.0
	 */
	const BOX_SIZE_MANAGEMENT = 'enable-box-size-management';
	const BOX_SIZES = 'box-sizes';
	const MULTIPLE_BOX_SIZES = 'enable-multiple-box-sizes';
	const MULTIPLE_BOX = 'multiple-box';
	const BOX_PRICE_CONFIGURATION = 'box-price-configuration';

	/**
	 * Declaring default settings
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	protected array $settings = [
		self::BOX_SIZE_MANAGEMENT => Constants::NO,
		self::BOX_SIZES => [],
		self::MULTIPLE_BOX_SIZES => Constants::NO,
		self::MULTIPLE_BOX => 0,
		self::BOX_PRICE_CONFIGURATION => Constants::NO,
	];

	/**
	 * Initializing option keys
	 *
	 * @since 1.1.0
	 */
	public function __construct() {
		parent::__construct( 'tps_manager_box_shipping' );
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