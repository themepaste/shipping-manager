<?php 
namespace ThemePaste\ShippingManager;

defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Traits\Hook;

final class App {

    use Hook;

    /**
     * All hooks
     */
    static function hooks() {
        /**
         * Register the activation hook.
         * This hook is triggered when the plugin is activated.
         * It installs necessary database tables, sets initial seeds, 
         * and checks database versions.
         */
        new Classes\Install();
        new Classes\Shipping\ShippingMethod();

        /**
         * Register hooks for Admin end.
         * This hook is triggered only admin end.
         */
        if( is_admin() ) {
            new Classes\Admin();
        }
        /**
         * Register hooks for Front end.
         * This hook is triggered only front end.
         */
        if( ! is_admin() ) {

        }
        /**
         * Register hooks for Common.
         * This hook is triggered both admin & front also.
         */
        
    }
}