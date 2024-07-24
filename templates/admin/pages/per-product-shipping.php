<?php
/**
 * Per Product Shipping Menu Page
 *
 * @since TSM_SINCE
 */
defined( 'ABSPATH' ) || exit;

use \Themepaste\ShippingManager\Constants;
use \Themepaste\ShippingManager\Models\PerProductShippingSettings;
?>

<form class="tsm-admin-settings-form" method="POST">

	<?php tsm_admin_nonce_field(); ?>
  <div  class="input-wrapper checkbox">
    <label for="<?php echo PerProductShippingSettings::PER_PRODUCT_SHIPPING; ?>"><?php esc_html_e( 'Enable Per Product Shipping', 'shipping-manager' ); ?></label>
    <input
      type="checkbox"
      id="<?php echo PerProductShippingSettings::PER_PRODUCT_SHIPPING; ?>"
      name="<?php echo PerProductShippingSettings::PER_PRODUCT_SHIPPING; ?>"
      value="<?php echo Constants::YES; ?>"
      <?php tsm_is_checked( $data[ PerProductShippingSettings::PER_PRODUCT_SHIPPING ] ); ?>
    >
    <div class="help-tip"><?php esc_html_e('This will enable shipping for every product.', 'shipping-manager'); ?></div>
  </div>

  <div  class="input-wrapper checkbox">
    <label for="<?php echo PerProductShippingSettings::OVERRIDE_OTHERS; ?>"><?php esc_html_e( 'Override Others', 'shipping-manager' ); ?></label>
    <input
      type="checkbox"
      id="<?php echo PerProductShippingSettings::OVERRIDE_OTHERS; ?>"
      name="<?php echo PerProductShippingSettings::OVERRIDE_OTHERS; ?>"
      value="<?php echo Constants::YES; ?>"
		<?php tsm_is_checked( $data[ PerProductShippingSettings::OVERRIDE_OTHERS ] ); ?>
    >
    <div class="help-tip"><?php esc_html_e('Override all other shipping rules if per product shipping settings is set for product level.', 'shipping-manager'); ?></div>
  </div>

  <div class="input-wrapper submit">
    <button class="woocommerce-save-button components-button is-primary" value="free-shipping"><?php esc_html_e( 'Save', 'shipping-manager' ); ?></button>
  </div>
</form>
