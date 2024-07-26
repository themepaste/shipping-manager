<?php
namespace Themepaste\ShippingManager\Admin\Form;

defined( 'ABSPATH' ) || exit;

/**
 * Parses $_POST data
 *
 * @since 1.1.0
 */
trait Parser {
	/**
	 * Parses $_POST data according to allowed fields
	 *
	 * @since 1.1.0
	 *
	 * @param array $allowed_fields List of allowed fields
	 *
	 * @return array
	 */
	protected function parse_post_data( array $allowed_fields ): array {
		$fields = array_intersect( $allowed_fields, array_keys( $_POST ) ); // Nonce already verified
		$data = [];
		foreach ( $fields as $key ) {
			$data[ $key ] = sanitize_text_field( wp_unslash( $_POST[ $key ] ) ); // Nonce already verified
		}
		return $data;
	}

}