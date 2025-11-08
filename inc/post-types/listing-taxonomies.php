<?php
/**
 * Register Listing Taxonomies
 *
 * @package ConnectPro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Listing Category Taxonomy
 */
function connectpro_register_listing_category() {
    $labels = array(
        'name'                       => _x( 'Listing Categories', 'Taxonomy General Name', 'connectpro' ),
        'singular_name'              => _x( 'Listing Category', 'Taxonomy Singular Name', 'connectpro' ),
        'menu_name'                  => __( 'Categories', 'connectpro' ),
        'all_items'                  => __( 'All Categories', 'connectpro' ),
        'parent_item'                => __( 'Parent Category', 'connectpro' ),
        'parent_item_colon'          => __( 'Parent Category:', 'connectpro' ),
        'new_item_name'              => __( 'New Category Name', 'connectpro' ),
        'add_new_item'               => __( 'Add New Category', 'connectpro' ),
        'edit_item'                  => __( 'Edit Category', 'connectpro' ),
        'update_item'                => __( 'Update Category', 'connectpro' ),
        'view_item'                  => __( 'View Category', 'connectpro' ),
        'separate_items_with_commas' => __( 'Separate categories with commas', 'connectpro' ),
        'add_or_remove_items'        => __( 'Add or remove categories', 'connectpro' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'connectpro' ),
        'popular_items'              => __( 'Popular Categories', 'connectpro' ),
        'search_items'               => __( 'Search Categories', 'connectpro' ),
        'not_found'                  => __( 'Not Found', 'connectpro' ),
        'no_terms'                   => __( 'No categories', 'connectpro' ),
        'items_list'                 => __( 'Categories list', 'connectpro' ),
        'items_list_navigation'      => __( 'Categories list navigation', 'connectpro' ),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'rewrite'                    => array( 'slug' => 'listing-category' ),
    );

    register_taxonomy( 'listing_category', array( 'listing' ), $args );
}
add_action( 'init', 'connectpro_register_listing_category', 0 );

/**
 * Register Listing Location Taxonomy
 */
function connectpro_register_listing_location() {
    $labels = array(
        'name'                       => _x( 'Locations', 'Taxonomy General Name', 'connectpro' ),
        'singular_name'              => _x( 'Location', 'Taxonomy Singular Name', 'connectpro' ),
        'menu_name'                  => __( 'Locations', 'connectpro' ),
        'all_items'                  => __( 'All Locations', 'connectpro' ),
        'parent_item'                => __( 'Parent Location', 'connectpro' ),
        'parent_item_colon'          => __( 'Parent Location:', 'connectpro' ),
        'new_item_name'              => __( 'New Location Name', 'connectpro' ),
        'add_new_item'               => __( 'Add New Location', 'connectpro' ),
        'edit_item'                  => __( 'Edit Location', 'connectpro' ),
        'update_item'                => __( 'Update Location', 'connectpro' ),
        'view_item'                  => __( 'View Location', 'connectpro' ),
        'separate_items_with_commas' => __( 'Separate locations with commas', 'connectpro' ),
        'add_or_remove_items'        => __( 'Add or remove locations', 'connectpro' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'connectpro' ),
        'popular_items'              => __( 'Popular Locations', 'connectpro' ),
        'search_items'               => __( 'Search Locations', 'connectpro' ),
        'not_found'                  => __( 'Not Found', 'connectpro' ),
        'no_terms'                   => __( 'No locations', 'connectpro' ),
        'items_list'                 => __( 'Locations list', 'connectpro' ),
        'items_list_navigation'      => __( 'Locations list navigation', 'connectpro' ),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'rewrite'                    => array( 'slug' => 'location' ),
    );

    register_taxonomy( 'listing_location', array( 'listing' ), $args );
}
add_action( 'init', 'connectpro_register_listing_location', 0 );

/**
 * Register Listing Tags Taxonomy
 */
function connectpro_register_listing_tags() {
    $labels = array(
        'name'                       => _x( 'Listing Tags', 'Taxonomy General Name', 'connectpro' ),
        'singular_name'              => _x( 'Listing Tag', 'Taxonomy Singular Name', 'connectpro' ),
        'menu_name'                  => __( 'Tags', 'connectpro' ),
        'all_items'                  => __( 'All Tags', 'connectpro' ),
        'parent_item'                => __( 'Parent Tag', 'connectpro' ),
        'parent_item_colon'          => __( 'Parent Tag:', 'connectpro' ),
        'new_item_name'              => __( 'New Tag Name', 'connectpro' ),
        'add_new_item'               => __( 'Add New Tag', 'connectpro' ),
        'edit_item'                  => __( 'Edit Tag', 'connectpro' ),
        'update_item'                => __( 'Update Tag', 'connectpro' ),
        'view_item'                  => __( 'View Tag', 'connectpro' ),
        'separate_items_with_commas' => __( 'Separate tags with commas', 'connectpro' ),
        'add_or_remove_items'        => __( 'Add or remove tags', 'connectpro' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'connectpro' ),
        'popular_items'              => __( 'Popular Tags', 'connectpro' ),
        'search_items'               => __( 'Search Tags', 'connectpro' ),
        'not_found'                  => __( 'Not Found', 'connectpro' ),
        'no_terms'                   => __( 'No tags', 'connectpro' ),
        'items_list'                 => __( 'Tags list', 'connectpro' ),
        'items_list_navigation'      => __( 'Tags list navigation', 'connectpro' ),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'rewrite'                    => array( 'slug' => 'listing-tag' ),
    );

    register_taxonomy( 'listing_tag', array( 'listing' ), $args );
}
add_action( 'init', 'connectpro_register_listing_tags', 0 );
