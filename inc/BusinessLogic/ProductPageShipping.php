<?php
namespace Themepaste\ShippingManager\BusinessLogic;

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
		}
	}

	public function render_shipping_calculator_form() {
		if (!is_product()) {
			return;
		}

		// Get the current product's ID
		global $product;
		$product_id = $product->get_id();

		// Create the shipping calculator form
		echo '<div class="shipping-calculator-wrapper">';
		echo '<h2>' . __('Calculate Shipping', 'woocommerce') . '</h2>';
		echo '<form class="woocommerce-shipping-calculator" action="" method="post">';

		// Country dropdown
		echo '<p class="form-row form-row-wide">';
		echo '<label for="calc_shipping_country">' . __('Country', 'woocommerce') . '</label>';
		echo '<select name="calc_shipping_country" id="calc_shipping_country">';
		echo '<option value="">' . __('Select a country...', 'woocommerce') . '</option>';
		foreach (WC()->countries->get_shipping_countries() as $key => $value) {
			echo '<option value="' . esc_attr($key) . '">' . esc_html($value) . '</option>';
		}
		echo '</select>';
		echo '</p>';

		// Postcode input
		echo '<p class="form-row form-row-wide">';
		echo '<label for="calc_shipping_postcode">' . __('Postcode / ZIP', 'woocommerce') . '</label>';
		echo '<input type="text" name="calc_shipping_postcode" id="calc_shipping_postcode" />';
		echo '</p>';

		// Calculate button
		echo '<button type="submit" name="calc_shipping" value="1" class="button">' . __('Calculate Shipping', 'woocommerce') . '</button>';
		echo '</form>';
		echo '</div>';
		echo '<div class="tsm-shipping-result"></div>'

		// Optional: Add custom JavaScript to handle form submission via AJAX, if needed
		// This will require additional code to handle the AJAX request
		?>
		<script>
            jQuery(document).ready(function($) {
                $('.woocommerce-shipping-calculator').on('submit', function(e) {
                    e.preventDefault();

                    var country = $('#calc_shipping_country').val();
                    var postcode = $('#calc_shipping_postcode').val();

                    $.ajax({
                        type: 'POST',
                        url: woocommerce_params.ajax_url,
                        data: {
                            action: 'calculate_shipping',
                            country: country,
                            postcode: postcode,
                            product_id: <?php echo esc_js( $product_id ); ?> // pass the product ID
                        },
                        success: function(response) {
                            // Handle the response (display the shipping rates)
                            $('.tsm-shipping-result').html(response);
                        }
                    });
                });
            });
		</script>
		<?php
	}

	public function handle_calculate_shipping() {
		$country = isset($_POST['country']) ? sanitize_text_field($_POST['country']) : '';
		$postcode = isset($_POST['postcode']) ? sanitize_text_field($_POST['postcode']) : '';
		$product_id = isset($_POST['product_id']) ? sanitize_text_field($_POST['product_id']) : '';

		if (empty($country) || empty($postcode)) {
			echo __('Please enter both a country and a postcode.', 'woocommerce');
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

		// Display the results
		if (!empty($shipping_methods['rates'])) {
			foreach ($shipping_methods['rates'] as $rate) {
				echo '<p>' . esc_html($rate->label) . ': ' . wc_price($rate->cost) . '</p>';
			}
		} else {
			echo __('No shipping options were found.', 'woocommerce');
		}

		wp_die();
	}

}
