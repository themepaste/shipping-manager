<?php
/**
 * Layout main part
 *
 * @since TSM_SINCE
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="nav"></div>

<div class="body-wrapper">
  <div class="message-wrapper">
	  <?php tsm_template( 'admin/parts/messages' ); ?>
  </div>
  <div class="page-wrapper">
	  <?php tsm_template_parts( 'admin/pages/' . $page ); ?>
  </div>
</div>
<div class="footer"><?php tsm_template( 'admin/parts/footer' ) ?></div>