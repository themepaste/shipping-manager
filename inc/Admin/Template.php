<?php
namespace Themepaste\ShippingManager\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Manages template files loading and passed data
 *
 * @since TSM_SINCE
 */
class Template {
	/**
	 * Unique id for initialized object
	 *
	 * @since TSM_SINCE
	 */
	const INSTANCE_KEY = 'admin_template';

	/**
	 * Template root directory constant
	 *
	 * @since TSM_SINCE
	 */
	const TEMPLATE_ROOT_DIR = TSM_PLUGIN_ROOT_PATH . '/templates/';

	/**
	 * Variable root template directory
	 *
	 * @since TSM_SINCE
	 *
	 * @var string
	 */
	private string $template_dir = '';

	private array $pages = [
		'free-shipping' => 'free-shipping', // first page is default page
	];

	public function __construct() {
		/**
		 * @filter `tsm_template_root_dir` filter to manipulate root directory for template paths
		 *
		 * @since TSM_SINCE
		 *
		 * @param string
		 *
		 * @retun string
		 */
		$this->template_dir = apply_filters( 'tsm_template_root_dir', self::TEMPLATE_ROOT_DIR );
	}

	/**
	 * Validates arguments if page is added in the argument.
	 *
	 * @since TSM_SINCE
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	private function validate_page( array $args ): array {
		if ( isset( $args['page'] ) ) {
			if ( ! in_array( $args['page'], $this->pages, true ) ) {
				$passed_page = $args['page'];
				wp_trigger_error( __METHOD__, "$passed_page is not a valid admin page." );
				$args['page'] = current( $this->pages ); // setting default page
			}
		} else {
			$args['page'] = current( $this->pages ); // setting default page;
		}
		return $args;
	}

	/**
	 * Loads template and passes arguments to the template files
	 *
	 * @since TSM_SINCE
	 *
	 * @param string $name
	 * @param array $args
	 *
	 * @return bool
	 */
	public function load_template( string $name, array $args = [] ): bool {
		$args = $this->validate_page( $args );
		$template_path = $this->template_dir  . $name . '.php';
		if ( ! file_exists( $template_path ) ) {
			wp_trigger_error( __METHOD__, "`$template_path` file not found." );
			return false;
		}

		/**
		 * @filter `tsm_template_path` filter to manipulate single template path
		 *
		 * @since TSM_SINCE
		 *
		 * @param string
		 *
		 * @retun string
		 */
		$template_path = apply_filters( 'tsm_template_path', $template_path );

		extract( $args );
		include $template_path;
		return true;
	}
}