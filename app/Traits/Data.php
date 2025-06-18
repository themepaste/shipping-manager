<?php 

namespace ThemePaste\ShippingManager\Traits;

defined( 'ABSPATH' ) || exit;

trait Data {
    protected $conditions_data = [
        'always'        => 'Always',
        'total-price'   => 'Cart Total Price',
        'weight'        => 'Total Weight',
    ];
}