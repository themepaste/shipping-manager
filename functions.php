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
	Admin\Routes
};


/**
 * Fetches the actual url string against the name of the string
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
