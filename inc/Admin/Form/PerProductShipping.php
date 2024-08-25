<?php
namespace Themepaste\ShippingManager\Admin\Form;

defined( 'ABSPATH' ) || exit;

use Themepaste\ShippingManager\Admin\Messages;
use Themepaste\ShippingManager\Models\PerProductShippingSettings;

class PerProductShipping implements Process {

	use Parser;

	/**
	 * Processes free shipping submission
	 *
	 * @since TPS_MANAGER_SINCE
	 *
	 * @return void
	 */
	public function process() {
		$allowed_fields = ( new PerProductShippingSettings() )->get_fields();
		$parsed_data    = $this->parse_post_data( $allowed_fields );
		$status         = ( new PerProductShippingSettings() )->set( $parsed_data )->save();
		if ( $status ) {
			tps_manager_admin_message( __( 'Saved successfully.', 'tps-manager' ), Messages::TYPES[2] );
		}
	}
}
