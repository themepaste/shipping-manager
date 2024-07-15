<?php
/**
 * Shipping Fees Menu Page
 *
 * @since TSM_SINCE
 */
defined( 'ABSPATH' ) || exit;

use \Themepaste\ShippingManager\Constants;
use \Themepaste\ShippingManager\Models\ShippingFeesSettings;
?>

<form class="tsm-admin-settings-form" method="POST">
	<?php tsm_admin_nonce_field(); ?>
	<div class="input-wrapper checkbox">
		<label for="<?php echo ShippingFeesSettings::ENABLE_PROCESSING_FEES; ?>"><?php esc_html_e( 'Add Processing Fee', 'shipping-manager' ); ?></label>
		<input
      id="<?php echo ShippingFeesSettings::ENABLE_PROCESSING_FEES; ?>"
      name="<?php echo ShippingFeesSettings::ENABLE_PROCESSING_FEES; ?>"
      value="<?php echo Constants::YES; ?>"
      type="checkbox"
      <?php tsm_is_checked( $data[ ShippingFeesSettings::ENABLE_PROCESSING_FEES ] );?>
    >
		<div class="help-tip"><?php esc_html_e( 'Adds a flat processing fee to process the shipment.', 'shipping-manager' ); ?></div>
	</div>
	<div class="input-wrapper amount">
		<label for="<?php echo ShippingFeesSettings::PROCESSING_FEES_AMOUNT; ?>"><?php esc_html_e( 'Amount', 'shipping-manager' ); ?></label>
		<input
      id="<?php echo ShippingFeesSettings::PROCESSING_FEES_AMOUNT; ?>"
      name="<?php echo ShippingFeesSettings::PROCESSING_FEES_AMOUNT; ?>"
      type="text"
      value="<?php echo esc_attr( $data[ ShippingFeesSettings::PROCESSING_FEES_AMOUNT ] ); ?>"
    >
		<div class="help-tip"><?php esc_html_e( 'Processing fee amount.', 'shipping-manager' ); ?></div>
	</div>

	<div class="input-wrapper checkbox">
		<label for="<?php echo ShippingFeesSettings::ENABLE_WEIGHT_BASED_FEES; ?>"><?php esc_html_e( 'Weight Based Fee', 'shipping-manager' ); ?></label>
		<input
      id="<?php echo ShippingFeesSettings::ENABLE_WEIGHT_BASED_FEES; ?>"
      name="<?php echo ShippingFeesSettings::ENABLE_WEIGHT_BASED_FEES; ?>"
      value="<?php echo Constants::YES; ?>"
      type="checkbox"
		  <?php tsm_is_checked( $data[ ShippingFeesSettings::ENABLE_WEIGHT_BASED_FEES ] ); ?>
    >
		<div class="help-tip"><?php esc_html_e( 'Adds weight based fee for product.', 'shipping-manager' ); ?></div>
	</div>
<!--	<div class="input-wrapper radio">-->
<!--		<div class="single-radio-option">-->
<!--			<input type="radio" value="per-unit" name="weight-unit">-->
<!--			<label for="">--><?php //esc_html_e( 'Per Unit', 'shipping-manager' ); ?><!--</label>-->
<!--			<div class="help-tip">--><?php //esc_html_e( 'Add fees based on per unit', 'shipping-manager' ); ?><!--</div>-->
<!--		</div>-->
<!--		<div class="single-radio-option">-->
<!--			<input type="radio" value="unit-range" name="weight-unit">-->
<!--			<label for="">--><?php //esc_html_e( 'Unit Range', 'shipping-manager' ); ?><!--</label>-->
<!--			<div class="help-tip">--><?php //esc_html_e( 'Add fees based on unit range', 'shipping-manager' ); ?><!--</div>-->
<!--		</div>-->
<!--	</div>-->
	<div class="input-wrapper amount">
		<label for="<?php echo ShippingFeesSettings::WEIGHT_BASED_PER_UNIT_AMOUNT_FEES; ?>"><?php esc_html_e( 'Per Unit Fee', 'shipping-manager' ); ?></label>
		<input
      id="<?php echo ShippingFeesSettings::WEIGHT_BASED_PER_UNIT_AMOUNT_FEES; ?>"
      name="<?php echo ShippingFeesSettings::WEIGHT_BASED_PER_UNIT_AMOUNT_FEES; ?>"
      type="text"
      value="<?php echo esc_attr( $data[ ShippingFeesSettings::WEIGHT_BASED_PER_UNIT_AMOUNT_FEES ] );?>"
    >
		<div class="help-tip"><?php esc_html_e( 'Fees for per unit weight for product shipping.', 'shipping-manager' ); ?></div>
	</div>
<!--	<div class="input-wrapper range">-->
<!--		<label for="">--><?php //esc_html_e( 'Per Unit Fee', 'shipping-manager' ); ?><!--</label>-->
<!--		<div class="range-row-wrapper">-->
<!--			from-->
<!--			<input type="text" value="">-->
<!--			to-->
<!--			<input type="text" value="">-->
<!--			fee-->
<!--			<input type="text" value="">-->
<!--			<div class="remove-row-button">Remove</div>-->
<!--		</div>-->
<!--		<div class="add-new-row-button">-->
<!--			<div class="add-new-button-text">Add New</div>-->
<!--		</div>-->
<!--		<div class="help-tip">--><?php //esc_html_e( 'Fees for unit range weight for product shipping.', 'shipping-manager' ); ?><!--</div>-->
<!--	</div>-->
  <div class="input-wrapper submit">
    <button class="woocommerce-save-button components-button is-primary" value="free-shipping"><?php esc_html_e( 'Save', 'free-shipping' ); ?></button>
  </div>
</form>