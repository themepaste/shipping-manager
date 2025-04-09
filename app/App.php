<?php 
namespace ThemePaste\ShippingManager;

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

        /**
         * Register hooks for Admin end.
         * This hook is triggered only admin end.
         */
        if( is_admin() ) {

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