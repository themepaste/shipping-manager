<?php
/**
 * Free Shipping Menu Page
 *
 * @since TSM_SINCE
 */
defined( 'ABSPATH' ) || exit;
?>
<form class="tsm-admin-settings-form" method="POST">
  <?php tsm_admin_nonce_field(); ?>
  <div class="input-wrapper checkbox">
    <lable for="hide-other"><?php esc_html_e( 'Hide Others', 'shipping-manager' ); ?></lable>
    <input type="checkbox" id="hide-other" name="hide-other">
    <div class="help-tip"><?php esc_html_e( "Hide other shipping methods while free shipping is available." ); ?></div>
  </div>
  <div class="input-wrapper checkbox">
    <lable for="free-shipping-bar"><?php esc_html_e( 'Free Shipping Bar', 'shipping-manager' ); ?></lable>
    <input type="checkbox" id="free-shipping-bar" name="free-shipping-bar">
    <div class="help-tip"><?php esc_html_e( "Enable free shipping bar for customers. Customers will see a bar for target to achieve free shipping." ); ?></div>
  </div>
  <div class="input-wrapper checkbox">
    <lable for="custom-minimum-amount"><?php esc_html_e( 'Minimum Amount', 'shipping-manager' ); ?></lable>
    <input type="checkbox" id="custom-minimum-amount" name="custom-minimum-amount">
    <div class="help-tip"><?php esc_html_e( "This will enable a custom minimum amount for free shipping. Otherwise it will use default minimum amount set in WooCommerce free shipping." ); ?></div>
  </div>
  <div class="input-wrapper amount">
    <lable for="custom-minimum-amount"><?php esc_html_e( 'Cart Amount', 'shipping-manager' ); ?></lable>
    <input type="text" id="custom-minimum-amount" name="custom-minimum-amount">
    <div class="help-tip"><?php esc_html_e( "Cart minimum amount for free shipping." ); ?></div>
  </div>
  <div class="input-wrapper checkbox">
    <lable for="after-coupon"><?php esc_html_e( 'After Coupon', 'shipping-manager' ); ?></lable>
    <input type="checkbox" id="after-coupon" name="after-coupon">
    <div class="help-tip"><?php esc_html_e( "Enable this to calculate free shipping after coupon discount has been applied for product." ); ?></div>
  </div>
  <div class="input-wrapper submit">
    <button value="free-shipping"><?php esc_html_e( 'Save', 'free-shipping' ); ?></button>
  </div>
</form>