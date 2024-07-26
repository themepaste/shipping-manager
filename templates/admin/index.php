<?php
/**
 * Main layout template for admin settings page
 *
 * @since 1.1.0
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="tsm-admin-wrapper">
  <div class="row-wrapper">
    <div class="sidebar"><?php tsm_template_parts( 'admin/parts/sidebar'); ?></div>
    <div class="main"><?php tsm_template_parts( 'admin/parts/main'); ?></div>
  </div>
</div>

