<?php
namespace Themepaste\ShippingManager\Admin\Form;

use Themepaste\ShippingManager\Admin\Messages;
use Themepaste\ShippingManager\Admin\Routes;
use Themepaste\ShippingManager\Models\FreeShippingSettings;
use Themepaste\ShippingManager\Models\PerProductShippingSettings;
use Themepaste\ShippingManager\Models\ProductPageShippingSettings;
use Themepaste\ShippingManager\Models\ShippingFeesSettings;

defined( 'ABSPATH' ) || exit;

/**
 * Manages form submission for admin settings
 *
 * @since TSM_SINCE
 */
class Manager {
	/**
	 * Unique id for initialized object
	 *
	 * @since TSM_SINCE
	 */
	const INSTANCE_KEY = 'admin_form_manager';

	/**
	 * Initializes form management
	 *
	 * @since TSM_SINCE
	 *
	 * @retun void
	 */
	public function __construct() {
		add_action( 'tsm_process_admin_form_data', [ $this, 'map_submission' ] );
		add_action( 'tsm_fetch_admin_form_data', [ $this, 'map_data' ], 10, 2 );
	}

	/**
	 * Maps form manager to appropriate handler
	 *
	 * @since TSM_SINCE
	 *
	 * @param string $page Name of the current page
	 *
	 * @return void
	 */
	public function map_submission( string $page ) {
		if ( ( new Authentication() )->is_authenticated( $page ) ) {
			switch ( $page ) {
				case tsm_get_page( Routes::FREE_SHIPPING ):
					( new FreeShipping() )->process();
					break;
				case tsm_get_page( Routes::PER_PRODUCT_SHIPPING ):
					( new PerProductShipping() )->process();
					break;
				case tsm_get_page( Routes::PRODUCT_PAGE_SHIPPING ):
					( new ProductPageShipping() )->process();
					break;
				default:
					( new ShippingFees() )->process();
					break;
			}
		} else {
			tsm_admin_message( __( 'Authentication failed.', 'shipping-manager' ), Messages::TYPES[0] );
		}
	}

	/**
	 * Maps data to appropriate form
	 *
	 * @since TSM_SINCE
	 *
	 * @param string $page
	 * @param array  $data
	 *
	 * @return array
	 */
	public function map_data( string $page, array $data ): array {
		$fetched_data = [];
		switch ( $page ) {
			case tsm_get_page( Routes::SHIPPING_FEES ):
				$fetched_data = ( new ShippingFeesSettings() )->fetch()->get();
				break;
			case tsm_get_page( Routes::FREE_SHIPPING ):
				$fetched_data = ( new FreeShippingSettings() )->fetch()->get();
				break;
			case tsm_get_page( Routes::PER_PRODUCT_SHIPPING ):
				$fetched_data = ( new PerProductShippingSettings() )->fetch()->get();
				break;
			case tsm_get_page( Routes::PRODUCT_PAGE_SHIPPING ):
				$fetched_data = ( new ProductPageShippingSettings() )->fetch()->get();
				break;
			default:
				break;
		}
		return array_merge( $data, $fetched_data );
	}
}
