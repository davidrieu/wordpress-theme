<?php
/**
 * ConnectPro Theme Functions
 *
 * @package ConnectPro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Theme Constants
define( 'CONNECTPRO_VERSION', '1.0.0' );
define( 'CONNECTPRO_THEME_DIR', get_template_directory() );
define( 'CONNECTPRO_THEME_URI', get_template_directory_uri() );
define( 'CONNECTPRO_INC_DIR', CONNECTPRO_THEME_DIR . '/inc' );

/**
 * Theme Setup
 */
function connectpro_theme_setup() {
    // Make theme available for translation
    load_theme_textdomain( 'connectpro', CONNECTPRO_THEME_DIR . '/languages' );

    // Add default posts and comments RSS feed links to head
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails
    add_theme_support( 'post-thumbnails' );

    // Custom image sizes
    add_image_size( 'connectpro-listing-thumb', 400, 300, true );
    add_image_size( 'connectpro-listing-large', 800, 600, true );
    add_image_size( 'connectpro-featured', 1200, 600, true );

    // Register navigation menus
    register_nav_menus( array(
        'primary'       => esc_html__( 'Primary Menu', 'connectpro' ),
        'footer'        => esc_html__( 'Footer Menu', 'connectpro' ),
        'mobile'        => esc_html__( 'Mobile Menu', 'connectpro' ),
        'user-dashboard' => esc_html__( 'User Dashboard Menu', 'connectpro' ),
    ) );

    // Switch default core markup to output valid HTML5
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );

    // Add theme support for selective refresh for widgets
    add_theme_support( 'customize-selective-refresh-widgets' );

    // Add support for custom logo
    add_theme_support( 'custom-logo', array(
        'height'      => 100,
        'width'       => 300,
        'flex-width'  => true,
        'flex-height' => true,
    ) );

    // Add support for custom background
    add_theme_support( 'custom-background', array(
        'default-color' => 'ffffff',
    ) );

    // Add support for Block Styles
    add_theme_support( 'wp-block-styles' );

    // Add support for full and wide align images
    add_theme_support( 'align-wide' );

    // Add support for responsive embedded content
    add_theme_support( 'responsive-embeds' );

    // Add support for editor styles
    add_theme_support( 'editor-styles' );
    add_editor_style( 'assets/css/editor-style.css' );

    // Gutenberg color palette
    add_theme_support( 'editor-color-palette', array(
        array(
            'name'  => esc_html__( 'Primary', 'connectpro' ),
            'slug'  => 'primary',
            'color' => '#3B82F6',
        ),
        array(
            'name'  => esc_html__( 'Secondary', 'connectpro' ),
            'slug'  => 'secondary',
            'color' => '#8B5CF6',
        ),
        array(
            'name'  => esc_html__( 'Accent', 'connectpro' ),
            'slug'  => 'accent',
            'color' => '#F59E0B',
        ),
        array(
            'name'  => esc_html__( 'Dark', 'connectpro' ),
            'slug'  => 'dark',
            'color' => '#1F2937',
        ),
        array(
            'name'  => esc_html__( 'Light', 'connectpro' ),
            'slug'  => 'light',
            'color' => '#F9FAFB',
        ),
    ) );

    // WooCommerce Support
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    // Set content width
    if ( ! isset( $content_width ) ) {
        $content_width = 1200;
    }
}
add_action( 'after_setup_theme', 'connectpro_theme_setup' );

/**
 * Register Widget Areas
 */
function connectpro_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Main Sidebar', 'connectpro' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets here for the main sidebar.', 'connectpro' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Shop Sidebar', 'connectpro' ),
        'id'            => 'sidebar-shop',
        'description'   => esc_html__( 'Add widgets here for the shop sidebar.', 'connectpro' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widget Area 1', 'connectpro' ),
        'id'            => 'footer-1',
        'description'   => esc_html__( 'Add widgets here for footer column 1.', 'connectpro' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widget Area 2', 'connectpro' ),
        'id'            => 'footer-2',
        'description'   => esc_html__( 'Add widgets here for footer column 2.', 'connectpro' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widget Area 3', 'connectpro' ),
        'id'            => 'footer-3',
        'description'   => esc_html__( 'Add widgets here for footer column 3.', 'connectpro' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widget Area 4', 'connectpro' ),
        'id'            => 'footer-4',
        'description'   => esc_html__( 'Add widgets here for footer column 4.', 'connectpro' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'connectpro_widgets_init' );

/**
 * Enqueue Scripts and Styles
 */
function connectpro_scripts() {
    // Main stylesheet
    wp_enqueue_style( 'connectpro-style', get_stylesheet_uri(), array(), CONNECTPRO_VERSION );

    // Google Fonts
    wp_enqueue_style( 'connectpro-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap', array(), null );

    // Main CSS
    wp_enqueue_style( 'connectpro-main', CONNECTPRO_THEME_URI . '/assets/css/main.css', array(), CONNECTPRO_VERSION );

    // Responsive CSS
    wp_enqueue_style( 'connectpro-responsive', CONNECTPRO_THEME_URI . '/assets/css/responsive.css', array(), CONNECTPRO_VERSION );

    // Main JavaScript
    wp_enqueue_script( 'connectpro-main', CONNECTPRO_THEME_URI . '/assets/js/main.js', array( 'jquery' ), CONNECTPRO_VERSION, true );

    // Navigation script
    wp_enqueue_script( 'connectpro-navigation', CONNECTPRO_THEME_URI . '/assets/js/navigation.js', array( 'jquery' ), CONNECTPRO_VERSION, true );

    // Comment reply
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }

    // Localize script
    wp_localize_script( 'connectpro-main', 'connectproData', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'connectpro_nonce' ),
        'theme_url' => CONNECTPRO_THEME_URI,
    ) );
}
add_action( 'wp_enqueue_scripts', 'connectpro_scripts' );

/**
 * Enqueue Admin Scripts
 */
function connectpro_admin_scripts( $hook ) {
    wp_enqueue_style( 'connectpro-admin', CONNECTPRO_THEME_URI . '/assets/css/admin.css', array(), CONNECTPRO_VERSION );
    wp_enqueue_script( 'connectpro-admin', CONNECTPRO_THEME_URI . '/assets/js/admin.js', array( 'jquery' ), CONNECTPRO_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'connectpro_admin_scripts' );

/**
 * Include Required Files
 */

// TGM Plugin Activation
require_once CONNECTPRO_INC_DIR . '/class-tgm-plugin-activation.php';
require_once CONNECTPRO_INC_DIR . '/required-plugins.php';

// Custom Post Types
require_once CONNECTPRO_INC_DIR . '/post-types/listing-post-type.php';

// Custom Taxonomies
require_once CONNECTPRO_INC_DIR . '/post-types/listing-taxonomies.php';

// WooCommerce Integration
if ( class_exists( 'WooCommerce' ) ) {
    require_once CONNECTPRO_INC_DIR . '/woocommerce.php';
}

// Elementor Integration
if ( did_action( 'elementor/loaded' ) ) {
    require_once CONNECTPRO_INC_DIR . '/elementor.php';
}

// Customizer
require_once CONNECTPRO_INC_DIR . '/customizer/customizer.php';

// Custom Widgets
require_once CONNECTPRO_INC_DIR . '/widgets/recent-listings-widget.php';
require_once CONNECTPRO_INC_DIR . '/widgets/search-widget.php';

// User Dashboard
require_once CONNECTPRO_INC_DIR . '/user-dashboard.php';

// Messaging System
require_once CONNECTPRO_INC_DIR . '/messaging-system.php';

// Favorites/Wishlist
require_once CONNECTPRO_INC_DIR . '/favorites.php';

// Search & Filter
require_once CONNECTPRO_INC_DIR . '/search-filter.php';

// AJAX Handlers
require_once CONNECTPRO_INC_DIR . '/ajax-handlers.php';

// Helper Functions
require_once CONNECTPRO_INC_DIR . '/helpers.php';

// Demo Content Importer
require_once CONNECTPRO_INC_DIR . '/demo-importer.php';

/**
 * Add body classes
 */
function connectpro_body_classes( $classes ) {
    // Adds a class of hfeed to non-singular pages
    if ( ! is_singular() ) {
        $classes[] = 'hfeed';
    }

    // Adds a class of no-sidebar when there is no sidebar
    if ( ! is_active_sidebar( 'sidebar-1' ) ) {
        $classes[] = 'no-sidebar';
    }

    return $classes;
}
add_filter( 'body_class', 'connectpro_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts
 */
function connectpro_pingback_header() {
    if ( is_singular() && pings_open() ) {
        printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
    }
}
add_action( 'wp_head', 'connectpro_pingback_header' );

/**
 * Custom excerpt length
 */
function connectpro_excerpt_length( $length ) {
    return 30;
}
add_filter( 'excerpt_length', 'connectpro_excerpt_length', 999 );

/**
 * Custom excerpt more
 */
function connectpro_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'connectpro_excerpt_more' );

/**
 * Security: Remove WordPress version
 */
remove_action( 'wp_head', 'wp_generator' );

/**
 * Performance: Remove emoji scripts
 */
function connectpro_disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'connectpro_disable_emojis' );

/**
 * Add SVG support
 */
function connectpro_mime_types( $mimes ) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'connectpro_mime_types' );
