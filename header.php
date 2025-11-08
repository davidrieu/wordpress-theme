<?php
/**
 * The header template
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

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary">
        <?php esc_html_e( 'Skip to content', 'connectpro' ); ?>
    </a>

    <header id="masthead" class="site-header">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="container">
                <div class="top-bar-inner">
                    <div class="top-bar-left">
                        <?php if ( has_nav_menu( 'primary' ) ) : ?>
                            <nav class="top-navigation">
                                <?php
                                wp_nav_menu( array(
                                    'theme_location' => 'primary',
                                    'menu_id'        => 'top-menu',
                                    'container'      => false,
                                    'depth'          => 1,
                                ) );
                                ?>
                            </nav>
                        <?php endif; ?>
                    </div>
                    <div class="top-bar-right">
                        <?php if ( is_user_logged_in() ) : ?>
                            <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="top-bar-link">
                                <?php esc_html_e( 'Logout', 'connectpro' ); ?>
                            </a>
                        <?php else : ?>
                            <a href="<?php echo esc_url( wp_login_url() ); ?>" class="top-bar-link">
                                <?php esc_html_e( 'Login', 'connectpro' ); ?>
                            </a>
                            <a href="<?php echo esc_url( wp_registration_url() ); ?>" class="top-bar-link">
                                <?php esc_html_e( 'Register', 'connectpro' ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Header -->
        <div class="main-header">
            <div class="container">
                <div class="header-inner">
                    <div class="site-branding">
                        <?php
                        if ( has_custom_logo() ) :
                            the_custom_logo();
                        else :
                            ?>
                            <div class="site-title-wrap">
                                <h1 class="site-title">
                                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                                        <?php bloginfo( 'name' ); ?>
                                    </a>
                                </h1>
                                <?php
                                $description = get_bloginfo( 'description', 'display' );
                                if ( $description || is_customize_preview() ) :
                                    ?>
                                    <p class="site-description"><?php echo $description; ?></p>
                                <?php endif; ?>
                            </div>
                            <?php
                        endif;
                        ?>
                    </div>

                    <nav id="site-navigation" class="main-navigation">
                        <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                            <span class="menu-toggle-icon"></span>
                            <span class="screen-reader-text"><?php esc_html_e( 'Menu', 'connectpro' ); ?></span>
                        </button>
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'container'      => 'ul',
                            'menu_class'     => 'primary-menu',
                        ) );
                        ?>
                    </nav>

                    <div class="header-actions">
                        <!-- Search Button -->
                        <button class="search-toggle" aria-label="<?php esc_attr_e( 'Search', 'connectpro' ); ?>">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                            </svg>
                        </button>

                        <!-- Add Listing Button -->
                        <a href="<?php echo esc_url( home_url( '/submit-listing' ) ); ?>" class="btn btn-primary">
                            <?php esc_html_e( 'Add Listing', 'connectpro' ); ?>
                        </a>

                        <?php if ( is_user_logged_in() ) : ?>
                            <!-- User Dashboard -->
                            <a href="<?php echo esc_url( home_url( '/dashboard' ) ); ?>" class="user-avatar">
                                <?php echo get_avatar( get_current_user_id(), 32 ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Modal -->
        <div id="search-modal" class="search-modal">
            <div class="search-modal-content">
                <button class="search-modal-close">&times;</button>
                <?php get_search_form(); ?>
            </div>
        </div>
    </header>

    <div id="content" class="site-content">
