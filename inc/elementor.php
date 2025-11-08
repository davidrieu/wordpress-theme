<?php
/**
 * Elementor Integration
 *
 * @package ConnectPro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Elementor Locations
 */
function connectpro_register_elementor_locations( $elementor_theme_manager ) {
    $elementor_theme_manager->register_all_core_location();
}
add_action( 'elementor/theme/register_locations', 'connectpro_register_elementor_locations' );

/**
 * Add Elementor Support
 */
function connectpro_add_elementor_support() {
    // Add custom Elementor widgets category
    add_action( 'elementor/elements/categories_registered', 'connectpro_add_elementor_widget_categories' );

    // Register custom Elementor widgets
    add_action( 'elementor/widgets/widgets_registered', 'connectpro_register_elementor_widgets' );

    // Elementor theme builder support
    add_theme_support( 'elementor' );
}
add_action( 'after_setup_theme', 'connectpro_add_elementor_support' );

/**
 * Add custom Elementor widget category
 */
function connectpro_add_elementor_widget_categories( $elements_manager ) {
    $elements_manager->add_category(
        'connectpro',
        array(
            'title' => __( 'ConnectPro', 'connectpro' ),
            'icon'  => 'fa fa-plug',
        )
    );
}

/**
 * Register custom Elementor widgets
 */
function connectpro_register_elementor_widgets() {
    // Check if Elementor installed and activated
    if ( ! did_action( 'elementor/loaded' ) ) {
        return;
    }

    // Include widget files
    require_once CONNECTPRO_INC_DIR . '/elementor/widgets/listings-grid.php';
    require_once CONNECTPRO_INC_DIR . '/elementor/widgets/featured-listings.php';
    require_once CONNECTPRO_INC_DIR . '/elementor/widgets/listing-search.php';
    require_once CONNECTPRO_INC_DIR . '/elementor/widgets/listing-categories.php';

    // Register widgets
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \ConnectPro_Elementor_Listings_Grid() );
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \ConnectPro_Elementor_Featured_Listings() );
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \ConnectPro_Elementor_Listing_Search() );
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \ConnectPro_Elementor_Listing_Categories() );
}

/**
 * Add Elementor editor custom CSS
 */
function connectpro_elementor_editor_styles() {
    wp_enqueue_style( 'connectpro-elementor-editor', CONNECTPRO_THEME_URI . '/assets/css/elementor-editor.css', array(), CONNECTPRO_VERSION );
}
add_action( 'elementor/editor/after_enqueue_styles', 'connectpro_elementor_editor_styles' );

/**
 * Add Elementor frontend custom CSS
 */
function connectpro_elementor_frontend_styles() {
    wp_enqueue_style( 'connectpro-elementor', CONNECTPRO_THEME_URI . '/assets/css/elementor.css', array(), CONNECTPRO_VERSION );
}
add_action( 'elementor/frontend/after_enqueue_styles', 'connectpro_elementor_frontend_styles' );

/**
 * Disable Elementor default colors and fonts
 */
add_filter( 'elementor/editor/localize_settings', function( $settings ) {
    $settings['disable_default_schemes'] = true;
    return $settings;
});

/**
 * Custom Elementor color palette
 */
function connectpro_elementor_custom_colors() {
    return [
        [
            'label' => __( 'Primary', 'connectpro' ),
            'value' => '#3B82F6',
        ],
        [
            'label' => __( 'Secondary', 'connectpro' ),
            'value' => '#8B5CF6',
        ],
        [
            'label' => __( 'Accent', 'connectpro' ),
            'value' => '#F59E0B',
        ],
        [
            'label' => __( 'Dark', 'connectpro' ),
            'value' => '#1F2937',
        ],
        [
            'label' => __( 'Light', 'connectpro' ),
            'value' => '#F9FAFB',
        ],
    ];
}
add_filter( 'elementor/editor/localize_settings', function( $settings ) {
    $settings['system_colors'] = connectpro_elementor_custom_colors();
    return $settings;
});
