<?php
namespace Themepaste\ShippingManager;

defined( 'ABSPATH' ) || exit;

/**
 * Manages the SaveRule
 *
 * @since TSM_SINCE
 */
class QueryRuleData {


	/**
	 * Initializes the object
	 *
	 * @since TSM_SINCE
	 */
	public function __construct() {
        
	}

	/**
	 * Registers all admin scripts
	 *
	 * @since TSM_SINCE
	 */


     public function custom_query_function() {
        // Define your query parameters
        $args = array(
            'post_type'              => array('tsm_custom_rule'),
            'post_status'            => array('publish'),
            'post_per_page'          => 3,
            'meta_key' => 'rule_title', // Meta key for the form field 'name'
        );

        // Instantiate WP_Query
        $query = new \WP_Query( $args );

        // Check if there are any posts
        if ( $query->have_posts() ) {
            // Start the loop
            while ( $query->have_posts() ) {
                $query->the_post();
                $rule_title = get_post_meta(get_the_ID(), 'rule_title', true);

                // Display post content or do something else with the post data

                echo '<div class="tsm-col-3">
                    <div class="tsm-first-box tsm-card">
                        <h2>' .$rule_title.'</h2>
                        <a href="">Edit</a>
                    </div>
                </div>';

            }

            // Restore original post data
            wp_reset_postdata();
        } else {
            // No posts found
            echo '<h2>No data found</h2>.';
        }
    }


}
    


