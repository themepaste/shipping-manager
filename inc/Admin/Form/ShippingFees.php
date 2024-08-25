<?php
namespace Themepaste\ShippingManager\Admin\Form;

defined( 'ABSPATH' ) || exit;

use Themepaste\ShippingManager\Admin\Messages;
use Themepaste\ShippingManager\Models\ShippingFeesSettings;

/**
 * Manages shipping fees form submission
 *
 * @since 1.1.0
 */
class ShippingFees implements Process {

	use Parser;

	public function process() {
		$allowed_fields = ( new ShippingFeesSettings() )->get_fields();
		$parsed_data = $this->parse_post_data( $allowed_fields );
		$status = ( new ShippingFeesSettings() )->set( $parsed_data )->save();
		if ( $status ) {
			tps_manager_admin_message( __( 'Saved successfully.', 'tps-manager' ), Messages::TYPES[2] );
		}
	}

}