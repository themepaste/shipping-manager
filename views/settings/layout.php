<?php

defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Helpers\Utility;

$settings_option = tpsm_settings_options();
$current_screen  = Utility::get_screen( 'tpsm-setting' );

$args = array(
    'settings_option' => $settings_option,
    'current_screen'  => $current_screen,
    'general_settings'=> get_option( 'tpsm-general_settings' )
);
?>

<div class="wrap">
    <div class="themepaste-shipping-manager-wrapper">
        <?php echo Utility::get_template( 'settings/parts/topbar.php' ); ?>
        <div class="shipping-manager-container">
            <?php echo Utility::get_template( 'settings/parts/sidebar.php', $args ); ?>
            <?php echo Utility::get_template( 'settings/parts/main.php', $args ); ?>
        </div>
    </div>
</div>