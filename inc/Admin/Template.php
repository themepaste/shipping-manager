<?php
namespace Themepaste\ShippingManager\Admin;

use Themepaste\ShippingManager\ShippingManager;

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

	/**
	 * A list of pages with routes
	 *
	 * @since TSM_SINCE
	 *
	 * @var array
	 */
	private array $pages = [
		Routes::SHIPPING_FEES => Routes::SHIPPING_FEES, // first page is default page
		Routes::FREE_SHIPPING => Routes::FREE_SHIPPING,
		Routes::PER_PRODUCT_SHIPPING => Routes::PER_PRODUCT_SHIPPING,
		Routes::PRODUCT_PAGE_SHIPPING => Routes::PRODUCT_PAGE_SHIPPING,
		Routes::TRACK_SHIPPING => Routes::TRACK_SHIPPING,
	];

	/**
	 * All the passed arguments will be saved here. This is later used to pass to load template parts
	 *
	 * @since TSM_SINCE
	 *
	 * @var array
	 */
	private array $args = [];

	/**
	 * Initializes:
	 * Template root directory
	 *
	 * @since TSM_SINCE
	 *
	 * @return void
	 */
	public function __construct() {
		$this->template_dir = self::TEMPLATE_ROOT_DIR;
	}

	/**
	 * Returns all pages
	 *
	 * @since TSM_SINCE
	 *
	 * @return array
	 */
	public function get_pages(): array {
		return array_values( $this->pages );
	}

	/**
	 * Returns the default page for admin settings page
	 *
	 * @since TSM_SINCE
	 *
	 * @return string
	 */
	public function get_default_page(): string  {
		return current( $this->get_pages() );
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
		if ( ! empty( $args['page'] ) ) {
			if ( ! in_array( $args['page'], array_keys( $this->pages ), true ) ) {
				$passed_page = $args['page'];
				wp_trigger_error( __METHOD__, "$passed_page is not a valid admin page." );
				$args['page'] = $this->get_default_page(); // setting default page
			}
		} else {
			$args['page'] = $this->get_default_page(); // setting default page;
		}
		return $args;
	}

	/**
	 * Set messages to template args for rendering
	 *
	 * @since TSM_SINCE
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	private function set_messages( array $args ): array {
		$args['messages'] = ( ShippingManager::get_instance( Messages::INSTANCE_KEY ) )->get_sorted_messages_by_type();
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
		$args = $this->set_messages( $args );

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

		$this->args = apply_filters( 'tsm_template_args', $args, $template_path );

		extract( $this->args );
		include $template_path;
		return true;
	}

	/**
	 * Loads templates parts passing arguments from load_template function
	 *
	 * @since TSM_SINCE
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function load_template_parts( string $name ): bool {
		$template_parts_path = $this->template_dir . $name . '.php';
		if ( ! file_exists( $template_parts_path ) ) {
			wp_trigger_error( __METHOD__, "`$template_parts_path` file not found." );
			return false;
		}

		$template_parts_path = apply_filters( 'tsm_template_parts_path', $template_parts_path );
		extract( $this->args );
		include $template_parts_path;
		return true;
	}
}
