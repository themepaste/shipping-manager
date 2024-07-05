<?php
/**
 * Layout sidebar part
 *
 * @since TSM_SINCE
 */
defined( 'ABSPATH' ) || exit;

use Themepaste\ShippingManager\Admin\Routes;
?>
<div class="logo-wrapper">
  <h3>Shipping Manager</h3>
  <span>by Themepaste</span>
</div>
<div class="sidebar-menu-links">
  <ul>
    <li>
      <a
        class='<?php tsm_is_active_menu( Routes::SHIPPING_FEES, 'active' ); ?>'
        href='<?php echo esc_attr( tsm_url( Routes::SHIPPING_FEES ) ); ?>'
      >
          <?php esc_html_e( 'Shipping Fees', 'shipping-manager' ); ?>
      </a>
    </li>
    <li>
      <a
        class='<?php tsm_is_active_menu( Routes::FREE_SHIPPING, 'active' ); ?>'
        href='<?php echo esc_attr( tsm_url( Routes::FREE_SHIPPING ) ); ?>'
      >
          <?php esc_html_e( 'Free Shipping', 'shipping-manager' ); ?>
      </a>
    </li>
    <li>
      <a
        class='<?php tsm_is_active_menu( Routes::PER_PRODUCT_SHIPPING, 'active' ); ?>'
        href='<?php echo esc_attr( tsm_url( Routes::PER_PRODUCT_SHIPPING ) ); ?>'
      >
          <?php esc_html_e( 'Per Product Shipping', 'shipping-manager' ); ?>
      </a>
    </li>
    <li>
      <a
        class='<?php tsm_is_active_menu( Routes::PRODUCT_PAGE_SHIPPING, 'active' ); ?>'
        href='<?php echo esc_attr( tsm_url( Routes::PRODUCT_PAGE_SHIPPING ) ); ?>'
      >
          <?php esc_html_e( 'Product Page Shipping', 'shipping-manager' ); ?>
      </a>
    </li>
    <li>
      <a
        class='<?php tsm_is_active_menu( Routes::TRACK_SHIPPING, 'active' ); ?>'
        href='<?php echo esc_attr( tsm_url( Routes::TRACK_SHIPPING ) ); ?>'
      >
          <?php esc_html_e( 'Track Shipping', 'shipping-manager' ); ?>
      </a>
    </li>
  </ul>
</div>
<div class="misalliances-links">
  <ul>
    <li><a href="#docs"><?php esc_html_e( 'Docs', 'shipping-manager' ); ?></a></li>
    <li><a href="#docs"><?php esc_html_e( 'Support', 'shipping-manager' ); ?></a></li>
    <li><a href="#docs"><?php esc_html_e( 'Feedback', 'shipping-manager' ); ?></a></li>
  </ul>
</div>