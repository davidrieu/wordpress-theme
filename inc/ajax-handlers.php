<?php
/**
 * AJAX Handlers
 *
 * @package ConnectPro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Load more listings
 */
function connectpro_ajax_load_more_listings() {
    check_ajax_referer( 'connectpro_nonce', 'nonce' );

    $paged = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
    $posts_per_page = isset( $_POST['per_page'] ) ? intval( $_POST['per_page'] ) : 12;

    $args = array(
        'post_type'      => 'listing',
        'posts_per_page' => $posts_per_page,
        'paged'          => $paged,
        'post_status'    => 'publish',
    );

    $query = new WP_Query( $args );

    ob_start();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            get_template_part( 'template-parts/listings/listing-card' );
        }
    }

    $html = ob_get_clean();
    wp_reset_postdata();

    wp_send_json_success( array(
        'html'      => $html,
        'max_pages' => $query->max_num_pages,
    ) );
}
add_action( 'wp_ajax_load_more_listings', 'connectpro_ajax_load_more_listings' );
add_action( 'wp_ajax_nopriv_load_more_listings', 'connectpro_ajax_load_more_listings' );

/**
 * Contact listing author
 */
function connectpro_ajax_contact_author() {
    check_ajax_referer( 'connectpro_nonce', 'nonce' );

    $listing_id = isset( $_POST['listing_id'] ) ? intval( $_POST['listing_id'] ) : 0;
    $name = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
    $email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
    $phone = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
    $message = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';

    if ( ! $listing_id || ! $name || ! $email || ! $message ) {
        wp_send_json_error( array(
            'message' => __( 'All fields are required.', 'connectpro' ),
        ) );
    }

    // Get listing author
    $author_id = get_post_field( 'post_author', $listing_id );
    $author_email = get_the_author_meta( 'user_email', $author_id );

    if ( ! $author_email ) {
        wp_send_json_error( array(
            'message' => __( 'Unable to contact the listing author.', 'connectpro' ),
        ) );
    }

    // Prepare email
    $subject = sprintf( __( 'Inquiry about: %s', 'connectpro' ), get_the_title( $listing_id ) );
    $body = sprintf(
        __( 'You have received a new inquiry about your listing "%1$s"

From: %2$s
Email: %3$s
Phone: %4$s

Message:
%5$s

Listing URL: %6$s', 'connectpro' ),
        get_the_title( $listing_id ),
        $name,
        $email,
        $phone,
        $message,
        get_permalink( $listing_id )
    );

    // Send email
    $sent = wp_mail( $author_email, $subject, $body, array(
        'Reply-To: ' . $email,
    ) );

    if ( $sent ) {
        wp_send_json_success( array(
            'message' => __( 'Your message has been sent successfully!', 'connectpro' ),
        ) );
    } else {
        wp_send_json_error( array(
            'message' => __( 'Failed to send message. Please try again.', 'connectpro' ),
        ) );
    }
}
add_action( 'wp_ajax_contact_author', 'connectpro_ajax_contact_author' );
add_action( 'wp_ajax_nopriv_contact_author', 'connectpro_ajax_contact_author' );

/**
 * Delete listing
 */
function connectpro_ajax_delete_listing() {
    check_ajax_referer( 'connectpro_nonce', 'nonce' );

    if ( ! is_user_logged_in() ) {
        wp_send_json_error( array(
            'message' => __( 'You must be logged in to delete listings.', 'connectpro' ),
        ) );
    }

    $listing_id = isset( $_POST['listing_id'] ) ? intval( $_POST['listing_id'] ) : 0;
    $user_id = get_current_user_id();

    if ( ! $listing_id ) {
        wp_send_json_error( array(
            'message' => __( 'Invalid listing ID.', 'connectpro' ),
        ) );
    }

    // Check if user is the author
    $author_id = get_post_field( 'post_author', $listing_id );

    if ( $author_id != $user_id && ! current_user_can( 'delete_posts' ) ) {
        wp_send_json_error( array(
            'message' => __( 'You don\'t have permission to delete this listing.', 'connectpro' ),
        ) );
    }

    // Delete listing
    $deleted = wp_delete_post( $listing_id, true );

    if ( $deleted ) {
        wp_send_json_success( array(
            'message' => __( 'Listing deleted successfully.', 'connectpro' ),
        ) );
    } else {
        wp_send_json_error( array(
            'message' => __( 'Failed to delete listing.', 'connectpro' ),
        ) );
    }
}
add_action( 'wp_ajax_delete_listing', 'connectpro_ajax_delete_listing' );

/**
 * Update listing status
 */
function connectpro_ajax_update_listing_status() {
    check_ajax_referer( 'connectpro_nonce', 'nonce' );

    if ( ! is_user_logged_in() ) {
        wp_send_json_error( array(
            'message' => __( 'You must be logged in.', 'connectpro' ),
        ) );
    }

    $listing_id = isset( $_POST['listing_id'] ) ? intval( $_POST['listing_id'] ) : 0;
    $status = isset( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : '';
    $user_id = get_current_user_id();

    if ( ! $listing_id || ! $status ) {
        wp_send_json_error( array(
            'message' => __( 'Invalid parameters.', 'connectpro' ),
        ) );
    }

    // Check if user is the author
    $author_id = get_post_field( 'post_author', $listing_id );

    if ( $author_id != $user_id && ! current_user_can( 'edit_posts' ) ) {
        wp_send_json_error( array(
            'message' => __( 'You don\'t have permission to update this listing.', 'connectpro' ),
        ) );
    }

    // Update status
    $updated = wp_update_post( array(
        'ID'          => $listing_id,
        'post_status' => $status,
    ) );

    if ( $updated && ! is_wp_error( $updated ) ) {
        wp_send_json_success( array(
            'message' => __( 'Listing status updated successfully.', 'connectpro' ),
        ) );
    } else {
        wp_send_json_error( array(
            'message' => __( 'Failed to update listing status.', 'connectpro' ),
        ) );
    }
}
add_action( 'wp_ajax_update_listing_status', 'connectpro_ajax_update_listing_status' );

/**
 * Get listing statistics
 */
function connectpro_ajax_get_listing_stats() {
    check_ajax_referer( 'connectpro_nonce', 'nonce' );

    if ( ! is_user_logged_in() ) {
        wp_send_json_error();
    }

    $listing_id = isset( $_POST['listing_id'] ) ? intval( $_POST['listing_id'] ) : 0;

    if ( ! $listing_id ) {
        wp_send_json_error();
    }

    $views = get_post_meta( $listing_id, 'listing_views', true ) ?: 0;
    $favorites = get_post_meta( $listing_id, 'listing_favorites_count', true ) ?: 0;
    $inquiries = get_post_meta( $listing_id, 'listing_inquiries', true ) ?: 0;

    wp_send_json_success( array(
        'views'     => $views,
        'favorites' => $favorites,
        'inquiries' => $inquiries,
    ) );
}
add_action( 'wp_ajax_get_listing_stats', 'connectpro_ajax_get_listing_stats' );

/**
 * Track listing views
 */
function connectpro_track_listing_view() {
    if ( is_singular( 'listing' ) ) {
        global $post;

        $views = get_post_meta( $post->ID, 'listing_views', true );
        $views = $views ? intval( $views ) + 1 : 1;

        update_post_meta( $post->ID, 'listing_views', $views );
    }
}
add_action( 'wp_head', 'connectpro_track_listing_view' );
