<?php
/**
 * Free Shipping Menu Page
 *
 * @since TSM_SINCE
 */
defined( 'ABSPATH' ) || exit;
?>
<form class="tsm-admin-settings-form">
  <div class="input-wrapper checkbox">
    <lable for="hide-other"><?php esc_html_e( 'Hide Others', 'shipping-manager' ); ?></lable>
    <input type="checkbox" id="hide-other" name="hide-other">
    <div class="help-tip"><?php esc_html_e( "Hide other shipping methods while free shipping is available." ); ?></div>
  </div>
  <div class="input-wrapper checkbox">
    <lable for="free-shipping-bar"><?php esc_html_e( 'Free Shipping Bar', 'shipping-manager' ); ?></lable>
    <input type="checkbox" id="free-shipping-bar" name="free-shipping-bar">
    <div class="help-tip"><?php esc_html_e( "Enable free shipping bar for customers." ); ?></div>
  </div>
</form>