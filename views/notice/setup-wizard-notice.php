<?php 

    $setup_url = esc_url( admin_url( 'admin.php?page=shipping-manager' ) );
    ?>
        <div class="notice notice-warning is-dismissible tpsm-setup-notice">
            <p><strong>🎉 Welcome! Please complete the setup wizard.</strong></p>
            <p>
                Never miss an important update. <br>
                <a href="<?php echo $setup_url; ?>" class="button button-primary">Launch Setup Wizard</a>
            </p>
        </div>