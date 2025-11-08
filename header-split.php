<?php
/**
 * Split Map Header
 *
 * Minimal header for split-map template
 *
 * @package ConnectPro
 * @since 1.0.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class( 'split-map-page' ); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site split-map-site">
    <a class="skip-link screen-reader-text" href="#primary">
        <?php esc_html_e( 'Skip to content', 'connectpro' ); ?>
    </a>

    <header id="masthead" class="split-header">
        <div class="split-header-inner container-fluid">
            <div class="split-header-left">
                <!-- Logo -->
                <div class="site-branding">
                    <?php
                    if ( has_custom_logo() ) :
                        the_custom_logo();
                    else :
                        ?>
                        <h1 class="site-title">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                                <?php bloginfo( 'name' ); ?>
                            </a>
                        </h1>
                        <?php
                    endif;
                    ?>
                </div>

                <!-- Navigation -->
                <nav id="split-navigation" class="split-navigation">
                    <?php
                    if ( has_nav_menu( 'split-header' ) ) {
                        wp_nav_menu( array(
                            'theme_location' => 'split-header',
                            'menu_class'     => 'split-nav-menu',
                            'container'      => false,
                            'depth'          => 1,
                        ) );
                    }
                    ?>
                </nav>
            </div>

            <div class="split-header-right">
                <!-- Dark Mode Toggle -->
                <?php if ( function_exists( 'connectpro_dark_mode_toggle_button' ) && get_theme_mod( 'dark_mode_toggle', true ) ) : ?>
                    <?php connectpro_dark_mode_toggle_button(); ?>
                <?php endif; ?>

                <!-- User Account -->
                <?php if ( is_user_logged_in() ) : ?>
                    <div class="split-user-menu">
                        <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'dashboard' ) ) ); ?>" class="btn btn-outline btn-sm">
                            <i class="fas fa-user"></i>
                            <span><?php esc_html_e( 'Dashboard', 'connectpro' ); ?></span>
                        </a>
                    </div>
                <?php else : ?>
                    <div class="split-auth-buttons">
                        <a href="<?php echo esc_url( wp_login_url() ); ?>" class="btn btn-outline btn-sm">
                            <?php esc_html_e( 'Sign In', 'connectpro' ); ?>
                        </a>
                        <a href="<?php echo esc_url( wp_registration_url() ); ?>" class="btn btn-primary btn-sm">
                            <?php esc_html_e( 'Sign Up', 'connectpro' ); ?>
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Add Listing Button -->
                <?php if ( is_user_logged_in() ) : ?>
                    <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'add-listing' ) ) ); ?>" class="btn btn-primary btn-sm btn-add-listing">
                        <i class="fas fa-plus"></i>
                        <span><?php esc_html_e( 'Add Listing', 'connectpro' ); ?></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div id="content" class="site-content split-content-wrapper">
