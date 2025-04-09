<?php

use ThemePaste\ShippingManager\Helpers\Utility;
?>

<div class="wrap">
    <div class="themepaste-shipping-manager-wrapper">
        <?php echo Utility::get_template( 'settings/parts/topbar.php' ); ?>
        <div class="shipping-manager-container">
            <?php echo Utility::get_template( 'settings/parts/sidebar.php' ); ?>
            <?php echo Utility::get_template( 'settings/parts/main.php' ); ?>
        </div>
    </div>
</div>