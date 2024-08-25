<?php
namespace Themepaste\ShippingManager\Models;

defined( 'ABSPATH' ) || exit;

use Themepaste\ShippingManager\Constants;

/**
 * Data source for per product shipping
 *
 * @since TPS_MANAGER_SINCE
 */
class ProductPageShippingSettings extends Model {
	/**
	 * List of keys
	 *
	 * @since TPS_MANAGER_SINCE
	 */
	const PRODUCT_PAGE_SHIPPING = 'enable-product-page-shipping';

	protected array $settings = [
		self::PRODUCT_PAGE_SHIPPING => Constants::NO,
	];

	public function __construct() {
		parent::__construct( 'tps_manager_product_page_shipping' );
	}
}