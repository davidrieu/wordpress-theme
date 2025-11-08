<?php
/**
 * Theme Customizer
 *
 * @package ConnectPro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Customizer Settings
 */
function connectpro_customize_register( $wp_customize ) {

    // ===== General Settings =====
    $wp_customize->add_section( 'connectpro_general', array(
        'title'    => __( 'General Settings', 'connectpro' ),
        'priority' => 30,
    ) );

    // Logo Width
    $wp_customize->add_setting( 'logo_width', array(
        'default'           => '200',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ) );

    $wp_customize->add_control( 'logo_width', array(
        'label'       => __( 'Logo Width (px)', 'connectpro' ),
        'section'     => 'connectpro_general',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 50,
            'max'  => 500,
            'step' => 10,
        ),
    ) );

    // ===== Color Settings =====
    $wp_customize->add_section( 'connectpro_colors', array(
        'title'    => __( 'Color Settings', 'connectpro' ),
        'priority' => 40,
    ) );

    // Primary Color
    $wp_customize->add_setting( 'primary_color', array(
        'default'           => '#3B82F6',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'primary_color', array(
        'label'   => __( 'Primary Color', 'connectpro' ),
        'section' => 'connectpro_colors',
    ) ) );

    // Secondary Color
    $wp_customize->add_setting( 'secondary_color', array(
        'default'           => '#8B5CF6',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'secondary_color', array(
        'label'   => __( 'Secondary Color', 'connectpro' ),
        'section' => 'connectpro_colors',
    ) ) );

    // Accent Color
    $wp_customize->add_setting( 'accent_color', array(
        'default'           => '#F59E0B',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'accent_color', array(
        'label'   => __( 'Accent Color', 'connectpro' ),
        'section' => 'connectpro_colors',
    ) ) );

    // ===== Header Settings =====
    $wp_customize->add_section( 'connectpro_header', array(
        'title'    => __( 'Header Settings', 'connectpro' ),
        'priority' => 50,
    ) );

    // Header Style
    $wp_customize->add_setting( 'header_style', array(
        'default'           => 'default',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'header_style', array(
        'label'   => __( 'Header Style', 'connectpro' ),
        'section' => 'connectpro_header',
        'type'    => 'select',
        'choices' => array(
            'default' => __( 'Default', 'connectpro' ),
            'minimal' => __( 'Minimal', 'connectpro' ),
            'centered' => __( 'Centered', 'connectpro' ),
        ),
    ) );

    // Sticky Header
    $wp_customize->add_setting( 'sticky_header', array(
        'default'           => true,
        'sanitize_callback' => 'connectpro_sanitize_checkbox',
    ) );

    $wp_customize->add_control( 'sticky_header', array(
        'label'   => __( 'Enable Sticky Header', 'connectpro' ),
        'section' => 'connectpro_header',
        'type'    => 'checkbox',
    ) );

    // ===== Footer Settings =====
    $wp_customize->add_section( 'connectpro_footer', array(
        'title'    => __( 'Footer Settings', 'connectpro' ),
        'priority' => 60,
    ) );

    // Footer Copyright Text
    $wp_customize->add_setting( 'footer_copyright', array(
        'default'           => sprintf( __( '&copy; %s %s. All rights reserved.', 'connectpro' ), date( 'Y' ), get_bloginfo( 'name' ) ),
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'footer_copyright', array(
        'label'   => __( 'Copyright Text', 'connectpro' ),
        'section' => 'connectpro_footer',
        'type'    => 'textarea',
    ) );

    // ===== Social Media =====
    $wp_customize->add_section( 'connectpro_social', array(
        'title'    => __( 'Social Media', 'connectpro' ),
        'priority' => 70,
    ) );

    $social_networks = array(
        'facebook'  => 'Facebook',
        'twitter'   => 'Twitter',
        'instagram' => 'Instagram',
        'linkedin'  => 'LinkedIn',
        'youtube'   => 'YouTube',
    );

    foreach ( $social_networks as $network => $label ) {
        $wp_customize->add_setting( 'social_' . $network, array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ) );

        $wp_customize->add_control( 'social_' . $network, array(
            'label'   => $label . ' ' . __( 'URL', 'connectpro' ),
            'section' => 'connectpro_social',
            'type'    => 'url',
        ) );
    }

    // ===== Typography =====
    $wp_customize->add_section( 'connectpro_typography', array(
        'title'    => __( 'Typography', 'connectpro' ),
        'priority' => 80,
    ) );

    // Body Font Size
    $wp_customize->add_setting( 'body_font_size', array(
        'default'           => '16',
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( 'body_font_size', array(
        'label'       => __( 'Body Font Size (px)', 'connectpro' ),
        'section'     => 'connectpro_typography',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 12,
            'max'  => 24,
            'step' => 1,
        ),
    ) );

    // ===== Blog Settings =====
    $wp_customize->add_section( 'connectpro_blog', array(
        'title'    => __( 'Blog Settings', 'connectpro' ),
        'priority' => 90,
    ) );

    // Blog Layout
    $wp_customize->add_setting( 'blog_layout', array(
        'default'           => 'grid',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'blog_layout', array(
        'label'   => __( 'Blog Layout', 'connectpro' ),
        'section' => 'connectpro_blog',
        'type'    => 'select',
        'choices' => array(
            'list' => __( 'List', 'connectpro' ),
            'grid' => __( 'Grid', 'connectpro' ),
        ),
    ) );
}
add_action( 'customize_register', 'connectpro_customize_register' );

/**
 * Sanitize checkbox
 */
function connectpro_sanitize_checkbox( $checked ) {
    return ( isset( $checked ) && true === $checked ) ? true : false;
}

/**
 * Output custom CSS
 */
function connectpro_customizer_css() {
    $primary_color = get_theme_mod( 'primary_color', '#3B82F6' );
    $secondary_color = get_theme_mod( 'secondary_color', '#8B5CF6' );
    $accent_color = get_theme_mod( 'accent_color', '#F59E0B' );
    $body_font_size = get_theme_mod( 'body_font_size', '16' );
    $logo_width = get_theme_mod( 'logo_width', '200' );

    ?>
    <style type="text/css">
        :root {
            --primary-color: <?php echo esc_attr( $primary_color ); ?>;
            --secondary-color: <?php echo esc_attr( $secondary_color ); ?>;
            --accent-color: <?php echo esc_attr( $accent_color ); ?>;
        }

        body {
            font-size: <?php echo esc_attr( $body_font_size ); ?>px;
        }

        .custom-logo {
            max-width: <?php echo esc_attr( $logo_width ); ?>px;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'connectpro_customizer_css' );

/**
 * Customizer live preview
 */
function connectpro_customizer_live_preview() {
    wp_enqueue_script( 'connectpro-customizer', CONNECTPRO_THEME_URI . '/assets/js/customizer.js', array( 'jquery', 'customize-preview' ), CONNECTPRO_VERSION, true );
}
add_action( 'customize_preview_init', 'connectpro_customizer_live_preview' );
