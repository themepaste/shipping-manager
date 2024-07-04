<?php
/**
 * Free Shipping Menu Page
 *
 * @since TSM_SINCE
 */
defined( 'ABSPATH' ) || exit;
?>
<form>
  <div class="input-wrapper checkbox">
    <lable for="hide-other"><?php esc_html_e( 'Hide Others', 'shipping-manager' ); ?></lable>
    <input type="checkbox" id="hide-other" name="hide-other">
    <div class="help-tip"><?php esc_html_e( "Hide other shipping methods while free shipping is available." ); ?></div>
  </div>
</form>