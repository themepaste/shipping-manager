<?php

defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Helpers\Utility;

// Note: named tpsm_* rather than $current_screen, which is a WordPress global.
$tpsm_settings_option = tpsm_settings_options();
$tpsm_current_screen  = Utility::get_screen( 'tpsm-setting' );

$args = array(
    'settings_option' => $tpsm_settings_option,
    'current_screen'  => $tpsm_current_screen,
);
?>

<!-- For Display Notice  -->
<div class="wrap">
    <h1></h1>
</div>

<div class="wrap">
    <div class="themepaste-shipping-manager-wrapper">
        <?php
        // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- Template output is escaped at the point of use.
        echo Utility::get_template( 'settings/parts/topbar.php' );
        ?>
        <div class="shipping-manager-container">
            <?php echo Utility::get_template( 'settings/parts/sidebar.php', $args ); ?>
            <?php echo Utility::get_template( 'settings/parts/main.php', $args ); ?>
        </div>
        <?php
        echo Utility::get_template( 'settings/parts/guide-me.php', $args );
        // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
        ?>
    </div>
</div>