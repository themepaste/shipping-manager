<?php
/**
 * Layout main part
 *
 * @since 1.1.0
 */
defined( 'ABSPATH' ) || exit;
$page_title = apply_filters( 'tsm_template_page_title', $page );
?>
<div class="title-bar">
  <h4>
    <?php echo esc_html( $page_title ); ?>
  </h4>
</div>

<div class="body-wrapper">
  <div class="message-wrapper">
	  <?php tsm_template_parts( 'admin/parts/messages' ); ?>
  </div>
  <div class="page-wrapper">
	  <?php tsm_template_parts( 'admin/pages/' . $page ); ?>
  </div>
</div>
<div class="footer"><?php tsm_template( 'admin/parts/footer' ) ?></div>