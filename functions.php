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
}

function woocommerce_product_custom_fields() {
	echo '<div class="options_group">';
	echo 'hello field';
	echo '</div>';
}
