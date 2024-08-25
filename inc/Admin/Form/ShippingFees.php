<?php
namespace Themepaste\ShippingManager\Admin\Form;

use Themepaste\ShippingManager\Admin\Messages;
use Themepaste\ShippingManager\Models\ShippingFeesSettings;

defined( 'ABSPATH' ) || exit;

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
			tsm_admin_message( __( 'Saved successfully.', 'tps-manager' ), Messages::TYPES[2] );
		}
	}

}