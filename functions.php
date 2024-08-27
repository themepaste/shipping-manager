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