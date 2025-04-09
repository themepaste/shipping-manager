<?php

defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Helpers\Utility;

$settings_option = tpsm_settings_options();
$current_screen  = Utility::get_screen( 'tpsm-setting' );

$args = array(
    'settings_option' => $settings_option,
    'current_screen'  => $current_screen,
);
?>

<div class="wrap">
    <div class="themepaste-shipping-manager-wrapper">
        <?php printf( Utility::get_template( 'settings/parts/topbar.php' )); ?>
        <div class="shipping-manager-container">
            <?php printf( Utility::get_template( 'settings/parts/sidebar.php', $args)); ?>
            <?php printf( Utility::get_template( 'settings/parts/main.php', $args )); ?>
        </div>
    </div>
</div>