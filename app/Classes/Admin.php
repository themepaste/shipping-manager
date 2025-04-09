<?php 

namespace ThemePaste\ShippingManager\Classes;

use ThemePaste\ShippingManager\Traits\Hook;

class Admin {

    use Hook;

    public function __construct() {
        ( new Settings() )->init();
    }

}