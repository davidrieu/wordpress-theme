<?php
/**
 * User Dashboard
 *
 * @package ConnectPro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Dashboard page template
 */
function connectpro_user_dashboard_content() {
    if ( ! is_user_logged_in() ) {
        echo '<p>' . esc_html__( 'Please log in to access your dashboard.', 'connectpro' ) . '</p>';
        echo '<a href="' . esc_url( wp_login_url( get_permalink() ) ) . '" class="btn btn-primary">' . esc_html__( 'Log In', 'connectpro' ) . '</a>';
        return;
    }

    $current_user = wp_get_current_user();
    $tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'overview';
    ?>
    <div class="user-dashboard">
        <div class="dashboard-sidebar">
            <div class="user-profile-widget">
                <?php echo get_avatar( $current_user->ID, 80 ); ?>
                <h3><?php echo esc_html( $current_user->display_name ); ?></h3>
                <p><?php echo esc_html( $current_user->user_email ); ?></p>
            </div>

            <nav class="dashboard-nav">
                <ul>
                    <li class="<?php echo $tab === 'overview' ? 'active' : ''; ?>">
                        <a href="?tab=overview"><?php esc_html_e( 'Overview', 'connectpro' ); ?></a>
                    </li>
                    <li class="<?php echo $tab === 'my-listings' ? 'active' : ''; ?>">
                        <a href="?tab=my-listings"><?php esc_html_e( 'My Listings', 'connectpro' ); ?></a>
                    </li>
                    <li class="<?php echo $tab === 'favorites' ? 'active' : ''; ?>">
                        <a href="?tab=favorites"><?php esc_html_e( 'Favorites', 'connectpro' ); ?></a>
                    </li>
                    <li class="<?php echo $tab === 'messages' ? 'active' : ''; ?>">
                        <a href="?tab=messages"><?php esc_html_e( 'Messages', 'connectpro' ); ?></a>
                    </li>
                    <li class="<?php echo $tab === 'profile' ? 'active' : ''; ?>">
                        <a href="?tab=profile"><?php esc_html_e( 'Edit Profile', 'connectpro' ); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>"><?php esc_html_e( 'Logout', 'connectpro' ); ?></a>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="dashboard-content">
            <?php
            switch ( $tab ) {
                case 'overview':
                    connectpro_dashboard_overview();
                    break;

                case 'my-listings':
                    connectpro_dashboard_my_listings();
                    break;

                case 'favorites':
                    connectpro_favorites_page_content();
                    break;

                case 'messages':
                    connectpro_dashboard_messages();
                    break;

                case 'profile':
                    connectpro_dashboard_edit_profile();
                    break;

                default:
                    connectpro_dashboard_overview();
                    break;
            }
            ?>
        </div>
    </div>
    <?php
}

/**
 * Dashboard overview tab
 */
function connectpro_dashboard_overview() {
    $user_id = get_current_user_id();

    // Count user's listings
    $listings_count = count_user_posts( $user_id, 'listing' );

    // Count favorites
    $favorites_count = connectpro_get_favorites_count();

    // Get recent listings
    $recent_listings = get_posts( array(
        'post_type'      => 'listing',
        'author'         => $user_id,
        'posts_per_page' => 5,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ) );

    ?>
    <div class="dashboard-overview">
        <h2><?php esc_html_e( 'Dashboard Overview', 'connectpro' ); ?></h2>

        <div class="dashboard-stats">
            <div class="stat-box">
                <h3><?php echo esc_html( $listings_count ); ?></h3>
                <p><?php esc_html_e( 'My Listings', 'connectpro' ); ?></p>
            </div>
            <div class="stat-box">
                <h3><?php echo esc_html( $favorites_count ); ?></h3>
                <p><?php esc_html_e( 'Favorites', 'connectpro' ); ?></p>
            </div>
            <div class="stat-box">
                <h3>0</h3>
                <p><?php esc_html_e( 'Messages', 'connectpro' ); ?></p>
            </div>
        </div>

        <?php if ( ! empty( $recent_listings ) ) : ?>
            <div class="recent-listings">
                <h3><?php esc_html_e( 'Recent Listings', 'connectpro' ); ?></h3>
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Title', 'connectpro' ); ?></th>
                            <th><?php esc_html_e( 'Status', 'connectpro' ); ?></th>
                            <th><?php esc_html_e( 'Date', 'connectpro' ); ?></th>
                            <th><?php esc_html_e( 'Actions', 'connectpro' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $recent_listings as $listing ) : ?>
                            <tr>
                                <td><a href="<?php echo esc_url( get_permalink( $listing->ID ) ); ?>"><?php echo esc_html( $listing->post_title ); ?></a></td>
                                <td><?php echo esc_html( ucfirst( $listing->post_status ) ); ?></td>
                                <td><?php echo esc_html( get_the_date( '', $listing->ID ) ); ?></td>
                                <td>
                                    <a href="<?php echo esc_url( get_edit_post_link( $listing->ID ) ); ?>"><?php esc_html_e( 'Edit', 'connectpro' ); ?></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <div class="quick-actions">
            <h3><?php esc_html_e( 'Quick Actions', 'connectpro' ); ?></h3>
            <a href="<?php echo esc_url( home_url( '/submit-listing' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Add New Listing', 'connectpro' ); ?></a>
            <a href="<?php echo esc_url( get_permalink() . '?tab=profile' ); ?>" class="btn btn-secondary"><?php esc_html_e( 'Edit Profile', 'connectpro' ); ?></a>
        </div>
    </div>
    <?php
}

/**
 * Dashboard my listings tab
 */
function connectpro_dashboard_my_listings() {
    $user_id = get_current_user_id();
    $paged = isset( $_GET['paged'] ) ? intval( $_GET['paged'] ) : 1;

    $args = array(
        'post_type'      => 'listing',
        'author'         => $user_id,
        'posts_per_page' => 10,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => array( 'publish', 'pending', 'draft' ),
    );

    $query = new WP_Query( $args );

    ?>
    <div class="dashboard-my-listings">
        <div class="dashboard-header">
            <h2><?php esc_html_e( 'My Listings', 'connectpro' ); ?></h2>
            <a href="<?php echo esc_url( home_url( '/submit-listing' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Add New Listing', 'connectpro' ); ?></a>
        </div>

        <?php if ( $query->have_posts() ) : ?>
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Title', 'connectpro' ); ?></th>
                        <th><?php esc_html_e( 'Price', 'connectpro' ); ?></th>
                        <th><?php esc_html_e( 'Status', 'connectpro' ); ?></th>
                        <th><?php esc_html_e( 'Date', 'connectpro' ); ?></th>
                        <th><?php esc_html_e( 'Actions', 'connectpro' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ( $query->have_posts() ) :
                        $query->the_post();
                        $price = connectpro_get_listing_price( get_the_ID() );
                        ?>
                        <tr>
                            <td><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></td>
                            <td><?php echo esc_html( $price ?: '-' ); ?></td>
                            <td><?php echo esc_html( ucfirst( get_post_status() ) ); ?></td>
                            <td><?php echo esc_html( get_the_date() ); ?></td>
                            <td>
                                <a href="<?php echo esc_url( get_edit_post_link() ); ?>"><?php esc_html_e( 'Edit', 'connectpro' ); ?></a> |
                                <a href="<?php the_permalink(); ?>"><?php esc_html_e( 'View', 'connectpro' ); ?></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <?php
            // Pagination
            if ( $query->max_num_pages > 1 ) {
                echo '<div class="dashboard-pagination">';
                echo paginate_links( array(
                    'total'   => $query->max_num_pages,
                    'current' => $paged,
                    'base'    => add_query_arg( 'paged', '%#%' ),
                ) );
                echo '</div>';
            }
            ?>

        <?php else : ?>
            <p><?php esc_html_e( 'You haven\'t added any listings yet.', 'connectpro' ); ?></p>
            <a href="<?php echo esc_url( home_url( '/submit-listing' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Add Your First Listing', 'connectpro' ); ?></a>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>
    </div>
    <?php
}

/**
 * Dashboard messages tab
 */
function connectpro_dashboard_messages() {
    ?>
    <div class="dashboard-messages">
        <h2><?php esc_html_e( 'Messages', 'connectpro' ); ?></h2>
        <p><?php esc_html_e( 'Messaging system coming soon...', 'connectpro' ); ?></p>
    </div>
    <?php
}

/**
 * Dashboard edit profile tab
 */
function connectpro_dashboard_edit_profile() {
    $current_user = wp_get_current_user();

    // Handle form submission
    if ( isset( $_POST['update_profile'] ) && wp_verify_nonce( $_POST['profile_nonce'], 'update_user_profile' ) ) {
        $user_id = $current_user->ID;

        // Update user data
        $user_data = array(
            'ID'           => $user_id,
            'first_name'   => sanitize_text_field( $_POST['first_name'] ),
            'last_name'    => sanitize_text_field( $_POST['last_name'] ),
            'display_name' => sanitize_text_field( $_POST['display_name'] ),
            'user_email'   => sanitize_email( $_POST['user_email'] ),
            'description'  => sanitize_textarea_field( $_POST['description'] ),
        );

        wp_update_user( $user_data );

        // Update custom meta
        update_user_meta( $user_id, 'phone', sanitize_text_field( $_POST['phone'] ) );
        update_user_meta( $user_id, 'address', sanitize_text_field( $_POST['address'] ) );

        echo '<div class="notice notice-success"><p>' . esc_html__( 'Profile updated successfully!', 'connectpro' ) . '</p></div>';
    }

    ?>
    <div class="dashboard-edit-profile">
        <h2><?php esc_html_e( 'Edit Profile', 'connectpro' ); ?></h2>

        <form method="post" class="profile-form">
            <?php wp_nonce_field( 'update_user_profile', 'profile_nonce' ); ?>

            <div class="form-row">
                <label for="first_name"><?php esc_html_e( 'First Name', 'connectpro' ); ?></label>
                <input type="text" name="first_name" id="first_name" value="<?php echo esc_attr( $current_user->first_name ); ?>">
            </div>

            <div class="form-row">
                <label for="last_name"><?php esc_html_e( 'Last Name', 'connectpro' ); ?></label>
                <input type="text" name="last_name" id="last_name" value="<?php echo esc_attr( $current_user->last_name ); ?>">
            </div>

            <div class="form-row">
                <label for="display_name"><?php esc_html_e( 'Display Name', 'connectpro' ); ?></label>
                <input type="text" name="display_name" id="display_name" value="<?php echo esc_attr( $current_user->display_name ); ?>">
            </div>

            <div class="form-row">
                <label for="user_email"><?php esc_html_e( 'Email', 'connectpro' ); ?></label>
                <input type="email" name="user_email" id="user_email" value="<?php echo esc_attr( $current_user->user_email ); ?>">
            </div>

            <div class="form-row">
                <label for="phone"><?php esc_html_e( 'Phone', 'connectpro' ); ?></label>
                <input type="tel" name="phone" id="phone" value="<?php echo esc_attr( get_user_meta( $current_user->ID, 'phone', true ) ); ?>">
            </div>

            <div class="form-row">
                <label for="address"><?php esc_html_e( 'Address', 'connectpro' ); ?></label>
                <input type="text" name="address" id="address" value="<?php echo esc_attr( get_user_meta( $current_user->ID, 'address', true ) ); ?>">
            </div>

            <div class="form-row">
                <label for="description"><?php esc_html_e( 'Bio', 'connectpro' ); ?></label>
                <textarea name="description" id="description" rows="5"><?php echo esc_textarea( $current_user->description ); ?></textarea>
            </div>

            <button type="submit" name="update_profile" class="btn btn-primary"><?php esc_html_e( 'Update Profile', 'connectpro' ); ?></button>
        </form>
    </div>
    <?php
}

/**
 * Dashboard shortcode
 */
function connectpro_dashboard_shortcode() {
    ob_start();
    connectpro_user_dashboard_content();
    return ob_get_clean();
}
add_shortcode( 'user_dashboard', 'connectpro_dashboard_shortcode' );
