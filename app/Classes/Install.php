<?php 

namespace ThemePaste\ShippingManager\Classes;

use ThemePaste\ShippingManager\Traits\Hook;

class Install {

    use Hook;

    public function __construct() {
        $this->activation( [$this, 'bootstrapping'] );
    }

    public function bootstrapping() {
        if( ! $this->is_plugin_version_up_to_date() ) {
            $this->update_db_version(); 
        }

        set_transient( 'tpsm_do_activation_redirect', true, 30 );

        //Update inital general settings
        update_option( 'tpsm-general_settings', [
            'method-title'      => '',
            'is-plugin-enable'  => 1,
        ] );
    }

    /**
	 * Check if the plugin version is up to date.
	 *
	 * @return bool
	 */
	private function is_plugin_version_up_to_date() {
		$installed_ver = get_option( 'TPSM_version' );
		return version_compare( $installed_ver, TPSM_PLUGIN_VERSION, '=' );
	}

    /**
    * Update or add the plugin version to the options table.
    */
    private function update_db_version() {
        update_option( 'TPSM_version', TPSM_PLUGIN_VERSION );
    }
}