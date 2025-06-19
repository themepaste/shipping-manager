<?php 

namespace ThemePaste\ShippingManager\Traits;

defined( 'ABSPATH' ) || exit;

trait Data {
    protected $conditions_data = [
        'tpsm-flat-rate'            => 'Flat Rate',
        'tpsm-sub-total-price'      => 'Cart Subtotal',
        'tpsm-total-price'          => 'Cart Total',
        'tpsm-flat-rate-per-weight-unit' => 'Flat rate per Weight Unit',
        'tpsm-total-weight'         => 'Total Weight',
        'tpsm-shipping-class'       => 'Shipping Class'
    ];
}