<?php
/**
 * Fullwidth Header
 *
 * Full-width header for landing pages and special templates
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

<body <?php body_class( 'fullwidth-header-page' ); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site fullwidth-site">
    <a class="skip-link screen-reader-text" href="#primary">
        <?php esc_html_e( 'Skip to content', 'connectpro' ); ?>
    </a>

    <header id="masthead" class="site-header fullwidth-header transparent-header">
        <div class="fullwidth-header-inner">
            <div class="header-container container-fluid">
                <div class="header-row">
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
                    <nav id="site-navigation" class="main-navigation">
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'primary',
                            'menu_class'     => 'primary-menu',
                            'container'      => false,
                        ) );
                        ?>
                    </nav>

                    <!-- Header Actions -->
                    <div class="header-actions">
                        <!-- Dark Mode Toggle -->
                        <?php do_action( 'connectpro_header_actions' ); ?>

                        <!-- Search -->
                        <button class="search-toggle" aria-label="<?php esc_attr_e( 'Search', 'connectpro' ); ?>">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                            </svg>
                        </button>

                        <!-- User Account or Auth Buttons -->
                        <?php if ( is_user_logged_in() ) : ?>
                            <div class="user-account-menu">
                                <button class="user-menu-trigger" aria-label="<?php esc_attr_e( 'User Menu', 'connectpro' ); ?>">
                                    <?php echo get_avatar( get_current_user_id(), 32 ); ?>
                                    <span class="user-name"><?php echo esc_html( wp_get_current_user()->display_name ); ?></span>
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                <div class="user-menu-dropdown">
                                    <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'dashboard' ) ) ); ?>">
                                        <i class="fas fa-tachometer-alt"></i>
                                        <?php esc_html_e( 'Dashboard', 'connectpro' ); ?>
                                    </a>
                                    <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'dashboard' ) ) . '?tab=my-listings' ); ?>">
                                        <i class="fas fa-list"></i>
                                        <?php esc_html_e( 'My Listings', 'connectpro' ); ?>
                                    </a>
                                    <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'dashboard' ) ) . '?tab=favorites' ); ?>">
                                        <i class="fas fa-heart"></i>
                                        <?php esc_html_e( 'Favorites', 'connectpro' ); ?>
                                    </a>
                                    <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'dashboard' ) ) . '?tab=messages' ); ?>">
                                        <i class="fas fa-envelope"></i>
                                        <?php esc_html_e( 'Messages', 'connectpro' ); ?>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'dashboard' ) ) . '?tab=profile' ); ?>">
                                        <i class="fas fa-user-cog"></i>
                                        <?php esc_html_e( 'Settings', 'connectpro' ); ?>
                                    </a>
                                    <a href="<?php echo wp_logout_url( home_url() ); ?>">
                                        <i class="fas fa-sign-out-alt"></i>
                                        <?php esc_html_e( 'Logout', 'connectpro' ); ?>
                                    </a>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="auth-buttons">
                                <a href="<?php echo esc_url( wp_login_url() ); ?>" class="btn btn-outline btn-sm">
                                    <?php esc_html_e( 'Sign In', 'connectpro' ); ?>
                                </a>
                                <a href="<?php echo esc_url( wp_registration_url() ); ?>" class="btn btn-primary btn-sm">
                                    <?php esc_html_e( 'Sign Up', 'connectpro' ); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <!-- Add Listing Button (for logged-in users) -->
                        <?php if ( is_user_logged_in() ) : ?>
                            <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'add-listing' ) ) ); ?>" class="btn btn-primary">
                                <i class="fas fa-plus-circle"></i>
                                <span><?php esc_html_e( 'Add Listing', 'connectpro' ); ?></span>
                            </a>
                        <?php endif; ?>

                        <!-- Mobile Menu Toggle -->
                        <button class="mobile-menu-toggle" aria-label="<?php esc_attr_e( 'Toggle Menu', 'connectpro' ); ?>">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div class="mobile-menu-wrapper">
                <div class="mobile-menu-inner">
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'mobile',
                        'menu_class'     => 'mobile-menu',
                        'container'      => false,
                        'fallback_cb'    => false,
                    ) );
                    ?>

                    <?php if ( ! is_user_logged_in() ) : ?>
                        <div class="mobile-auth-buttons">
                            <a href="<?php echo esc_url( wp_login_url() ); ?>" class="btn btn-outline btn-block">
                                <?php esc_html_e( 'Sign In', 'connectpro' ); ?>
                            </a>
                            <a href="<?php echo esc_url( wp_registration_url() ); ?>" class="btn btn-primary btn-block">
                                <?php esc_html_e( 'Sign Up', 'connectpro' ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Search Modal -->
    <div id="search-modal" class="search-modal">
        <div class="search-modal-content">
            <button class="search-modal-close" aria-label="<?php esc_attr_e( 'Close Search', 'connectpro' ); ?>">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
            <div class="search-modal-body">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <input type="search" class="search-field" placeholder="<?php esc_attr_e( 'Search listings, locations, categories...', 'connectpro' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                    <button type="submit" class="search-submit">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div id="content" class="site-content fullwidth-content-wrapper">
