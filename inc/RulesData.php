<?php
namespace Themepaste\ShippingManager;

/**
 * Handles rules data for `tsm_custom_rule` custom post cpt
 *
 * @since TSM_SINCE
 */
class RulesData {

	/**
	 * Custom post type ID
	 *
	 * @since TSM_SINCE
	 */
	const POST_TYPE = 'tsm_custom_rule';

	/**
	 * Get conditional rules list using `get_posts` WordPress function
	 *
	 * @since TSM_SINCE
	 *
	 * @param $args
	 *
	 * @return array
	 */
	public function get_rules( $args = [] ): array {
		$args = array_merge(
			[
				'post_per_page' => -1,
			],
			$args
		);

		$args['post_type'] = self::POST_TYPE; // Post type can not be changed by the user

		$rules = get_posts( $args );

		return $rules;
	}

}