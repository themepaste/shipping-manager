<?php

namespace ThemePaste\ShippingManager\Classes;

defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Helpers\Utility;
use ThemePaste\ShippingManager\Traits\Asset;
use ThemePaste\ShippingManager\Traits\Hook;

class Wizard {

    use Hook;
    use Asset;

    public function __construct() {
        $this->action( 'admin_init', [$this, 'redirect_to_setup_wizard_page'] );
        $this->action( 'admin_menu', [$this, 'add_setup_wizard_page'] );
        $this->action( 'admin_enqueue_scripts', [$this, 'enqueue_assets'] );
        $this->action( 'admin_init', [$this, 'setup_wizard_process'] );
        $this->action( 'admin_head', [$this, 'hide_setup_wizard_menu'] );
    }

    public function setup_wizard_process() {
        if ( !isset( $_POST['tpsm_optin_submit'] ) ) {
            return;
        }

        if ( !isset( $_POST['tpsm-nonce_name'] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tpsm-nonce_name'] ) ), 'tpsm-nonce_action' ) ) {
            wp_die( esc_html__( 'Nonce verification failed.', 'shipping-manager' ) );
        }

        if ( !current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Unauthorized user', 'shipping-manager' ) );
        }

        $choice = isset( $_POST['tpsm_optin_choice'] ) ? sanitize_text_field( wp_unslash( $_POST['tpsm_optin_choice'] ) ) : '0';
        $value = (int) $choice === 1 ? 1 : 0;

        update_option( 'tpsm_is_setup_wizard', $value );

        if ( $value === 1 ) {
            tpsm_saved_remote_data();
        }

        // Land on the plugin's own section, which is the setup guide: it tells a
        // brand new install exactly what to do next and links to everything.
        $redirect_url = add_query_arg(
            array(
                'page'    => 'wc-settings',
                'tab'     => 'shipping',
                'section' => Settings::SETTING_PAGE_ID,
            ),
            admin_url( 'admin.php' )
        );

        wp_safe_redirect( $redirect_url );
        exit;
    }

    public function enqueue_assets( $screen ) {
        if ( 'toplevel_page_' . 'tpsm_setup_wizard' === $screen ) {
            $this->enqueue_style(
                'tpsm-setup-wizard',
                TPSM_ASSETS_URL . '/admin/css/wizard.css',
            );
        }
    }

    public function redirect_to_setup_wizard_page() {
        if ( get_transient( 'tpsm_do_activation_redirect' ) ) {
            delete_transient( 'tpsm_do_activation_redirect' );

            if ( get_option( 'tpsm_is_setup_wizard', 0 ) ) {
                return;
            }

            wp_safe_redirect(
                add_query_arg(
                    array(
                        'page' => 'tpsm_setup_wizard',
                    ),
                    admin_url( 'admin.php' )
                )
            );
            exit;
        }
    }

    public function add_setup_wizard_page() {

        add_menu_page(
            'Shipping Manager', // Page title
            'Shipping Manager', // Menu title (won't be visible due to CSS)
            'manage_options',
            'tpsm_setup_wizard',
            [$this, 'render_setup_wizard_page'],
            '',
            100
        );
    }

    /**
     * Hide the wizard menu item visually, but keep it in $menu
     * so get_admin_page_title() works and no PHP 8 deprecation is triggered.
     */
    public function hide_setup_wizard_menu() {
        ?>
<style>
/* Hide the top-level wizard menu item everywhere */
#toplevel_page_tpsm_setup_wizard {
    display: none !important;
}
</style>
<?php
}

    public function render_setup_wizard_page() {
        if ( !current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Unauthorized user', 'shipping-manager' ) );
        }

        echo Utility::get_template( 'wizard/wizard.php' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Template output is escaped at the point of use.
    }
}