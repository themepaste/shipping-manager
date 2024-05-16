<?php
namespace Themepaste\ShippingManager;

class RulesData {

	const POST_TYPE = 'tsm_custom_rule';

	public function get_rules( $args = [] ): array {
		$rules = get_posts([
			'posts_per_page'   => -1,
			'post_type'       => self::POST_TYPE,
		]);

		return $rules;
	}

}