<?php
namespace Themepaste\ShippingManager\Admin\Form;

defined( 'ABSPATH' ) || exit;

/**
 * Imposes process interface
 *
 * @since 1.1.0
 */
interface Process {
	public function process();
}
