<?php
/**
 * Template Name: Dashboard - Full Featured
 *
 * Complete dashboard with advanced features inspired by modern marketplace themes
 *
 * @package ConnectPro
 * @since 1.0.0
 */

// Redirect if not logged in
if ( ! is_user_logged_in() ) {
    auth_redirect();
}

get_header( 'dashboard' );

$current_user = wp_get_current_user();
$current_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'dashboard';
?>

<div id="dashboard" class="dashboard-container">
    <div class="dashboard-sidebar">
        <div class="dashboard-sidebar-inner">
            <!-- User Profile Card -->
            <div class="dashboard-user-card">
                <div class="user-avatar">
                    <?php echo get_avatar( $current_user->ID, 80 ); ?>
                </div>
                <div class="user-info">
                    <h4><?php echo esc_html( $current_user->display_name ); ?></h4>
                    <span class="user-email"><?php echo esc_html( $current_user->user_email ); ?></span>
                </div>
            </div>

            <!-- Dashboard Navigation -->
            <nav class="dashboard-nav">
                <?php
                // Dashboard menu location
                wp_nav_menu( array(
                    'theme_location' => 'dashboard_main',
                    'menu_class'     => 'dashboard-nav-menu',
                    'container'      => false,
                    'fallback_cb'    => 'connectpro_dashboard_menu_fallback',
                ) );
                ?>
            </nav>

            <!-- Listings Submenu -->
            <div class="dashboard-nav-section">
                <h5 class="nav-section-title"><?php esc_html_e( 'Manage Listings', 'connectpro' ); ?></h5>
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'dashboard_listings',
                    'menu_class'     => 'dashboard-nav-submenu',
                    'container'      => false,
                ) );
                ?>
            </div>

            <!-- Account Submenu -->
            <div class="dashboard-nav-section">
                <h5 class="nav-section-title"><?php esc_html_e( 'Account', 'connectpro' ); ?></h5>
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'dashboard_account',
                    'menu_class'     => 'dashboard-nav-submenu',
                    'container'      => false,
                ) );
                ?>
            </div>
        </div>
    </div>

    <div class="dashboard-content">
        <!-- Top Bar -->
        <div class="dashboard-top-bar">
            <div class="dashboard-breadcrumbs">
                <span><?php esc_html_e( 'Dashboard', 'connectpro' ); ?></span>
                <?php if ( $current_tab !== 'dashboard' ) : ?>
                    <span class="separator">/</span>
                    <span class="current"><?php echo esc_html( ucfirst( str_replace( '-', ' ', $current_tab ) ) ); ?></span>
                <?php endif; ?>
            </div>

            <div class="dashboard-actions">
                <?php
                // Dashboard top menu
                wp_nav_menu( array(
                    'theme_location' => 'dashboard_top_menu',
                    'menu_class'     => 'dashboard-top-menu',
                    'container'      => false,
                ) );
                ?>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="dashboard-main-content">
            <?php
            // Load the appropriate dashboard tab content
            switch ( $current_tab ) {
                case 'dashboard':
                    connectpro_dashboard_overview_tab();
                    break;

                case 'my-listings':
                    connectpro_dashboard_listings_tab();
                    break;

                case 'add-listing':
                    connectpro_dashboard_add_listing_tab();
                    break;

                case 'bookings':
                    connectpro_dashboard_bookings_tab();
                    break;

                case 'reviews':
                    connectpro_dashboard_reviews_tab();
                    break;

                case 'favorites':
                    connectpro_dashboard_favorites_tab();
                    break;

                case 'messages':
                    connectpro_dashboard_messages_tab();
                    break;

                case 'profile':
                    connectpro_dashboard_profile_tab();
                    break;

                case 'wallet':
                    connectpro_dashboard_wallet_tab();
                    break;

                default:
                    do_action( 'connectpro_dashboard_tab_' . $current_tab );
                    break;
            }
            ?>
        </div>
    </div>
</div>

<?php
get_footer( 'dashboard' );

/**
 * Dashboard Menu Fallback
 */
function connectpro_dashboard_menu_fallback() {
    ?>
    <ul class="dashboard-nav-menu">
        <li><a href="?tab=dashboard"><i class="fas fa-tachometer-alt"></i> <?php esc_html_e( 'Dashboard', 'connectpro' ); ?></a></li>
        <li><a href="?tab=my-listings"><i class="fas fa-list"></i> <?php esc_html_e( 'My Listings', 'connectpro' ); ?></a></li>
        <li><a href="?tab=bookings"><i class="fas fa-calendar-check"></i> <?php esc_html_e( 'Bookings', 'connectpro' ); ?></a></li>
        <li><a href="?tab=reviews"><i class="fas fa-star"></i> <?php esc_html_e( 'Reviews', 'connectpro' ); ?></a></li>
        <li><a href="?tab=favorites"><i class="fas fa-heart"></i> <?php esc_html_e( 'Favorites', 'connectpro' ); ?></a></li>
        <li><a href="?tab=messages"><i class="fas fa-envelope"></i> <?php esc_html_e( 'Messages', 'connectpro' ); ?></a></li>
        <li><a href="?tab=profile"><i class="fas fa-user"></i> <?php esc_html_e( 'My Profile', 'connectpro' ); ?></a></li>
        <li><a href="<?php echo wp_logout_url( home_url() ); ?>"><i class="fas fa-sign-out-alt"></i> <?php esc_html_e( 'Logout', 'connectpro' ); ?></a></li>
    </ul>
    <?php
}

/**
 * Dashboard Overview Tab
 */
function connectpro_dashboard_overview_tab() {
    $user_id = get_current_user_id();
    ?>
    <div class="dashboard-overview">
        <h2><?php esc_html_e( 'Dashboard Overview', 'connectpro' ); ?></h2>

        <!-- Statistics Cards -->
        <div class="dashboard-stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-list-alt"></i></div>
                <div class="stat-content">
                    <h3><?php echo count_user_posts( $user_id, 'listing' ); ?></h3>
                    <p><?php esc_html_e( 'Active Listings', 'connectpro' ); ?></p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-eye"></i></div>
                <div class="stat-content">
                    <h3><?php echo connectpro_get_total_listing_views( $user_id ); ?></h3>
                    <p><?php esc_html_e( 'Total Views', 'connectpro' ); ?></p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-star"></i></div>
                <div class="stat-content">
                    <h3><?php echo connectpro_get_favorites_count(); ?></h3>
                    <p><?php esc_html_e( 'Favorites', 'connectpro' ); ?></p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-content">
                    <h3><?php echo connectpro_get_user_bookings_count( $user_id ); ?></h3>
                    <p><?php esc_html_e( 'Bookings', 'connectpro' ); ?></p>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="dashboard-section">
            <h3><?php esc_html_e( 'Recent Activity', 'connectpro' ); ?></h3>
            <?php connectpro_display_recent_activity( $user_id ); ?>
        </div>

        <!-- Quick Actions -->
        <div class="dashboard-quick-actions">
            <h3><?php esc_html_e( 'Quick Actions', 'connectpro' ); ?></h3>
            <div class="quick-actions-grid">
                <a href="?tab=add-listing" class="quick-action-btn">
                    <i class="fas fa-plus-circle"></i>
                    <span><?php esc_html_e( 'Add New Listing', 'connectpro' ); ?></span>
                </a>
                <a href="?tab=my-listings" class="quick-action-btn">
                    <i class="fas fa-list"></i>
                    <span><?php esc_html_e( 'Manage Listings', 'connectpro' ); ?></span>
                </a>
                <a href="?tab=messages" class="quick-action-btn">
                    <i class="fas fa-envelope"></i>
                    <span><?php esc_html_e( 'Messages', 'connectpro' ); ?></span>
                </a>
                <a href="?tab=profile" class="quick-action-btn">
                    <i class="fas fa-user-edit"></i>
                    <span><?php esc_html_e( 'Edit Profile', 'connectpro' ); ?></span>
                </a>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Helper Functions
 */
function connectpro_get_total_listing_views( $user_id ) {
    // Get all user listings
    $user_listings = get_posts( array(
        'author'         => $user_id,
        'post_type'      => 'listing',
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ) );

    $total_views = 0;
    foreach ( $user_listings as $listing_id ) {
        $views = get_post_meta( $listing_id, 'listing_views', true );
        $total_views += intval( $views );
    }

    return $total_views;
}

function connectpro_get_user_bookings_count( $user_id ) {
    // Placeholder - implement based on your booking system
    return 0;
}

function connectpro_display_recent_activity( $user_id ) {
    // Get recent listings
    $recent_listings = get_posts( array(
        'author'         => $user_id,
        'post_type'      => 'listing',
        'posts_per_page' => 5,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ) );

    if ( $recent_listings ) {
        echo '<ul class="activity-list">';
        foreach ( $recent_listings as $listing ) {
            echo '<li>';
            echo '<span class="activity-icon"><i class="fas fa-plus"></i></span>';
            echo '<span class="activity-text">';
            printf(
                esc_html__( 'You added "%s" listing', 'connectpro' ),
                esc_html( $listing->post_title )
            );
            echo '</span>';
            echo '<span class="activity-time">' . human_time_diff( strtotime( $listing->post_date ), current_time( 'timestamp' ) ) . ' ' . esc_html__( 'ago', 'connectpro' ) . '</span>';
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>' . esc_html__( 'No recent activity', 'connectpro' ) . '</p>';
    }
}

// Other tab functions...
function connectpro_dashboard_listings_tab() {
    connectpro_dashboard_my_listings();
}

function connectpro_dashboard_add_listing_tab() {
    echo '<h2>' . esc_html__( 'Add New Listing', 'connectpro' ) . '</h2>';
    echo do_shortcode( '[submit_listing]' );
}

function connectpro_dashboard_bookings_tab() {
    echo '<h2>' . esc_html__( 'My Bookings', 'connectpro' ) . '</h2>';
    echo '<p>' . esc_html__( 'Bookings system coming soon...', 'connectpro' ) . '</p>';
}

function connectpro_dashboard_reviews_tab() {
    echo '<h2>' . esc_html__( 'Reviews', 'connectpro' ) . '</h2>';
    echo '<p>' . esc_html__( 'Review management coming soon...', 'connectpro' ) . '</p>';
}

function connectpro_dashboard_favorites_tab() {
    connectpro_favorites_page_content();
}

function connectpro_dashboard_messages_tab() {
    connectpro_dashboard_messages();
}

function connectpro_dashboard_profile_tab() {
    connectpro_dashboard_edit_profile();
}

function connectpro_dashboard_wallet_tab() {
    echo '<h2>' . esc_html__( 'My Wallet', 'connectpro' ) . '</h2>';
    echo '<p>' . esc_html__( 'Wallet and earnings management coming soon...', 'connectpro' ) . '</p>';
}
