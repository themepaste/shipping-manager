<?php
/**
 * Layout sidebar part
 *
 * @since 1.1.0
 */

defined( 'ABSPATH' ) || exit;

use Themepaste\ShippingManager\Admin\Routes;

?>
<div class="logo-wrapper">
  <h3>Shipping Manager</h3>
  <span>by Themepaste</span>
  <div class="sidebar-menu-links">
  <ul>
    <li>
      <a
        class='<?php tps_manager_is_active_menu( Routes::SHIPPING_FEES, 'active' ); ?>'
        href='<?php echo esc_attr( tps_manager_url( Routes::SHIPPING_FEES ) ); ?>'
      >
          <?php esc_html_e( 'Shipping Fees', 'tps-manager' ); ?>
      </a>
    </li>
    <li>
      <a
        class='<?php tps_manager_is_active_menu( Routes::FREE_SHIPPING, 'active' ); ?>'
        href='<?php echo esc_attr( tps_manager_url( Routes::FREE_SHIPPING ) ); ?>'
      >
          <?php esc_html_e( 'Free Shipping', 'tps-manager' ); ?>
      </a>
    </li>
    <li>
      <a
        class='<?php tps_manager_is_active_menu( Routes::PER_PRODUCT_SHIPPING, 'active' ); ?>'
        href='<?php echo esc_attr( tps_manager_url( Routes::PER_PRODUCT_SHIPPING ) ); ?>'
      >
          <?php esc_html_e( 'Per Product Shipping', 'tps-manager' ); ?>
      </a>
    </li>
    <li>
      <a
        class='<?php tps_manager_is_active_menu( Routes::PRODUCT_PAGE_SHIPPING, 'active' ); ?>'
        href='<?php echo esc_attr( tps_manager_url( Routes::PRODUCT_PAGE_SHIPPING ) ); ?>'
      >
          <?php esc_html_e( 'Product Page Shipping', 'tps-manager' ); ?>
      </a>
    </li>
<!--    <li>-->
<!--      <a-->
<!--        class='--><?php //tps_manager_is_active_menu( Routes::TRACK_SHIPPING, 'active' ); ?><!--'-->
<!--        href='--><?php //echo esc_attr( tps_manager_url( Routes::TRACK_SHIPPING ) ); ?><!--'-->
<!--      >-->
<!--          --><?php //esc_html_e( 'Track Shipping', 'tps-manager' ); ?>
<!--      </a>-->
<!--    </li>-->
  </ul>
</div>
</div>

<div class="misalliances-links">
  <ul>
    <li><a href="#docs"><?php esc_html_e( 'Docs', 'tps-manager' ); ?></a></li>
    <li><a href="#docs"><?php esc_html_e( 'Support', 'tps-manager' ); ?></a></li>
    <li><a href="#docs"><?php esc_html_e( 'Feedback', 'tps-manager' ); ?></a></li>
  </ul>
</div>