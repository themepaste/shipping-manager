<?php
/**
 * Shipping Fees Menu Page
 *
 * @since 1.1.0
 */
defined( 'ABSPATH' ) || exit;

use \Themepaste\ShippingManager\Constants;
use \Themepaste\ShippingManager\Models\ShippingFeesSettings;
?>

<form class="tsm-admin-settings-form" method="POST">
	<?php tsm_admin_nonce_field(); ?>
	<div class="input-wrapper checkbox">
		<label for="<?php echo esc_attr( ShippingFeesSettings::ENABLE_PROCESSING_FEES ); ?>"><?php esc_html_e( 'Add Processing Fee', 'tps-manager' ); ?></label>
		<input
      id="<?php echo esc_attr( ShippingFeesSettings::ENABLE_PROCESSING_FEES ); ?>"
      name="<?php echo esc_attr( ShippingFeesSettings::ENABLE_PROCESSING_FEES ); ?>"
      value="<?php echo esc_attr( Constants::YES ); ?>"
      type="checkbox"
      <?php tsm_is_checked( $data[ ShippingFeesSettings::ENABLE_PROCESSING_FEES ] );?>
    >
		<div class="help-tip"><?php esc_html_e( 'Adds a flat processing fee to process the shipment.', 'tps-manager' ); ?></div>
	</div>
	<div class="input-wrapper amount">
		<label for="<?php echo esc_attr( ShippingFeesSettings::PROCESSING_FEES_AMOUNT ); ?>"><?php esc_html_e( 'Amount', 'tps-manager' ); ?></label>
		<input
      id="<?php echo esc_attr( ShippingFeesSettings::PROCESSING_FEES_AMOUNT ); ?>"
      name="<?php echo esc_attr( ShippingFeesSettings::PROCESSING_FEES_AMOUNT ); ?>"
      type="text"
      value="<?php echo esc_attr( $data[ ShippingFeesSettings::PROCESSING_FEES_AMOUNT ] ); ?>"
    >
		<div class="help-tip"><?php esc_html_e( 'Processing fee amount.', 'tps-manager' ); ?></div>
	</div>
  <?php tsm_template_parts( 'admin/pages/shipping-fees/weight-settings' ); ?>
  <div class="input-wrapper submit">
    <button class="woocommerce-save-button components-button is-primary" value="free-shipping"><?php esc_html_e( 'Save', 'free-shipping' ); ?></button>
  </div>
</form>