<?php
namespace Themepaste\ShippingManager;

defined( 'ABSPATH' ) || exit;

class AddShippingSettingsPage {

	const INSTANCE_KEY = 'AddShippingSettingsPage';
	const SETTINGS_PAGE_KEY = 'tsm_shipping_settings';
	const BACK_PAGE_URL = '';

	/**
	 * Initializing necessary hooks
	 *
	 * @since TSM
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( 'woocommerce_get_sections_shipping', [ $this, 'add_shipping_settings_menu' ] );
		add_filter( 'woocommerce_settings_shipping', [ $this, 'validate_tsm_shipping_settings' ], 21 );
	}

	/**
	 * @param array $sections
	 *
	 * @return void
	 */
	public function add_shipping_settings_menu( array $sections ): array {
		$sections[ self::SETTINGS_PAGE_KEY ] = __( 'TSM Settings', 'tsm-shipping-manager' );
		return $sections;
	}

	/**
	 * Validates if it is the correct section
	 *
	 * @since TSM_SINCE
	 *
	 * @return void
	 */
	public function validate_tsm_shipping_settings( ) {
		if ( isset ( $_GET[ 'section' ] ) ) {
			$section_name = sanitize_text_field( $_GET[ 'section' ] );
			if ( self::SETTINGS_PAGE_KEY == $section_name ) {
				$this->render_page();
			}
		}
	}

	/**
	 * Renders the content of the page
	 *
	 * @since TSM_SINCE
	 *
	 * @return void
	 */
	public function render_page() {
		wp_enqueue_script( Assets::ADMIN_SHIPPING_SETTINGS_SCRIPT );
		( TemplateLoader::init() )->load( 'settings-layout.php' );
	}

}