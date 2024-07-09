<?php
/**
 * Shortcut functions
 *
 * @since TSM_SINCE
 */

defined( 'ABSPATH' ) || exit;

use Themepaste\ShippingManager\{
	ShippingManager,
	Admin\Routes,
	Admin\Template,
	Admin\Controller
};


/**
 * Shortcut function get pagename from route name
 *
 * @since TSM_SINCE
 *
 * @param string $route_name
 *
 * @return string
 */
function get_page( string $route_name ): string {
	$all_routes = ShippingManager::get_instance( Routes::INSTANCE_KEY )->get_all_routes();
	return $all_routes[ $route_name ][ 'tsm-page' ] ?? '';
}

/**
 * Shortcut for loading template
 *
 * @since TSM_SINCE
 *
 * @param string $template
 * @param array  $args
 *
 * @return bool
 */
function tsm_template( string $template, array $args = [] ): bool {
	return ( ShippingManager::get_instance( Template::INSTANCE_KEY ) )->load_template( $template, $args );
}

function tsm_template_parts( string $template ): bool {
	return ( ShippingManager::get_instance( Template::INSTANCE_KEY ) )->load_template_parts( $template );
}

/**
 * Shortcut for checking if current page is inside shipping manager admin dashboard
 *
 * @since TSM_SINCE
 *
 * @return bool
 */
function tsm_is_admin_dashboard(): bool {
	return (ShippingManager::get_instance( Controller::INSTANCE_KEY ) )->is_admin_dashboard();
}

/**
 * Shortcut for getting current admin settings page
 *
 * @since TSM_SINCE
 *
 * @retun string
 */
function tsm_current_admin_settings_page(): string {
	return (ShippingManager::get_instance( Controller::INSTANCE_KEY ) )->current_page();
}

/**
 * Shortcut to get url from route name
 *
 * @since TSM_SINCE
 *
 * @param string $route_name
 *
 * @return string
 */
function tsm_url( string $route_name ): string {
	return (ShippingManager::get_instance( Routes::INSTANCE_KEY ) )->get_url( $route_name );
}

/**
 * Check if it is current route the prints provided class name and returns true
 *
 * @since TSM_SINCE
 *
 * @param string $route_name
 * @param string $class_name
 *
 * @return bool
 */
function tsm_is_active_menu( string $route_name, string $class_name = '' ): bool {
	if ( ( ShippingManager::get_instance( Controller::INSTANCE_KEY ) )->is_current_page( $route_name ) ) {
		if ( ! empty( $class_name ) ) {
			echo esc_attr( $class_name );
		}
		return true;
	} else {
		return false;
	}
}
