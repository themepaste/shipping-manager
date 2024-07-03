<?php
/**
 * Main layout template for admin settings page
 *
 * @since TSM_SINCE
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="tsm-admin-wrapper">
  <div class="row-wrapper">
    <div class="sidebar"><?php tsm_template( 'admin/parts/sidebar'); ?></div>
    <div class="main"><?php tsm_template( 'admin/parts/main'); ?></div>
  </div>
  <div class="row-wrapper">
    <div class="footer"><?php tsm_template( 'admin/parts/footer' ) ?></div>
  </div>
</div>

