<?php 

namespace ThemePaste\ShippingManager\Classes;

defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Traits\Hook;

class Pro {

    use Hook;

    public function __construct() {
        $this->filter( 'tpsm_settings_options', [$this, 'add_settings_options'] );
    }

    public function add_settings_options( $options ) {
        $options['distance-shipping'] = array(
            'label' => __( 'Distance Shipping', 'shipping-manager' ),
            'class' => '',
        );
        $options['role-based-shipping'] = array(
            'label' => __( 'Role Based Shipping', 'shipping-manager' ),
            'class' => '',
        );

        return $options;
    }
}