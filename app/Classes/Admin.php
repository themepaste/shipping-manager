<?php 

namespace ThemePaste\ShippingManager\Classes;

defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Traits\Hook;

class Admin {

    use Hook;

    public function __construct() {
        $this->action( 'plugins_loaded', function() {
            ( new Settings() )->init();
        } );
    }

}