<?php
/**
 * Demo Content Importer
 *
 * @package ConnectPro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * One Click Demo Import Configuration
 * Requires: One Click Demo Import plugin
 */
function connectpro_import_files() {
    return array(
        array(
            'import_file_name'           => 'ConnectPro Demo',
            'categories'                 => array( 'Marketplace', 'Listings' ),
            'import_file_url'            => CONNECTPRO_THEME_URI . '/demo-content/demo-content.xml',
            'import_widget_file_url'     => CONNECTPRO_THEME_URI . '/demo-content/widgets.wie',
            'import_customizer_file_url' => CONNECTPRO_THEME_URI . '/demo-content/customizer.dat',
            'import_preview_image_url'   => CONNECTPRO_THEME_URI . '/screenshot.png',
            'import_notice'              => __( 'Please wait while we import the demo content. This process may take several minutes.', 'connectpro' ),
            'preview_url'                => 'https://connectpro-demo.com',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'connectpro_import_files' );

/**
 * After import setup
 */
function connectpro_after_import_setup() {
    // Assign menus to their locations
    $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
    $footer_menu = get_term_by( 'name', 'Footer Menu', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
        'primary' => $main_menu->term_id,
        'footer'  => $footer_menu->term_id,
    ) );

    // Assign front page and posts page
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );

    // Flush rewrite rules
    flush_rewrite_rules();
}
add_action( 'pt-ocdi/after_import', 'connectpro_after_import_setup' );

/**
 * Create sample listings programmatically
 */
function connectpro_create_sample_listings() {
    // Only run once
    if ( get_option( 'connectpro_demo_imported' ) ) {
        return;
    }

    $sample_listings = array(
        array(
            'title'       => 'Modern Studio Apartment in City Center',
            'description' => 'Beautiful studio apartment with modern amenities, perfect for professionals or students.',
            'price'       => 1200,
            'type'        => 'rent',
            'bedrooms'    => 1,
            'bathrooms'   => 1,
            'area'        => 450,
            'city'        => 'New York',
            'category'    => 'Real Estate',
        ),
        array(
            'title'       => 'Professional Photography Services',
            'description' => 'High-quality photography services for events, portraits, and commercial projects.',
            'price'       => 500,
            'type'        => 'service',
            'city'        => 'Los Angeles',
            'category'    => 'Services',
        ),
        array(
            'title'       => 'MacBook Pro 2023 - Like New',
            'description' => 'Barely used MacBook Pro 16" with M2 chip, 32GB RAM, 1TB SSD.',
            'price'       => 2500,
            'type'        => 'sale',
            'city'        => 'San Francisco',
            'category'    => 'Electronics',
        ),
        array(
            'title'       => 'Cozy Beach House for Summer',
            'description' => 'Beautiful beach house with ocean view, perfect for summer vacation.',
            'price'       => 3000,
            'type'        => 'rent',
            'bedrooms'    => 3,
            'bathrooms'   => 2,
            'area'        => 1500,
            'city'        => 'Miami',
            'category'    => 'Real Estate',
        ),
        array(
            'title'       => 'Web Development & Design Services',
            'description' => 'Professional web development services for businesses of all sizes.',
            'price'       => 75,
            'type'        => 'service',
            'city'        => 'Seattle',
            'category'    => 'Services',
        ),
    );

    foreach ( $sample_listings as $listing_data ) {
        // Create listing
        $post_id = wp_insert_post( array(
            'post_title'   => $listing_data['title'],
            'post_content' => $listing_data['description'],
            'post_status'  => 'publish',
            'post_type'    => 'listing',
        ) );

        if ( $post_id ) {
            // Add meta fields
            if ( isset( $listing_data['price'] ) ) {
                update_post_meta( $post_id, '_listing_price', $listing_data['price'] );
            }
            if ( isset( $listing_data['type'] ) ) {
                update_post_meta( $post_id, '_listing_type', $listing_data['type'] );
            }
            if ( isset( $listing_data['bedrooms'] ) ) {
                update_post_meta( $post_id, '_listing_bedrooms', $listing_data['bedrooms'] );
            }
            if ( isset( $listing_data['bathrooms'] ) ) {
                update_post_meta( $post_id, '_listing_bathrooms', $listing_data['bathrooms'] );
            }
            if ( isset( $listing_data['area'] ) ) {
                update_post_meta( $post_id, '_listing_area', $listing_data['area'] );
            }
            if ( isset( $listing_data['city'] ) ) {
                update_post_meta( $post_id, '_listing_city', $listing_data['city'] );
            }

            update_post_meta( $post_id, '_listing_currency', 'USD' );

            // Assign category
            if ( isset( $listing_data['category'] ) ) {
                wp_set_object_terms( $post_id, $listing_data['category'], 'listing_category' );
            }
        }
    }

    update_option( 'connectpro_demo_imported', true );
}
// Uncomment to create sample listings: add_action( 'admin_init', 'connectpro_create_sample_listings' );
