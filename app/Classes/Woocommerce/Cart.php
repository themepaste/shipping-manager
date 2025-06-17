<?php 

namespace ThemePaste\ShippingManager\Classes\Woocommerce;

defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Helpers\Utility;
use ThemePaste\ShippingManager\Traits\Asset;
use ThemePaste\ShippingManager\Traits\Hook;

/**
 * Class Cart
 *
 * Handles custom shipping fee calculations based on cart total weight.
 *
 * @package ThemePaste\ShippingManager\Classes\Woocommerce
 */
class Cart {

    use Hook;
    use Asset;

    public $shipping_fees_settings;
    public $box_shipping_settings;
    public $free_shipping_settings;

    /**
     * Cart constructor.
     *
     * Registers necessary filters/hooks.
     */
    public function __construct() {
        $this->shipping_fees_settings   = tpsm_get_shipping_fees_settings();
        $this->box_shipping_settings    = tpsm_get_box_shipping_settings();
        $this->free_shipping_settings   = tpsm_get_free_shipping_settings();

        new FreeShipping( $this->free_shipping_settings );
        $this->filter( 'tpsm_shipping_fees_cost', [ $this, 'shipping_fees_cost' ] );
    }

    
    public function shipping_fees_cost( $data ) {

        $data = json_decode( $data, true );

        if( ! is_array( $data ) && empty( $data ) ) {
            return;
        }

        return '10';
    }

    /**
     * Calculates total valid weight of products in the cart.
     *
     * Skips products with no weight set (null, empty, or zero).
     *
     * @param \WC_Cart $cart The WooCommerce cart object.
     *
     * @return float The total weight of all products in the cart.
     */
    private function cart_total_product_weights( $cart ) {
        $total_weight = 0;

        foreach ( $cart->get_cart() as $cart_item ) {
            $product  = $cart_item['data'];
            $quantity = $cart_item['quantity'];
            $weight   = $product->get_weight();

            // Validate that the weight is set and greater than zero
            if ( $weight !== '' && $weight !== null && floatval( $weight ) > 0 ) {
                $total_weight += floatval( $weight ) * $quantity;
            }
        }

        return $total_weight;
    }

    private function cart_total_dimension_fee( $cart ) {
        $tpsm_dimensions_settings = $this->box_shipping_settings['box-shipping'] ?? [];
        $total_fee = 0;
    
        foreach ( $cart->get_cart() as $cart_item ) {
            $product    = $cart_item['data'];
            $quantity   = $cart_item['quantity'];
            $length     = floatval( $product->get_length() );
            $width      = floatval( $product->get_width() );
            $height     = floatval( $product->get_height() );
            $fee        = 0;

            // Check if all dimensions are valid (greater than zero)
            if ( $length > 0 && $width > 0 && $height > 0 ) {

                if( !empty( $tpsm_dimensions_settings ) ) {
                    foreach ( $tpsm_dimensions_settings as $value) {
                        $tpsm_length    = $value['length'];
                        $tpsm_width     = $value['width'];
                        $tpsm_height    = $value['height'];
    
                        if( $length <= $tpsm_length && $width <= $tpsm_width && $height <= $tpsm_height ) {
                            $fee  = $value['fee'];
                            break;
                        }
                    }
                    $total_fee += $fee * $quantity;
                }
            }
        }
    
        return $total_fee;
    }
}




