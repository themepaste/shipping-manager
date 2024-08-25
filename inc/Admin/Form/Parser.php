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
		$data = [];
		foreach( $allowed_fields as $valid_field_key ) {
			if ( ! empty( $_POST[ $valid_field_key ] ) ) {
				$data[ $valid_field_key ] = sanitize_text_field( wp_unslash( $_POST[ $valid_field_key ] ) );
			}
		}
		return $data;
	}

}