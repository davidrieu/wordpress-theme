<?php
/**
 * Dashboard Header
 *
 * Simplified header for dashboard pages
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

<body <?php body_class( 'dashboard-page' ); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site dashboard-site">
    <a class="skip-link screen-reader-text" href="#primary">
        <?php esc_html_e( 'Skip to content', 'connectpro' ); ?>
    </a>

    <header id="masthead" class="dashboard-header">
        <div class="dashboard-header-inner">
            <div class="dashboard-header-left">
                <!-- Mobile Menu Toggle -->
                <button class="dashboard-menu-toggle" aria-label="<?php esc_attr_e( 'Toggle Menu', 'connectpro' ); ?>">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

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
            </div>

            <div class="dashboard-header-right">
                <!-- Add Listing Button -->
                <a href="?tab=add-listing" class="btn btn-primary btn-add-listing">
                    <i class="fas fa-plus-circle"></i>
                    <span><?php esc_html_e( 'Add Listing', 'connectpro' ); ?></span>
                </a>

                <!-- Notifications -->
                <div class="dashboard-notifications">
                    <button class="notifications-trigger" aria-label="<?php esc_attr_e( 'Notifications', 'connectpro' ); ?>">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    <div class="notifications-dropdown">
                        <h4><?php esc_html_e( 'Notifications', 'connectpro' ); ?></h4>
                        <ul class="notifications-list">
                            <!-- Notifications will be loaded here -->
                        </ul>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="dashboard-user-menu">
                    <?php echo get_avatar( get_current_user_id(), 40 ); ?>
                    <div class="user-menu-dropdown">
                        <a href="?tab=profile"><?php esc_html_e( 'My Profile', 'connectpro' ); ?></a>
                        <a href="?tab=settings"><?php esc_html_e( 'Settings', 'connectpro' ); ?></a>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'View Site', 'connectpro' ); ?></a>
                        <div class="dropdown-divider"></div>
                        <a href="<?php echo wp_logout_url( home_url() ); ?>"><?php esc_html_e( 'Logout', 'connectpro' ); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div id="content" class="site-content dashboard-content-wrapper">
