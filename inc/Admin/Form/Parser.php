<?php
namespace Themepaste\ShippingManager\Admin\Form;

defined( 'ABSPATH' ) || exit;

/**
 * Parses $_POST data
 *
 * @since TSM_SINCE
 */
trait Parser {
	/**
	 * Parses $_POST data according to allowed fields
	 *
	 * @since TSM_SINCE
	 *
	 * @param array $allowed_fields List of allowed fields
	 *
	 * @return array
	 */
	protected function parse_post_data( array $allowed_fields ): array {
		$fields = array_intersect( $allowed_fields, $_POST );
		$data = [];
		foreach ( $fields as $key ) {
			$data[ $key ] = sanitize_text_field( wp_unslash( $_POST[ $key ] ) );
		}
		return $data;
	}

}