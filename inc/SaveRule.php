<?php
namespace Themepaste\ShippingManager;

defined( 'ABSPATH' ) || exit;

/**
 * Manages the SaveRule
 *
 * @since TSM_SINCE
 */
class SaveRule {

    const INSTANCE_KEY = 'SaveRule';
    const ADD_RULE_NONCE = 'tsm_shipping_rule';

	/**
	 * Initializes the object
	 *
	 * @since TSM_SINCE
	 */
	public function __construct() {
        add_action( 'admin_init', [ $this, 'process_form' ] );
	}

	/**
	 * This will validate user nonce and permission
	 *
	 * @since TSM_SINCE
	 *
	 * @return bool
	 */
	protected function validate_user(): bool {
		// If nonce is available
		if ( ! isset( $_POST[ self::ADD_RULE_NONCE ] ) ) {
			return false;
		}

		// Is nonce valid
		if (
			! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ self::ADD_RULE_NONCE ] ) ), self::ADD_RULE_NONCE )
		) {
			return false;
		}

		// Is user has permission
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Registers all admin scripts
	 *
	 * @since TSM_SINCE
	 */
     public function process_form() {
		 if ( ! $this->validate_user() ) {
			 return;
		 }

		 $rule_title = ! empty( $_POST['rule_title'] ) ? sanitize_text_field( wp_unslash( $_POST['rule_title'] ) ) : '';
		 $is_active = ! empty( $_POST['is_active'] ) ? 'yes' : 'no';

		 $this->save_data( $rule_title, $is_active );

    }

    private function save_data($rule_title,$is_active) {
        // Create a new post (or update an existing one)
        $post_args = array(
            'post_title'    => 'TSM Rule', // You can change this to a more descriptive title
            'post_content'  => '', // You can add content here if needed
            'post_status'   => 'publish',
            'post_type'     => RulesData::POST_TYPE // Change this to your desired post type
        );

        $post_id = wp_insert_post($post_args);

        // Save form data as post meta
        update_post_meta($post_id, 'rule_title', $rule_title);
        update_post_meta($post_id, 'is_active', $is_active);

        // Optionally, you can do additional processing or save the data in custom tables
    }


}
    
new SaveRule();


