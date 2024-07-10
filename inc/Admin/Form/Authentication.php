<?php
namespace Themepaste\ShippingManager\Admin\Form;

defined( 'ABSPATH' ) || exit;

/**
 * Authentication and authorization check
 *
 * @since
 */
class Authentication {

	/**
	 * Prints nonce filed, should be inside form
	 *
	 * @since TSM_SINCE
	 *
	 * @param string $handle
	 *
	 * @return void
	 */
	public function nonce_field( string $handle ) {
		wp_nonce_field( $handle, $handle );
	}

	/**
	 * Verifies nonce filed for form submission
	 *
	 * @since TSM_SINCE
	 *
	 * @param string $handle
	 *
	 * @return bool
	 */
	public function is_valid_nonce( string $handle): bool {
		return wp_verify_nonce( ! empty( $_POST[ $handle ] ) ? sanitize_text_field( $_POST[ $handle ] ): '' , $handle);
	}

	/**
	 * Checks if current user is authenticated and nonce is validated
	 *
	 * @since TSM_SINCE
	 *
	 * @param string $nonce_handle
	 *
	 * @return bool
	 */
	public function is_authenticated( string $nonce_handle ): bool {
		return is_user_logged_in() && $this->is_valid_nonce( $nonce_handle ) && current_user_can( 'manage_options' );
	}
}
