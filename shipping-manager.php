<?php
/**
 * Plugin Name: Shipping Manager
 * Description: A simplified all in one shipping solution for WooCommerce.
 * Version: 1.00.0
 * Requires Plugins: woocommerce
 *
 * Requires PHP: 7.2
 * Text Domain: tsm-shipping-manager
 */

defined('ABSPATH') || exit; // Security check

defined( 'TSM_ROOT_FILE_PATH' ) || define( 'TSM_ROOT_FILE_PATH', __DIR__ );
defined( 'TSM_ROOT_FOLDER_URL' ) || define( 'TSM_ROOT_FOLDER_URL', plugin_dir_url( __FILE__ ) );

require_once "vendor/autoload.php";

\Themepaste\ShippingManager\ShippingManager::init();

if (!function_exists('tsm_custom_rule')) {
    // Register Custom Post Type
    function tsm_custom_rule()
    {
        $labels = array(
            'name'                  => _x('TSM Rules', 'Post Type General Name', 'tsm-shipping-manager'),
            'singular_name'         => _x('tsm_rule', 'Post Type Singular Name', 'tsm-shipping-manager'),
            'menu_name'             => __('TSM Rules', 'tsm-shipping-manager'),
            'name_admin_bar'        => __('Post Type', 'tsm-shipping-manager'),
            'archives'              => __('Item Archives', 'tsm-shipping-manager'),
            'attributes'            => __('Item Attributes', 'tsm-shipping-manager'),
            'parent_item_colon'     => __('Parent Item:', 'tsm-shipping-manager'),
            'all_items'             => __('All Items', 'tsm-shipping-manager'),
            'add_new_item'          => __('Add New Item', 'tsm-shipping-manager'),
            'add_new'               => __('Add New', 'tsm-shipping-manager'),
            'new_item'              => __('New Item', 'tsm-shipping-manager'),
            'edit_item'             => __('Edit Item', 'tsm-shipping-manager'),
            'update_item'           => __('Update Item', 'tsm-shipping-manager'),
            'view_item'             => __('View Item', 'tsm-shipping-manager'),
            'view_items'            => __('View Items', 'tsm-shipping-manager'),
            'search_items'          => __('Search Item', 'tsm-shipping-manager'),
            'not_found'             => __('Not found', 'tsm-shipping-manager'),
            'not_found_in_trash'    => __('Not found in Trash', 'tsm-shipping-manager'),
            'insert_into_item'      => __('Insert into item', 'tsm-shipping-manager'),
            'uploaded_to_this_item' => __('Uploaded to this item', 'tsm-shipping-manager'),
            'items_list'            => __('Items list', 'tsm-shipping-manager'),
            'items_list_navigation' => __('Items list navigation', 'tsm-shipping-manager'),
            'filter_items_list'     => __('Filter items list', 'tsm-shipping-manager'),
        );
        $args = array(
            'label'                 => __('TSM Rule Title', 'tsm-shipping-manager'),
            'description'           => __('TSM Rule Description', 'tsm-shipping-manager'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'comments', 'revisions'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'page',
        );
        register_post_type('tsm_rule', $args);
    }
    add_action('init', 'tsm_custom_rule', 0);
}