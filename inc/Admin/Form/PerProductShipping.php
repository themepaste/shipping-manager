<?php
namespace Themepaste\ShippingManager\Admin\Form;

use Themepaste\ShippingManager\Admin\Messages;
use Themepaste\ShippingManager\Models\PerProductShippingSettings;

class PerProductShipping implements Process {

	use Parser;

	/**
	 * Processes free shipping submission
	 *
	 * @since TSM_SINCE
	 *
	 * @return void
	 */
	public function process() {
		$allowed_fields = ( new PerProductShippingSettings() )->get_fields();
		$parsed_data    = $this->parse_post_data( $allowed_fields );
		$status         = ( new PerProductShippingSettings() )->set( $parsed_data )->save();
		if ( $status ) {
			tsm_admin_message( __( 'Saved successfully.', 'shipping-manager' ), Messages::TYPES[2] );
		}
	}
}
