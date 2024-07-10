<?php
namespace Themepaste\ShippingManager\Admin\Form;

use Themepaste\ShippingManager\Admin\Messages;

defined( 'ABSPATH' ) || exit;

/**
 * Manages free shipping form submission
 *
 * @since TSM_SINCE
 */
class FreeShipping implements Process {

	use Parser;

	public function process() {
		$status = true;

		if ( $status ) {
			tsm_admin_message( __( 'Saved successfully.', 'shipping-manager' ), Messages::TYPES[2] );
		}
	}

}