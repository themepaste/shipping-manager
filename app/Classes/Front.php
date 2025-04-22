<?php 

namespace ThemePaste\ShippingManager\Classes;

defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Traits\Hook;
use ThemePaste\ShippingManager\Traits\Asset;

class Front {

    use Hook;
    use Asset;

    public function __construct() {
        $this->action( 'wp_enqueue_scripts', [$this, 'enqueue_css'] );
        $this->action( 'wp_enqueue_scripts', [$this, 'enqueue_scripts'] );
    }

    public function enqueue_css() {
        $this->enqueue_style( 
            'tpsm-front',
            TPSM_ASSETS_URL . '/front/css/front.css'
        );
    }
}