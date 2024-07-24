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
  <?php tsm_template_parts( 'admin/pages/shipping-fees/weight-settings' ); ?>
  <div class="input-wrapper submit">
    <button class="woocommerce-save-button components-button is-primary" value="free-shipping"><?php esc_html_e( 'Save', 'free-shipping' ); ?></button>
  </div>
</form>