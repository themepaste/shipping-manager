<?php 
defined( 'ABSPATH' ) || exit;
$setup_url = esc_url( admin_url( 'admin.php?page=tpsm_setup_wizard' ) );
?>
    <div class="notice notice-warning is-dismissible tpsm-setup-notice">
        <p style="display: flex; align-items: center; justify-content: space-between;">
            <span>
                <strong>
                    <?php echo esc_html( '🎉 Welcome! Please complete the setup wizard.', 'shipping-manager' ); ?>
                </strong>
                <?php esc_html_e( 'Before you can use Shipping Manager, you need to complete the setup wizard.', 'shipping-manager' ); ?>
            </span>
            <!-- <br> -->
            <a href="<?php echo $setup_url; ?>" class="button button-primary"><?php esc_html_e( 'Launch Setup Wizard', 'shipping-manager' ) ?></a>
        </p>
    </div>