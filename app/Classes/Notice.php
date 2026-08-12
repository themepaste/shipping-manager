<?php

namespace ThemePaste\ShippingManager\Classes;

defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Traits\Hook;
use ThemePaste\ShippingManager\Helpers\Utility;
use ThemePaste\ShippingManager\Traits\Asset;

class Notice {

    use Hook;
    use Asset;

    /**
     * User meta key recording that the setup notice was dismissed.
     */
    const DISMISSED_META = 'tpsm_dismissed_setup_notice';

    public function __construct() {
        $this->action( 'admin_notices', [$this, 'render_admin_notices'] );
        $this->action( 'admin_enqueue_scripts', [$this, 'enqueue_assets'] );
        $this->ajax_priv( 'tpsm_dismiss_setup_notice', [$this, 'dismiss_setup_notice'] );
    }

    /**
     * Whether the setup notice still needs to be shown to the current user.
     *
     * @return bool
     */
    private function should_show_notice() {
        // Only show to admin users
        if ( !current_user_can( 'manage_options' ) ) {
            return false;
        }

        // Already completed? Then skip notice
        $setup_wizard_value = get_option( 'tpsm_is_setup_wizard', null );
        if ( in_array( $setup_wizard_value, ['0', '1', 0, 1], true ) ) {
            return false;
        }

        // Check if user dismissed the notice manually
        if ( get_user_meta( get_current_user_id(), self::DISMISSED_META, true ) ) {
            return false;
        }

        return true;
    }

    public function render_admin_notices() {
        if ( !$this->should_show_notice() ) {
            return;
        }

        echo Utility::get_template( 'notice/setup-wizard-notice.php' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Template output is escaped at the point of use.
    }

    /**
     * Persist the dismissal so the notice does not come back on the next page load.
     *
     * The notice has always been marked `is-dismissible`, but nothing recorded
     * the dismissal, so closing it only lasted until the page was reloaded.
     *
     * @return void
     */
    public function dismiss_setup_notice() {
        check_ajax_referer( 'tpsm_dismiss_setup_notice', 'nonce' );

        if ( !current_user_can( 'manage_options' ) ) {
            wp_send_json_error( null, 403 );
        }

        update_user_meta( get_current_user_id(), self::DISMISSED_META, 1 );

        wp_send_json_success();
    }

    public function enqueue_assets() {
        if ( !$this->should_show_notice() ) {
            return;
        }

        $this->enqueue_style(
            'tpsm-notice',
            TPSM_ASSETS_URL . '/admin/css/notice.css'
        );

        $this->enqueue_script(
            'tpsm-notice',
            TPSM_ASSETS_URL . '/admin/js/notice.js',
            ['jquery']
        );

        $this->localize_script(
            'tpsm-notice',
            'TPSM_NOTICE',
            [
                'ajax'  => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'tpsm_dismiss_setup_notice' ),
            ]
        );
    }
}
