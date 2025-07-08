<?php 

namespace ThemePaste\ShippingManager\Classes;

defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Traits\Hook;
use ThemePaste\ShippingManager\Helpers\Utility;
use ThemePaste\ShippingManager\Traits\Asset;

class Notice {

    use Hook;
    use Asset;

    public function __construct() {
        $this->action( 'admin_notices', [$this, 'render_admin_notices'] );
        $this->action( 'admin_enqueue_scripts', [$this, 'enqueue_assets'] );
    }

    public function render_admin_notices() {
        // Only show to admin users
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        // Already completed? Then skip notice
        if ( get_option( 'tpsm_is_setup_wizard', false ) ) {
            return;
        }

        // Check if user dismissed the notice manually
        if ( get_user_meta( get_current_user_id(), 'tpsm_dismissed_setup_notice', true ) ) {
            return;
        }

        printf( '%s', Utility::get_template( 'notice/setup-wizard-notice.php' ) );
    }

    public function enqueue_assets() {
        $this->enqueue_style(
            'tpsm-notice',
            TPSM_ASSETS_URL . '/admin/css/notice.css',
        );
    }
}