<?php
/**
 * Shipping Fees Menu Page
 *
 * @since TSM_SINCE
 */
defined( 'ABSPATH' ) || exit;

use \Themepaste\ShippingManager\Constants;

?>

<form class="tsm-admin-settings-form" method="POST">
	<div class="input-wrapper checkbox">
		<label for=""><?php esc_html_e( 'Add Processing Fee', 'shipping-manager' ); ?></label>
		<input type="checkbox" value="<?php echo Constants::NO; ?>">
		<div class="help-tip"><?php esc_html_e( 'Adds a flat processing fee to process the shipment.', 'shipping-manager' ); ?></div>
	</div>
	<div class="input-wrapper amount">
		<label for=""><?php esc_html_e( 'Amount', 'shipping-manager' ); ?></label>
		<input type="text" value="">
		<div class="help-tip"><?php esc_html_e( 'Processing fee amount.', 'shipping-manager' ); ?></div>
	</div>

	<div class="input-wrapper checkbox">
		<label for=""><?php esc_html_e( 'Weight Based Fee', 'shipping-manager' ); ?></label>
		<input type="checkbox" value="<?php echo Constants::NO; ?>">
		<div class="help-tip"><?php esc_html_e( 'Adds weight based fee for product.', 'shipping-manager' ); ?></div>
	</div>
	<div class="input-wrapper radio">
		<div class="single-radio-option">
			<input type="radio" value="per-unit" name="weight-unit">
			<label for=""><?php esc_html_e( 'Per Unit', 'shipping-manager' ); ?></label>
			<div class="help-tip"><?php esc_html_e( 'Add fees based on per unit', 'shipping-manager' ); ?></div>
		</div>
		<div class="single-radio-option">
			<input type="radio" value="unit-range" name="weight-unit">
			<label for=""><?php esc_html_e( 'Unit Range', 'shipping-manager' ); ?></label>
			<div class="help-tip"><?php esc_html_e( 'Add fees based on unit range', 'shipping-manager' ); ?></div>
		</div>
	</div>
	<div class="input-wrapper amount">
		<label for=""><?php esc_html_e( 'Per Unit Fee', 'shipping-manager' ); ?></label>
		<input type="text" value="">
		<div class="help-tip"><?php esc_html_e( 'Fees for per unit weight for product shipping.', 'shipping-manager' ); ?></div>
	</div>
	<div class="input-wrapper range">
		<label for=""><?php esc_html_e( 'Per Unit Fee', 'shipping-manager' ); ?></label>
		<div class="range-row-wrapper">
			from
			<input type="text" value="">
			to
			<input type="text" value="">
			fee
			<input type="text" value="">
			<div class="remove-row-button">Remove</div>
		</div>
		<div class="add-new-row-button">
			<div class="add-new-button-text">Add New</div>
		</div>
		<div class="help-tip"><?php esc_html_e( 'Fees for unit range weight for product shipping.', 'shipping-manager' ); ?></div>
	</div>
</form>