<?php
/**
 * Shortcut functions
 *
 * @since 1.1.0
 */

defined( 'ABSPATH' ) || exit;

use Themepaste\ShippingManager\{
	ShippingManager,
	Admin\Routes,
	Admin\Template,
	Admin\Controller,
	Admin\Messages,
	Admin\Form\Authentication as FormAuthentication,
	Constants
};
use Themepaste\ShippingManager\Admin\Form\PerProductShipping;
use Themepaste\ShippingManager\Models\FreeShippingSettings;
use Themepaste\ShippingManager\Models\PerProductShippingSettings;

/**
 * Shortcut function get pagename from route name
 *
 * @since 1.1.0
 *
 * @param string $route_name
 *
 * @return string
 */
function tps_manager_get_page( string $route_name ): string {
	$all_routes = ShippingManager::get_instance( Routes::INSTANCE_KEY )->get_all_routes();
	return $all_routes[ $route_name ][ 'tsm-page' ] ?? '';
}

/**
 * Shortcut for loading template
 *
 * @since 1.1.0
 *
 * @param string $template
 * @param array  $args
 *
 * @return bool
 */
function tps_manager_template( string $template, array $args = [] ): bool {
	return ( ShippingManager::get_instance( Template::INSTANCE_KEY ) )->load_template( $template, $args );
}

function tps_manager_template_parts( string $template ): bool {
	return ( ShippingManager::get_instance( Template::INSTANCE_KEY ) )->load_template_parts( $template );
}

/**
 * Shortcut for checking if current page is inside shipping manager admin dashboard
 *
 * @since 1.1.0
 *
 * @return bool
 */
function tps_manager_is_admin_dashboard(): bool {
	return (ShippingManager::get_instance( Controller::INSTANCE_KEY ) )->is_admin_dashboard();
}

/**
 * Shortcut for getting current admin settings page
 *
 * @since 1.1.0
 *
 * @retun string
 */
function tps_manager_current_admin_settings_page(): string {
	return (ShippingManager::get_instance( Controller::INSTANCE_KEY ) )->current_page();
}

/**
 * Shortcut to get url from route name
 *
 * @since 1.1.0
 *
 * @param string $route_name
 *
 * @return string
 */
function tps_manager_url( string $route_name ): string {
	return (ShippingManager::get_instance( Routes::INSTANCE_KEY ) )->get_url( $route_name );
}

/**
 * Check if it is current route the prints provided class name and returns true
 *
 * @since 1.1.0
 *
 * @param string $route_name
 * @param string $class_name
 *
 * @return bool
 */
function tps_manager_is_active_menu( string $route_name, string $class_name = '' ): bool {
	if ( ( ShippingManager::get_instance( Controller::INSTANCE_KEY ) )->is_current_page( $route_name ) ) {
		if ( ! empty( $class_name ) ) {
			echo esc_attr( $class_name );
		}
		return true;
	} else {
		return false;
	}
}

/**
 * Shortcut to get nonce field for form
 *
 * @since 1.1.0
 *
 * @return void
 */
function tps_manager_admin_nonce_field() {
	( new FormAuthentication() )
		->nonce_field(
			tps_manager_current_admin_settings_page()
		);
}

/**
 * Shortcut to add admin message
 *
 * @since 1.1.0
 *
 * @param string $message
 * @param string $type
 *
 * @return void
 */
function tps_manager_admin_message( string $message, string $type = '' ) {
	( ShippingManager::get_instance( Messages::INSTANCE_KEY ) )
		->add_message( $message, $type );
}

/**
 * Shortcut for placing checked value
 *
 * @since 1.1.0
 *
 * @param string $value
 * @param bool   $print
 * @param string $compare
 *
 * @return bool
 */
function tps_manager_is_checked( string $value, bool $print = true, string $compare = '' ) {
	$status = false;
	$compare = $compare === '' ? Constants::YES : $compare;
	if ( $compare === $value ) {
		$status = true;
	}
	if ( $status && $print ) {
		echo "checked";
	}
	return $status;
}

/**
 * Checks if current page is single product page
 *
 * @since 1.2.1
 *
 * @return bool
 */
function tps_manager_is_single_product_page(): bool {
	return function_exists( 'is_product' ) && is_product();
}

/**
 * Shipping bar functionality
 * 
 * @since 1.2.1
 */
$shipping_fees = new FreeShippingSettings();
$free_shipping_bar = $shipping_fees->fetch()->get( FreeShippingSettings::FREE_SHIPPING_BAR );
if ( 'yes' === $free_shipping_bar ) {
    // add_filter( 'render_block', 'tps_woocommerce_cart_block_do_actions', 9, 2 );

    // add_action( 'tps_after_woocommerce/cart-order-summary-subtotal-block', 'tps_shipping_bar', 9 );

	// add_action( 'woocommerce_before_cart', 'tps_free_bar_update' );
}

function tps_woocommerce_cart_block_do_actions( $block_content, $block ) {
	$blocks = array(
		'woocommerce/cart-order-summary-subtotal-block',
	);

	if ( in_array( $block['blockName'], $blocks ) ) {
		ob_start();
		do_action( 'tps_before_' . $block['blockName'] );
		echo $block_content;
		do_action( 'tps_after_' . $block['blockName'] );
		$block_content = ob_get_contents();
		ob_end_clean();
	}
	return $block_content;
}

function tps_shipping_bar() {
	if ( is_admin() ) return;
	$cart_total = WC()->cart->get_subtotal();
    $cart_remaining = 100 - $cart_total;
    if ($cart_total < 100 ) {
        echo '<div style="display: flex; align-items: center;">';
        echo '<span style="margin-right: 10px;">' . get_woocommerce_currency_symbol() . '0</span>';
        echo '<progress id="freeshippingprogress" max="100" value="'.$cart_total.'"></progress>';
        echo '<span style="margin-left: 10px;">' . get_woocommerce_currency_symbol() . '100</span>';
        echo '</div>';
        echo '<span style="color:blue;">You\'re ' . get_woocommerce_currency_symbol() . $cart_remaining . ' away from free shipping!</span>';
    } else {
        echo '<span style="color:blue;">You\'ve unlocked free shipping!</span>';
    }
}

function tps_free_bar_update() {
	?>
	<script>
			jQuery(document.body).on( 'click', '.wc-block-components-quantity-selector__button', function(e) {
			// 	e.preventDefault();
			<?php 
			$cart_total = WC()->cart->get_subtotal();
			?>
				// var subtotal = jQuery( '.wp-block-woocommerce-cart-order-summary-totals-block .wp-block-woocommerce-cart-order-summary-subtotal-block .wc-block-components-totals-item .wc-block-components-totals-item__value').text()
				console.log(<?php echo $cart_total; ?>);
				// jQuery('#tps-shipping-bar').html()
			})
	</script>
	<?php

	// Show 'Spend another X amount' on cart page.
add_filter( 'woocommerce_cart_totals_before_shipping', 'ts_cart_page_progress_bar', 10 );
function ts_cart_page_progress_bar() {
    $cart_total = WC()->cart->get_subtotal();
    $cart_remaining = 100 - $cart_total;
    if ($cart_total < 100 ) {
        echo '<div style="display: flex; align-items: center;">';
        echo '<span style="margin-right: 10px;">' . get_woocommerce_currency_symbol() . '0</span>';
        echo '<progress id="freeshippingprogress" max="100" value="'.$cart_total.'"></progress>';
        echo '<span style="margin-left: 10px;">' . get_woocommerce_currency_symbol() . '100</span>';
        echo '</div>';
        echo '<span style="color:blue;">You\'re ' . get_woocommerce_currency_symbol() . $cart_remaining . ' away from free shipping!</span>';
    } else {
        echo '<span style="color:blue;">You\'ve unlocked free shipping!</span>';
    }
};

}

/**
 * Per product shipping
 * 
 * @since 1.2.1
 */
$shipping_per_product = new PerProductShippingSettings();
$enable_per_product = $shipping_per_product->fetch()->get( PerProductShippingSettings::PER_PRODUCT_SHIPPING );

if ( 'yes' === $enable_per_product ) {
	add_action('woocommerce_product_options_shipping_product_data', 'woocommerce_product_custom_fields');
	add_action( 'admin_enqueue_scripts', 'add_tsm_admin_scripts', 10, 1 );
}

// Displaying quantity setting fields on admin product pages
function woocommerce_product_custom_fields() {
    global $product_object;

    $values = $product_object->get_meta('_tsm_enable_per_product');

    woocommerce_wp_checkbox( array( // Checkbox.
        'id'            => '_tsm_enable_per_product',
        'label'         => '',
        'value'         => empty($values) ? 'yes' : $values,
        'description'   => __( 'Enable per product shipping', 'woocommerce' ),
    ) );

	echo '<div class="tsm_table_per_product">';
	?>
	
	<table>
	<tr class="empty-row" style="display: none;">
		<td></td>
		<td><input type="text"></td>
		<td><input type="text"></td>
		<td><input type="text"></td>
		<td><input type="text"></td>
		<td><input type="text"></td>
		<td><input type="text"></td>
		<td><input type="text"></td>
	</tr>
		<tr class="header-row">
			<th></th>
			<th><?php echo __( 'Country Code[?]', 'tps-manager' ); ?></th>
			<th><?php echo __( 'State Code[?]', 'tps-manager' ); ?></th>
			<th><?php echo __( 'City Name[?]', 'tps-manager' ); ?></th>
			<th><?php echo __( 'District Name[?]', 'tps-manager' ); ?></th>
			<th><?php echo __( 'Zip Code[?]', 'tps-manager' ); ?></th>
			<th><?php echo __( 'Line Cost (Excl. Tax)[?]', 'tps-manager' ); ?></th>
			<th><?php echo __( 'Item Cost (Excl. Tax)[?]', 'tps-manager' ); ?></th>
		</tr>
		<?php
		$product_shipping_rates = maybe_unserialize( $product_object->get_meta('_tsm_per_product_shipping_rates') );
		foreach( $product_shipping_rates as $key => $product_shipping_rate ) {
			?>
			<tr>
				<td></td>
				<td><input type="text" name="product_shipping_rates[<?php echo esc_attr( $key ); ?>][]" value="<?php echo isset( $product_shipping_rate[0] ) ? $product_shipping_rate[0] : ''; ?>"></td>
				<td><input type="text" name="product_shipping_rates[<?php echo esc_attr( $key ); ?>][]" value="<?php echo isset( $product_shipping_rate[1] ) ? $product_shipping_rate[1] : ''; ?>"></td>
				<td><input type="text" name="product_shipping_rates[<?php echo esc_attr( $key ); ?>][]" value="<?php echo isset( $product_shipping_rate[2] ) ? $product_shipping_rate[2] : ''; ?>"></td>
				<td><input type="text" name="product_shipping_rates[<?php echo esc_attr( $key ); ?>][]" value="<?php echo isset( $product_shipping_rate[3] ) ? $product_shipping_rate[3] : ''; ?>"></td>
				<td><input type="text" name="product_shipping_rates[<?php echo esc_attr( $key ); ?>][]" value="<?php echo isset( $product_shipping_rate[4] ) ? $product_shipping_rate[4] : ''; ?>"></td>
				<td><input type="text" name="product_shipping_rates[<?php echo esc_attr( $key ); ?>][]" value="<?php echo isset( $product_shipping_rate[5] ) ? $product_shipping_rate[5] : ''; ?>"></td>
				<td><input type="text" name="product_shipping_rates[<?php echo esc_attr( $key ); ?>][]" value="<?php echo isset( $product_shipping_rate[6] ) ? $product_shipping_rate[6] : ''; ?>"></td>
			</tr>
			<?php
		}
		?>
	</table>
	<button class="button button-primary add-row-button"><?php echo __('Add row', 'tps_manager'); ?></button>
	<button class="button button-danger remove-row-button"><?php echo __('Delete row', 'tps_manager'); ?></button>
	<?php
	echo '</div>';
}

// Save quantity setting fields values
add_action( 'woocommerce_admin_process_product_object', 'save_custom_field_product_options_pricing' );
function save_custom_field_product_options_pricing( $product ) {
    $product->update_meta_data( '_tsm_enable_per_product', isset($_POST['_tsm_enable_per_product']) ? 'yes' : 'no' );

	$product_shipping_rates = [];
	if ( isset( $_POST['product_shipping_rates'] ) ) {
		$product_shipping_rates = $_POST['product_shipping_rates'];
	}
	$product->update_meta_data( '_tsm_per_product_shipping_rates', $product_shipping_rates );
}

function add_tsm_admin_scripts( $hook ) {

    global $post;

    if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
        if ( 'product' === $post->post_type ) {     
            wp_enqueue_script(  'tsm-per-product-script', plugin_dir_url( __FILE__ ). 'assets/build/admin/js/per-product-shipping.js', array( 'jquery' ) );
        }
    }
}

add_filter( 'woocommerce_package_rates', 'woocommerce_per_product_shipping_rates' );

function woocommerce_per_product_shipping_rates( $rates ) {

	global $woocommerce;
	$country = $woocommerce->customer->get_shipping_country();
	$city = $woocommerce->customer->get_shipping_city();
	$postcode = $woocommerce->customer->get_shipping_postcode();
	$cart = $woocommerce->cart->get_cart();
	$cart_quantity = $woocommerce->cart->get_cart_contents_count();
	static $cart_cost = 0;
	static $per_product_cost = 0;
	
	static $number = 0;

	
foreach( $cart as $cart_item_key => $cart_item ) {
	
		if ( $number >= $cart_quantity ) {
			break;
		}
		$product_id = $cart_item['product_id'];
		$is_virtual = get_post_meta( $product_id, '_virtual', true );

		if ( 'yes' == $is_virtual ) {
			$cart_quantity = $cart_quantity - $cart_item['quantity'];
		}
		
		$product = wc_get_product( $product_id );
		$enable_per_product = $product->get_meta('_tsm_enable_per_product');

		if ( 'yes' === $enable_per_product ) {
			$cost = 0;
			$per_product_shipping_rates = $product->get_meta('_tsm_per_product_shipping_rates');

			foreach( $per_product_shipping_rates as $per_product_shipping_rate ) {

				$shipping_rate_country = isset( $per_product_shipping_rate[0] ) ? $per_product_shipping_rate[0] : '';

				if ( $country == $shipping_rate_country ) {

					$cost = ( isset($per_product_shipping_rate[5]) ? (int)$per_product_shipping_rate[5] : 0 ) + ( isset( $per_product_shipping_rate[6] ) ? (int)$per_product_shipping_rate[6] : 0 );
					
					foreach ( $rates as $rate_id => $rate ) {
						$quantity = $cart_item['quantity'];
						$per_product_cost = ($cost * $quantity);
					}
				}
			}
		} else {
			
			foreach ( $rates as $rate_id => $rate ) {
				$quantity = $cart_item['quantity'];
				$per_product_shipping = ($rate->cost / $cart_quantity );
				$cart_cost = $per_product_shipping * $quantity;
			}
		}
		$number++;
	}
	if ( 'tsm-shipping-manager-shipping-method' === $rate->method_id ) {
		foreach ( $rates as $rate_id => $rate ) {
			$rate->cost = $cart_cost + $per_product_cost;
		}
	}
	return $rates;
}