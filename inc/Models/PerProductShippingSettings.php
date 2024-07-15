<?php
namespace Themepaste\ShippingManager\Models;

use Themepaste\ShippingManager\Constants;

defined( 'ABSPATH' ) || exit;

/**
 * Data source for per product shipping
 *
 * @since TSM_SINCE
 */
class PerProductShippingSettings extends Model {
	/**
	 * List of keys
	 *
	 * @since TSM_SINCE
	 */
	const PER_PRODUCT_SHIPPING = 'enable-per-product-shipping';
	const OVERRIDE_OTHERS = 'override-others';

	protected array $settings = [
		self::PER_PRODUCT_SHIPPING => Constants::NO,
		self::OVERRIDE_OTHERS => Constants::NO
	];

	public function __construct() {
		parent::__construct( 'tsm_per_product_shipping' );
	}
}