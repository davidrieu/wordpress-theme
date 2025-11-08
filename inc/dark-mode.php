<?php
/**
 * Dark Mode Functionality
 *
 * @package ConnectPro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add Dark Mode Setting to Customizer
 */
function connectpro_add_dark_mode_settings( $wp_customize ) {
    // Dark Mode Section
    $wp_customize->add_section( 'connectpro_dark_mode', array(
        'title'    => __( 'Dark Mode', 'connectpro' ),
        'priority' => 45,
    ) );

    // Enable Dark Mode
    $wp_customize->add_setting( 'enable_dark_mode', array(
        'default'           => false,
        'sanitize_callback' => 'connectpro_sanitize_checkbox',
    ) );

    $wp_customize->add_control( 'enable_dark_mode', array(
        'label'   => __( 'Enable Dark Mode', 'connectpro' ),
        'section' => 'connectpro_dark_mode',
        'type'    => 'checkbox',
    ) );

    // Dark Mode Toggle Button
    $wp_customize->add_setting( 'dark_mode_toggle', array(
        'default'           => true,
        'sanitize_callback' => 'connectpro_sanitize_checkbox',
    ) );

    $wp_customize->add_control( 'dark_mode_toggle', array(
        'label'       => __( 'Show Dark Mode Toggle', 'connectpro' ),
        'description' => __( 'Display dark mode toggle button in header', 'connectpro' ),
        'section'     => 'connectpro_dark_mode',
        'type'        => 'checkbox',
    ) );

    // Auto Dark Mode (based on system preference)
    $wp_customize->add_setting( 'auto_dark_mode', array(
        'default'           => false,
        'sanitize_callback' => 'connectpro_sanitize_checkbox',
    ) );

    $wp_customize->add_control( 'auto_dark_mode', array(
        'label'       => __( 'Auto Dark Mode', 'connectpro' ),
        'description' => __( 'Automatically switch to dark mode based on system preference', 'connectpro' ),
        'section'     => 'connectpro_dark_mode',
        'type'        => 'checkbox',
    ) );
}
add_action( 'customize_register', 'connectpro_add_dark_mode_settings' );

/**
 * Enqueue Dark Mode Styles
 */
function connectpro_enqueue_dark_mode() {
    if ( get_theme_mod( 'enable_dark_mode', false ) ) {
        wp_enqueue_style(
            'connectpro-dark-mode',
            CONNECTPRO_THEME_URI . '/assets/css/dark-mode.css',
            array( 'connectpro-style' ),
            CONNECTPRO_VERSION
        );
    }

    if ( get_theme_mod( 'dark_mode_toggle', true ) || get_theme_mod( 'auto_dark_mode', false ) ) {
        wp_enqueue_script(
            'connectpro-dark-mode',
            CONNECTPRO_THEME_URI . '/assets/js/dark-mode.js',
            array( 'jquery' ),
            CONNECTPRO_VERSION,
            true
        );

        wp_localize_script( 'connectpro-dark-mode', 'connectproDarkMode', array(
            'auto' => get_theme_mod( 'auto_dark_mode', false ),
        ) );
    }
}
add_action( 'wp_enqueue_scripts', 'connectpro_enqueue_dark_mode' );

/**
 * Add Dark Mode Toggle Button to Header
 */
function connectpro_dark_mode_toggle_button() {
    if ( get_theme_mod( 'dark_mode_toggle', true ) ) {
        ?>
        <button class="dark-mode-toggle" id="dark-mode-toggle" aria-label="<?php esc_attr_e( 'Toggle Dark Mode', 'connectpro' ); ?>">
            <span class="dark-mode-icon sun-icon">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                </svg>
            </span>
            <span class="dark-mode-icon moon-icon" style="display: none;">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                </svg>
            </span>
        </button>
        <?php
    }
}
add_action( 'connectpro_header_actions', 'connectpro_dark_mode_toggle_button' );

/**
 * Add Dark Mode Body Class
 */
function connectpro_dark_mode_body_class( $classes ) {
    if ( get_theme_mod( 'enable_dark_mode', false ) ) {
        $classes[] = 'dark-mode-enabled';
    }

    if ( get_theme_mod( 'auto_dark_mode', false ) ) {
        $classes[] = 'dark-mode-auto';
    }

    return $classes;
}
add_filter( 'body_class', 'connectpro_dark_mode_body_class' );
