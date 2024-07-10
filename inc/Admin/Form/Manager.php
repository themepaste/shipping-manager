<?php
namespace Themepaste\ShippingManager\Admin\Form;

use Themepaste\ShippingManager\Admin\Messages;
use Themepaste\ShippingManager\Admin\Routes;

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
				default:
					( new ShippingFees() )->process();
					break;
			}
		} else {
			tsm_admin_message( __( 'Authentication failed.', 'shipping-manager' ), Messages::TYPES[0] );
		}
	}
}
