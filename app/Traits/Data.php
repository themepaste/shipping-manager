<?php 

namespace ThemePaste\ShippingManager\Traits;

defined( 'ABSPATH' ) || exit;

trait Data {
    protected $conditions_data = [
        'always'            => 'Flat Rate',
        'total-price'       => 'Cart Total Price',
        'sub-total-price'   => 'Cart  Subtotal Price',
        'weight'            => 'Total Weight',
        'shipping-class'    => 'Shipping Class'
    ];
}