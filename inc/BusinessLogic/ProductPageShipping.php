<?php
namespace Themepaste\ShippingManager\BusinessLogic;

use Themepaste\ShippingManager\Admin\Assets;
use Themepaste\ShippingManager\Models\ProductPageShippingSettings;

defined( 'ABSPATH' ) || exit;

/**
 * Frontend logic for product page shipping
 *
 * @since TSM_SINCE
 */
class ProductPageShipping {

    const INSTANCE_KEY = 'business_logic_product_page_shipping';

    public function __construct() {
      $product_page_shipping = new ProductPageShippingSettings();
      if ( tsm_is_checked( $product_page_shipping->fetch()->get( ProductPageShippingSettings::PRODUCT_PAGE_SHIPPING ), false ) ) {
        add_action( 'woocommerce_after_add_to_cart_form', [ $this, 'render_shipping_calculator_form' ] );
        add_action('wp_ajax_calculate_shipping', [ $this, 'handle_calculate_shipping' ] );
        add_action('wp_ajax_nopriv_calculate_shipping', [ $this, 'handle_calculate_shipping' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'add_data_to_script' ] );
      }
    }

    /**
     * Adds data to frontend scripts for product page shipping
     *
     * @since TSM_SINCE
     *
     * @return void
	  */
    public function add_data_to_script() {
//		if (  )
        global $post;
        wp_localize_script( Assets::PRODUCT_PAGE_SHIPPING_SCRIPT, 'tps_manager', [ 'product_id' => $post->ID ] );
    }

    public function render_shipping_calculator_form() {
      if ( ! is_product() ) {
        return;
      }

      // Get the current product's ID
      global $product;
      $product_id = $product->get_id();

      // Create the shipping calculator form
      echo '<div class="shipping-calculator-wrapper">';
      echo '<h2>' . esc_html__('Calculate Shipping', 'woocommerce') . '</h2>';
      echo '<form class="woocommerce-shipping-calculator" action="" method="post">';

      // Country dropdown
      echo '<p class="form-row form-row-wide">';
      echo '<label for="calc_shipping_country">' . esc_html__('Country', 'woocommerce') . '</label>';
      echo '<select name="calc_shipping_country" id="calc_shipping_country">';
      echo '<option value="">' . esc_html__('Select a country...', 'woocommerce') . '</option>';
      foreach (WC()->countries->get_shipping_countries() as $key => $value) {
        echo '<option value="' . esc_attr($key) . '">' . esc_html($value) . '</option>';
      }
      echo '</select>';
      echo '</p>';

      // Postcode input
      echo '<p class="form-row form-row-wide">';
      echo '<label for="calc_shipping_postcode">' . esc_html__('Postcode / ZIP', 'woocommerce') . '</label>';
      echo '<input type="text" name="calc_shipping_postcode" id="calc_shipping_postcode" />';
      echo '</p>';

      // Calculate button
      echo '<button type="submit" name="calc_shipping" value="1" class="button">' . esc_html__('Calculate Shipping', 'woocommerce') . '</button>';
      echo '</form>';
      echo '</div>';
      echo '<div class="tsm-shipping-result"></div>';
    }

    public function handle_calculate_shipping() {
      $country = isset($_POST['country']) ? sanitize_text_field($_POST['country']) : '';
      $postcode = isset($_POST['postcode']) ? sanitize_text_field($_POST['postcode']) : '';
      $product_id = isset($_POST['product_id']) ? sanitize_text_field($_POST['product_id']) : '';

      if (empty($country) || empty($postcode)) {
        echo esc_html__('Please enter both a country and a postcode.', 'woocommerce');
        wp_die();
      }

      // Calculate shipping based on the given country, postcode, and product
      $package = array(
        'destination' => array(
          'country'  => $country,
          'state' => 'CA',
          'postcode' => $postcode,
        ),
        'contents' => array(
          array(
            'product_id' => $product_id,
            'quantity'   => 1,
            'data' => wc_get_product( $product_id )
          ),
        ),
      );

      $shipping_methods = WC()->shipping->calculate_shipping_for_package($package);
	  if ( ! empty( $shipping_methods ) ) {
		  $shipping_rates = [];
		  foreach ( $shipping_methods[ 'rates' ] as $rate ) {
			  $shipping_rates[] = [
				  'label' => esc_html( $rate->label ),
				  'cost' => esc_html( $rate->cost ),
			  ];
		  }
		  wp_send_json_success( [ 'shipping_rates' => $shipping_rates ] );
	  } else {
		  wp_send_json_error( [ 'message' => esc_html__('No shipping methods found.', 'tps-manager') ] );
	  }
      wp_die();
    }

}
