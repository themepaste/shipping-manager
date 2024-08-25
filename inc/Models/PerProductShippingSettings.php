<?php
namespace Themepaste\ShippingManager\Models;

defined( 'ABSPATH' ) || exit;

use Themepaste\ShippingManager\Constants;

/**
 * Data source for per product shipping
 *
 * @since 1.2.1
 */
class PerProductShippingSettings extends Model {
	/**
	 * List of keys
	 *
	 * @since 1.2.1
	 */
	const PER_PRODUCT_SHIPPING = 'enable-per-product-shipping';
	const OVERRIDE_OTHERS = 'override-others';

	protected array $settings = [
		self::PER_PRODUCT_SHIPPING => Constants::NO,
		self::OVERRIDE_OTHERS => Constants::NO
	];

	public function __construct() {
		parent::__construct( 'tps_manager_per_product_shipping' );
	}
}