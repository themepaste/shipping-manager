<?php

namespace ThemePaste\ShippingManager\Classes;

defined( 'ABSPATH' ) || exit;

class Install {

    /**
     * Runs on plugin activation.
     *
     * Static because the activation hook is registered from the main plugin
     * file, before the plugin has booted.
     *
     * @return void
     */
    public static function bootstrapping() {
        if ( !self::is_plugin_version_up_to_date() ) {
            self::update_db_version();
        }

        set_transient( 'tpsm_do_activation_redirect', true, 30 );
    }

    /**
     * Check if the plugin version is up to date.
     *
     * @return bool
     */
    private static function is_plugin_version_up_to_date() {
        $installed_ver = get_option( 'TPSM_version' );

        if ( !is_string( $installed_ver ) || '' === $installed_ver ) {
            return false;
        }

        return version_compare( $installed_ver, TPSM_PLUGIN_VERSION, '=' );
    }

    /**
     * Update or add the plugin version to the options table.
     */
    private static function update_db_version() {
        update_option( 'TPSM_version', TPSM_PLUGIN_VERSION );
    }
}
