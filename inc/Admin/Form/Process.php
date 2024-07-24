<?php
namespace Themepaste\ShippingManager\Admin\Form;

defined( 'ABSPATH' ) || exit;

/**
 * Imposes process interface
 *
 * @since TSM_SINCE
 */
interface Process {
	public function process();
}
