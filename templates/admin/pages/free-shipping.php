<?php
/**
 * Free Shipping Menu Page
 *
 * @since TSM_SINCE
 */

use \Themepaste\ShippingManager\Constants;
use \Themepaste\ShippingManager\Models\FreeShippingSettings;

defined( 'ABSPATH' ) || exit;
?>
<form class="tsm-admin-settings-form" method="POST">
  <?php tsm_admin_nonce_field(); ?>
  <div class="input-wrapper checkbox">
    <lable for="<?php echo FreeShippingSettings::HIDE_OTHERS ?>"><?php esc_html_e( 'Hide Others', 'shipping-manager' ); ?></lable>
    <input
      type="checkbox"
      id="<?php echo FreeShippingSettings::HIDE_OTHERS ?>"
      name="<?php echo FreeShippingSettings::HIDE_OTHERS ?>"
      value="<?php echo Constants::YES; ?>"
      <?php tsm_is_checked( $data[ FreeShippingSettings::HIDE_OTHERS ] ); ?>
    >
    <div class="help-tip"><?php esc_html_e( "Hide other shipping methods while free shipping is available." ); ?></div>
  </div>
  <div class="input-wrapper checkbox">
    <lable for="<?php echo FreeShippingSettings::FREE_SHIPPING_BAR ?>"><?php esc_html_e( 'Free Shipping Bar', 'shipping-manager' ); ?></lable>
    <input
      type="checkbox"
      id="<?php echo FreeShippingSettings::FREE_SHIPPING_BAR ?>"
      name="<?php echo FreeShippingSettings::FREE_SHIPPING_BAR ?>"
      value="<?php echo Constants::YES; ?>"
		  <?php tsm_is_checked( $data[ FreeShippingSettings::FREE_SHIPPING_BAR ] ); ?>
    >
    <div class="help-tip"><?php esc_html_e( "Enable free shipping bar for customers. Customers will see a bar for target to achieve free shipping." ); ?></div>
  </div>
  <div class="input-wrapper checkbox">
    <lable for="<?php echo FreeShippingSettings::MINIMUM_AMOUNT ?>"><?php esc_html_e( 'Minimum Amount', 'shipping-manager' ); ?></lable>
    <input
      type="checkbox"
      id="<?php echo FreeShippingSettings::MINIMUM_AMOUNT ?>"
      name="<?php echo FreeShippingSettings::MINIMUM_AMOUNT ?>"
      value="<?php echo Constants::YES; ?>"
  		<?php tsm_is_checked( $data[ FreeShippingSettings::MINIMUM_AMOUNT ] ); ?>
    >
    <div class="help-tip"><?php esc_html_e( "This will enable a custom minimum amount for free shipping. Otherwise it will use default minimum amount set in WooCommerce free shipping." ); ?></div>
  </div>
  <div class="input-wrapper amount">
    <lable for="<?php echo FreeShippingSettings::CART_AMOUNT ?>"><?php esc_html_e( 'Cart Amount', 'shipping-manager' ); ?></lable>
    <input
      type="text"
      id="<?php echo FreeShippingSettings::CART_AMOUNT ?>"
      name="<?php echo FreeShippingSettings::CART_AMOUNT ?>"
      value="<?php echo esc_attr( $data[ FreeShippingSettings::CART_AMOUNT ] ); ?>"
    >
    <div class="help-tip"><?php esc_html_e( "Cart minimum amount for free shipping." ); ?></div>
  </div>
  <div class="input-wrapper checkbox">
    <lable for="<?php echo FreeShippingSettings::AFTER_COUPON ?>"><?php esc_html_e( 'After Coupon', 'shipping-manager' ); ?></lable>
    <input
      type="checkbox"
      id="<?php echo FreeShippingSettings::AFTER_COUPON ?>"
      name="<?php echo FreeShippingSettings::AFTER_COUPON ?>"
      value="<?php echo Constants::YES; ?>"
  		<?php tsm_is_checked( $data[ FreeShippingSettings::AFTER_COUPON ] ); ?>
    >
    <div class="help-tip"><?php esc_html_e( "Enable this to calculate free shipping after coupon discount has been applied for product." ); ?></div>
  </div>
  <div class="input-wrapper submit">
    <button class="woocommerce-save-button components-button is-primary" value="free-shipping"><?php esc_html_e( 'Save', 'free-shipping' ); ?></button>
  </div>
</form>