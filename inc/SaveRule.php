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
    const ADMIN_SHIPPING_TSM_RULE_NONCE = 'tsm_shipping_rule';

	/**
	 * Initializes the object
	 *
	 * @since TSM_SINCE
	 */
	public function __construct() {
        add_action( 'admin_init', [ $this, 'process_form' ] );
	}

	/**
	 * Registers all admin scripts
	 *
	 * @since TSM_SINCE
	 */


     public function process_form() {
        
        if (isset($_POST['tsm_rules_form'])) {

            // Check Nonce
            if ( ! wp_verify_nonce( $_POST['_wpnonce'], SaveRule::ADMIN_SHIPPING_TSM_RULE_NONCE ) ) {
                wp_die( 'Are you cheating?' );
            } 

            
             // Check Required Files
            if ( !empty($_POST['rule_title'])) {
                $rule_title = sanitize_text_field($_POST['rule_title']);
            }else{
                wp_die( 'Rule Title is required' );
                $rule_title = null;
            }

            if ( !empty($_POST['is_active'])) {
                $is_active = sanitize_text_field($_POST['is_active']);
            }else{
                wp_die( 'Status is required' );
                $is_active = null;
            }
            

            // Save the data to the database
            $this->save_data($rule_title,$is_active);


             // Redirect user after form submission
             wp_redirect(home_url('/wp-admin/admin.php?page=wc-settings&tab=shipping&section=tsm_shipping_settings'));
             exit();
        }
    }

    private function save_data($rule_title,$is_active) {
        // Create a new post (or update an existing one)
        $post_args = array(
            'post_title'    => 'TSM Rule', // You can change this to a more descriptive title
            'post_content'  => '', // You can add content here if needed
            'post_status'   => 'publish',
            'post_type'     => 'tsm_custom_rule' // Change this to your desired post type
        );

        $post_id = wp_insert_post($post_args);

        // Save form data as post meta
        update_post_meta($post_id, 'rule_title', $rule_title);
        update_post_meta($post_id, 'is_active', $is_active);

        // Optionally, you can do additional processing or save the data in custom tables
    }


}
    
new SaveRule();


