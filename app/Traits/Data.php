<?php 

namespace ThemePaste\ShippingManager\Traits;

defined( 'ABSPATH' ) || exit;

trait Data {
    protected $conditions_data = [
        'tpsm-flat-rate'    => 'Flat Rate',
        'sub-total-price'   => 'Cart Subtotal Price',
        'total-price'       => 'Cart Total Price',
        'flat-rate-per-unit'=> 'Flat rate per Unit',
        'weight'            => 'Total Weight',
        'shipping-class'    => 'Shipping Class'

        
    ];
}