<?php
namespace Themepaste\ShippingManager\Admin\Form;

defined( 'ABSPATH' ) || exit;

use Themepaste\ShippingManager\Admin\Messages;
use Themepaste\ShippingManager\Models\ProductPageShippingSettings;

class ProductPageShipping implements Process {

	use Parser;

	/**
	 * Processes free shipping submission
	 *
	 * @since 1.2.1
	 *
	 * @return void
	 */
	public function process() {
		$allowed_fields = ( new ProductPageShippingSettings() )->get_fields();
		$parsed_data    = $this->parse_post_data( $allowed_fields );
		$status         = ( new ProductPageShippingSettings() )->set( $parsed_data )->save();
		if ( $status ) {
			tps_manager_admin_message( __( 'Saved successfully.', 'tps-manager' ), Messages::TYPES[2] );
		}
	}
}
