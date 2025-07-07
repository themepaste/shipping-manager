<?php 

namespace ThemePaste\ShippingManager\Classes;

defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Traits\Hook;
use ThemePaste\ShippingManager\Helpers\Utility;
use ThemePaste\ShippingManager\Traits\Asset;

class Wizard {

    use Hook;
    use Asset;

    public function __construct() {
        $this->action( 'admin_init', [$this, 'redirect_to_setup_wizard_page'] );
        $this->action( 'admin_menu', [$this, 'add_setup_wizard_page'] );
        $this->action( 'admin_enqueue_scripts', [$this, 'enqueue_assets'] );
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
            delete_transient('tpsm_do_activation_redirect');
        
            if( get_option( 'tpsm_is_setup_wizard', 0 ) ) {
                return;
            }
            wp_redirect( add_query_arg( 
                array(
                    'page'     => 'tpsm_setup_wizard',
                ),
                admin_url( 'admin.php' )
            ) );
            exit;
        }
    }

    public function add_setup_wizard_page() {
        add_menu_page( 
            null,
            'Shipping Manager',
            'manage_options',
            'tpsm_setup_wizard',
            [$this, 'render_setup_wizard_page'],
        );
    }

    public function render_setup_wizard_page() {
        printf( '%s', Utility::get_template( 'wizard/wizard.php' ) );
    }


}