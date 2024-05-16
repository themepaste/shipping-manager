<?php
namespace Themepaste\ShippingManager;

defined( 'ABSPATH' ) || exit;

/**
 * Manages template loading
 *
 * @since TSM_SINCE
 */
class TemplateLoader {
	const TEMPLATE_PATH = TSM_ROOT_FILE_PATH . '/templates/';

	protected static $self;

	protected function __construct() {}

	public static function init() {
		if ( empty( TemplateLoader::$self ) ) {
			TemplateLoader::$self = new TemplateLoader();
		}
		return TemplateLoader::$self;
	}

	/**
	 * Loads the layout
	 *
	 * @since TSM_SINCE
	 *
	 * @param $path
	 *
	 * @return void
	 */
	public function load( $path, $args = [] ) {
		extract( $args );
		include TemplateLoader::TEMPLATE_PATH . $path;
	}

}