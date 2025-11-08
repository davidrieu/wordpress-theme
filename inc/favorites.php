<?php
/**
 * Favorites/Wishlist System
 *
 * @package ConnectPro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Toggle favorite listing
 */
function connectpro_toggle_favorite() {
    check_ajax_referer( 'connectpro_nonce', 'nonce' );

    if ( ! is_user_logged_in() ) {
        wp_send_json_error( array(
            'message' => __( 'You must be logged in to add favorites.', 'connectpro' ),
        ) );
    }

    $listing_id = isset( $_POST['listing_id'] ) ? intval( $_POST['listing_id'] ) : 0;

    if ( ! $listing_id || get_post_type( $listing_id ) !== 'listing' ) {
        wp_send_json_error( array(
            'message' => __( 'Invalid listing ID.', 'connectpro' ),
        ) );
    }

    $user_id = get_current_user_id();
    $favorites = get_user_meta( $user_id, 'listing_favorites', true );

    if ( ! is_array( $favorites ) ) {
        $favorites = array();
    }

    $key = array_search( $listing_id, $favorites );

    if ( $key !== false ) {
        // Remove from favorites
        unset( $favorites[ $key ] );
        $favorites = array_values( $favorites );
        $favorited = false;
        $message = __( 'Removed from favorites.', 'connectpro' );
    } else {
        // Add to favorites
        $favorites[] = $listing_id;
        $favorited = true;
        $message = __( 'Added to favorites.', 'connectpro' );
    }

    update_user_meta( $user_id, 'listing_favorites', $favorites );

    wp_send_json_success( array(
        'favorited' => $favorited,
        'message'   => $message,
        'count'     => count( $favorites ),
    ) );
}
add_action( 'wp_ajax_toggle_favorite', 'connectpro_toggle_favorite' );

/**
 * Get user favorites
 */
function connectpro_get_user_favorites( $user_id = null ) {
    if ( ! $user_id ) {
        $user_id = get_current_user_id();
    }

    if ( ! $user_id ) {
        return array();
    }

    $favorites = get_user_meta( $user_id, 'listing_favorites', true );

    if ( ! is_array( $favorites ) ) {
        return array();
    }

    return $favorites;
}

/**
 * Display favorites page
 */
function connectpro_favorites_page_content() {
    if ( ! is_user_logged_in() ) {
        echo '<p>' . esc_html__( 'Please log in to view your favorites.', 'connectpro' ) . '</p>';
        return;
    }

    $favorites = connectpro_get_user_favorites();

    if ( empty( $favorites ) ) {
        echo '<div class="no-favorites">';
        echo '<h2>' . esc_html__( 'No Favorites Yet', 'connectpro' ) . '</h2>';
        echo '<p>' . esc_html__( 'You haven\'t added any listings to your favorites yet.', 'connectpro' ) . '</p>';
        echo '<a href="' . esc_url( home_url( '/listings' ) ) . '" class="btn btn-primary">' . esc_html__( 'Browse Listings', 'connectpro' ) . '</a>';
        echo '</div>';
        return;
    }

    $args = array(
        'post_type'      => 'listing',
        'post__in'       => $favorites,
        'posts_per_page' => -1,
        'orderby'        => 'post__in',
    );

    $query = new WP_Query( $args );

    if ( $query->have_posts() ) {
        echo '<div class="favorites-grid listings-grid">';

        while ( $query->have_posts() ) {
            $query->the_post();
            get_template_part( 'template-parts/listings/listing-card' );
        }

        echo '</div>';

        wp_reset_postdata();
    }
}

/**
 * Remove favorite shortcode
 */
function connectpro_favorites_shortcode() {
    ob_start();
    connectpro_favorites_page_content();
    return ob_get_clean();
}
add_shortcode( 'connectpro_favorites', 'connectpro_favorites_shortcode' );

/**
 * Add favorites menu item count
 */
function connectpro_add_favorites_count_to_menu( $items, $args ) {
    if ( $args->theme_location === 'primary' && is_user_logged_in() ) {
        $count = connectpro_get_favorites_count();
        $items = str_replace( '</a>', ' <span class="favorites-count">' . $count . '</span></a>', $items );
    }

    return $items;
}
// Uncomment to enable: add_filter( 'wp_nav_menu_items', 'connectpro_add_favorites_count_to_menu', 10, 2 );
