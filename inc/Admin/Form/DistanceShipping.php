<?php
namespace Themepaste\ShippingManager\Admin\Form;

defined( 'ABSPATH' ) || exit;

use Themepaste\ShippingManager\Admin\Messages;
use Themepaste\ShippingManager\Models\DistanceShippingSettings;


/**
 * Manages distance shipping form submission
 *
 * @since 1.2.1
 */
class DistanceShipping implements Process {

	use Parser;

	/**
	 * Processes distance shipping submission
	 *
	 * @since 1.2.1
	 *
	 * @return void
	 */
	public function process() {
		$allowed_fields = ( new DistanceShippingSettings() )->get_fields();
		$parsed_data = $this->parse_post_data( $allowed_fields );
		$status = ( new DistanceShippingSettings() )->set( $parsed_data )->save();
		if ( $status ) {
			tps_manager_admin_message( __( 'Saved successfully.', 'tps-manager' ), Messages::TYPES[2] );
		}
	}
}
