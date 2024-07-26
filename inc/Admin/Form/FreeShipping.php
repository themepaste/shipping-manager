<?php
namespace Themepaste\ShippingManager\Admin\Form;

use Themepaste\ShippingManager\Admin\Messages;
use Themepaste\ShippingManager\Models\FreeShippingSettings;

defined( 'ABSPATH' ) || exit;

/**
 * Manages free shipping form submission
 *
 * @since 1.1.0
 */
class FreeShipping implements Process {

	use Parser;

	/**
	 * Processes free shipping submission
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function process() {
		$allowed_fields = ( new FreeShippingSettings() )->get_fields();
		$parsed_data = $this->parse_post_data( $allowed_fields );
		$status = ( new FreeShippingSettings() )->set( $parsed_data )->save();
		if ( $status ) {
			tsm_admin_message( __( 'Saved successfully.', 'shipping-manager' ), Messages::TYPES[2] );
		}
	}
}
