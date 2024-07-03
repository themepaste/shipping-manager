<?php
/**
 * Shortcut functions
 *
 * @since TSM_SINCE
 */

// Security check
defined( 'ABSPATH' ) || exit;

use Themepaste\ShippingManager\{
	ShippingManager,
	Admin\Routes,
	Admin\Template
};


/**
 * Shortcut function get route string from name
 *
 * @since TSM_SINCE
 *
 * @param string $name
 *
 * @return string
 */
function tsm_route( string $name ): string {
	return ShippingManager::get_instance( Routes::INSTANCE_KEY )->get_route( $name );
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