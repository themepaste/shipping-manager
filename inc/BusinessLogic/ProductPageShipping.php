<?php
namespace Themepaste\ShippingManager\BusinessLogic;

use Themepaste\ShippingManager\Admin\Assets;
use Themepaste\ShippingManager\Models\ProductPageShippingSettings;

defined( 'ABSPATH' ) || exit;

/**
 * Frontend logic for product page shipping
 *
 * @since 1.1.1
 */
class ProductPageShipping {
	/**
	 * Instance key to include
	 */
    const INSTANCE_KEY = 'business_logic_product_page_shipping';

	const AJAX_NONCE_HANDLE_RATES = 'tps-manager-shipping-rates-nonce';

    public function __construct() {
      $product_page_shipping = new ProductPageShippingSettings();
      if ( tps_manager_is_checked( $product_page_shipping->fetch()->get( ProductPageShippingSettings::PRODUCT_PAGE_SHIPPING ), false ) ) {
        add_action( 'woocommerce_after_add_to_cart_form', [ $this, 'render_shipping_calculator_form' ] );
        add_action('wp_ajax_calculate_shipping', [ $this, 'handle_calculate_shipping' ] );
        add_action('wp_ajax_nopriv_calculate_shipping', [ $this, 'handle_calculate_shipping' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'add_data_to_script' ] );
      }
    }

    /**
     * Adds data to frontend scripts for product page shipping
     *
     * @since 1.1.1
     *
     * @return void
	  */
    public function add_data_to_script() {
        global $post;
        wp_localize_script(
			Assets::PRODUCT_PAGE_SHIPPING_SCRIPT,
			'tps_manager',
			[
				'rates_nonce' => wp_create_nonce( self::AJAX_NONCE_HANDLE_RATES ),
				'product_id' => $post->ID
			]
		);
    }

	/**
	 * Renders shipping calculator form on single product page
	 *
	 * @since 1.1.1
	 *
	 * @return void
	 */
    public function render_shipping_calculator_form() {
	  if ( ! is_product() ) {
		return;
	  }
	  tps_manager_template( 'frontend/product-page-shipping/customer-address' );
    }

	/**
	 * Calculate shipping fees and return response
	 *
	 * @since 1.1.1
	 *
	 * @return void
	 */
    public function handle_calculate_shipping() {
		if ( ! check_ajax_referer( self::AJAX_NONCE_HANDLE_RATES, 'rates_nonce', false ) ) {
			wp_send_json_error( [
				'message' => esc_html__( 'Nonce not valid', 'tps-manager' )
			] );
			wp_die();
		}
      $country = isset($_POST['country']) ? sanitize_text_field($_POST['country']) : '';
      $postcode = isset($_POST['postcode']) ? sanitize_text_field($_POST['postcode']) : '';
      $product_id = isset($_POST['product_id']) ? sanitize_text_field($_POST['product_id']) : '';

      if (empty($country) || empty($postcode)) {
		  wp_send_json_error(
			[ 'message' => esc_html__( 'Please enter both a country and a postcode.', 'tps-manager' ) ]
		  );
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
