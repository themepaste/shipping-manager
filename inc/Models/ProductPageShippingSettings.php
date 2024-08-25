<?php
namespace Themepaste\ShippingManager\Models;

use Themepaste\ShippingManager\Constants;

defined( 'ABSPATH' ) || exit;

/**
 * Data source for per product shipping
 *
 * @since TSM_SINCE
 */
class ProductPageShippingSettings extends Model {
	/**
	 * List of keys
	 *
	 * @since TSM_SINCE
	 */
	const PRODUCT_PAGE_SHIPPING = 'enable-product-page-shipping';

	protected array $settings = [
		self::PRODUCT_PAGE_SHIPPING => Constants::NO,
	];

	public function __construct() {
		parent::__construct( 'tps_manager_product_page_shipping' );
	}
}