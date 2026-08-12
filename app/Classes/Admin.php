<?php 

namespace ThemePaste\ShippingManager\Classes;

defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Traits\Hook;

class Admin {

    use Hook;

    public function __construct() {
        // App::hooks() already runs on `plugins_loaded`, so these can be wired
        // up directly. Re-adding a `plugins_loaded` callback from within that
        // same hook is fragile and was easy to break.
        new Wizard();
        new Notice();
        ( new Settings() )->init();
    }
}