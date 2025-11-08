<?php
/**
 * Review and Rating System
 *
 * @package ConnectPro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Create Reviews Table
 */
function connectpro_create_reviews_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'connectpro_reviews';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        listing_id bigint(20) NOT NULL,
        user_id bigint(20) NOT NULL,
        rating decimal(2,1) NOT NULL,
        title varchar(255) DEFAULT NULL,
        comment text NOT NULL,
        date datetime DEFAULT CURRENT_TIMESTAMP,
        status varchar(20) DEFAULT 'approved',
        helpful_count int(11) DEFAULT 0,
        PRIMARY KEY  (id),
        KEY listing_id (listing_id),
        KEY user_id (user_id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );
}
add_action( 'after_switch_theme', 'connectpro_create_reviews_table' );

/**
 * Add Review
 */
function connectpro_add_review( $listing_id, $user_id, $rating, $title, $comment ) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'connectpro_reviews';

    // Check if user already reviewed this listing
    $existing_review = $wpdb->get_var( $wpdb->prepare(
        "SELECT id FROM $table_name WHERE listing_id = %d AND user_id = %d",
        $listing_id,
        $user_id
    ) );

    if ( $existing_review ) {
        return new WP_Error( 'already_reviewed', __( 'You have already reviewed this listing.', 'connectpro' ) );
    }

    $result = $wpdb->insert(
        $table_name,
        array(
            'listing_id' => $listing_id,
            'user_id' => $user_id,
            'rating' => $rating,
            'title' => $title,
            'comment' => $comment,
            'date' => current_time( 'mysql' ),
            'status' => 'approved',
        ),
        array( '%d', '%d', '%f', '%s', '%s', '%s', '%s' )
    );

    if ( $result ) {
        $review_id = $wpdb->insert_id;

        // Update listing average rating
        connectpro_update_listing_rating( $listing_id );

        do_action( 'connectpro_review_added', $review_id, $listing_id, $user_id );

        return $review_id;
    }

    return new WP_Error( 'review_failed', __( 'Failed to add review.', 'connectpro' ) );
}

/**
 * Get Listing Reviews
 */
function connectpro_get_listing_reviews( $listing_id, $limit = -1, $offset = 0 ) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'connectpro_reviews';

    $query = $wpdb->prepare(
        "SELECT * FROM $table_name WHERE listing_id = %d AND status = 'approved' ORDER BY date DESC",
        $listing_id
    );

    if ( $limit > 0 ) {
        $query .= $wpdb->prepare( " LIMIT %d OFFSET %d", $limit, $offset );
    }

    return $wpdb->get_results( $query );
}

/**
 * Get Author Reviews
 */
function connectpro_get_author_reviews( $author_id, $limit = -1 ) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'connectpro_reviews';
    $posts_table = $wpdb->prefix . 'posts';

    $query = $wpdb->prepare(
        "SELECT r.* FROM $table_name r
        INNER JOIN $posts_table p ON r.listing_id = p.ID
        WHERE p.post_author = %d AND r.status = 'approved'
        ORDER BY r.date DESC",
        $author_id
    );

    if ( $limit > 0 ) {
        $query .= $wpdb->prepare( " LIMIT %d", $limit );
    }

    return $wpdb->get_results( $query );
}

/**
 * Get Author Reviews Count
 */
function connectpro_get_author_reviews_count( $author_id ) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'connectpro_reviews';
    $posts_table = $wpdb->prefix . 'posts';

    return (int) $wpdb->get_var( $wpdb->prepare(
        "SELECT COUNT(*) FROM $table_name r
        INNER JOIN $posts_table p ON r.listing_id = p.ID
        WHERE p.post_author = %d AND r.status = 'approved'",
        $author_id
    ) );
}

/**
 * Get Author Average Rating
 */
function connectpro_get_author_average_rating( $author_id ) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'connectpro_reviews';
    $posts_table = $wpdb->prefix . 'posts';

    $average = $wpdb->get_var( $wpdb->prepare(
        "SELECT AVG(r.rating) FROM $table_name r
        INNER JOIN $posts_table p ON r.listing_id = p.ID
        WHERE p.post_author = %d AND r.status = 'approved'",
        $author_id
    ) );

    return $average ? round( $average, 1 ) : 0;
}

/**
 * Update Listing Rating
 */
function connectpro_update_listing_rating( $listing_id ) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'connectpro_reviews';

    $stats = $wpdb->get_row( $wpdb->prepare(
        "SELECT AVG(rating) as average, COUNT(*) as count FROM $table_name WHERE listing_id = %d AND status = 'approved'",
        $listing_id
    ) );

    if ( $stats ) {
        update_post_meta( $listing_id, '_listing_rating', round( $stats->average, 1 ) );
        update_post_meta( $listing_id, '_listing_reviews_count', $stats->count );
    }
}

/**
 * Get Listing Rating Stats
 */
function connectpro_get_listing_rating_stats( $listing_id ) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'connectpro_reviews';

    $stats = array();

    for ( $i = 5; $i >= 1; $i-- ) {
        $count = $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE listing_id = %d AND rating >= %d AND rating < %d AND status = 'approved'",
            $listing_id,
            $i,
            $i + 1
        ) );

        $stats[ $i ] = (int) $count;
    }

    return $stats;
}

/**
 * Display Rating Stars
 */
function connectpro_display_rating( $rating, $show_number = true ) {
    $rating = floatval( $rating );
    $full_stars = floor( $rating );
    $half_star = ( $rating - $full_stars ) >= 0.5 ? 1 : 0;
    $empty_stars = 5 - $full_stars - $half_star;

    $output = '<div class="listing-rating">';
    $output .= '<div class="stars">';

    // Full stars
    for ( $i = 0; $i < $full_stars; $i++ ) {
        $output .= '<i class="fas fa-star"></i>';
    }

    // Half star
    if ( $half_star ) {
        $output .= '<i class="fas fa-star-half-alt"></i>';
    }

    // Empty stars
    for ( $i = 0; $i < $empty_stars; $i++ ) {
        $output .= '<i class="far fa-star"></i>';
    }

    $output .= '</div>';

    if ( $show_number ) {
        $output .= '<span class="rating-number">' . number_format( $rating, 1 ) . '</span>';
    }

    $output .= '</div>';

    echo $output;
}

/**
 * AJAX: Submit Review
 */
function connectpro_submit_review() {
    check_ajax_referer( 'connectpro_nonce', 'nonce' );

    if ( ! is_user_logged_in() ) {
        wp_send_json_error( array( 'message' => __( 'You must be logged in to leave a review.', 'connectpro' ) ) );
    }

    $listing_id = intval( $_POST['listing_id'] );
    $rating = floatval( $_POST['rating'] );
    $title = sanitize_text_field( $_POST['title'] );
    $comment = sanitize_textarea_field( $_POST['comment'] );
    $user_id = get_current_user_id();

    // Validate
    if ( ! $listing_id || $rating < 1 || $rating > 5 || empty( $comment ) ) {
        wp_send_json_error( array( 'message' => __( 'Please fill in all required fields.', 'connectpro' ) ) );
    }

    // Check if user is the listing author
    $listing_author = get_post_field( 'post_author', $listing_id );
    if ( $user_id == $listing_author ) {
        wp_send_json_error( array( 'message' => __( 'You cannot review your own listing.', 'connectpro' ) ) );
    }

    $result = connectpro_add_review( $listing_id, $user_id, $rating, $title, $comment );

    if ( is_wp_error( $result ) ) {
        wp_send_json_error( array( 'message' => $result->get_error_message() ) );
    }

    wp_send_json_success( array(
        'message' => __( 'Review submitted successfully!', 'connectpro' ),
        'review_id' => $result,
    ) );
}
add_action( 'wp_ajax_submit_review', 'connectpro_submit_review' );

/**
 * AJAX: Mark Review Helpful
 */
function connectpro_mark_review_helpful() {
    check_ajax_referer( 'connectpro_nonce', 'nonce' );

    $review_id = intval( $_POST['review_id'] );

    if ( ! $review_id ) {
        wp_send_json_error();
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'connectpro_reviews';

    $wpdb->query( $wpdb->prepare(
        "UPDATE $table_name SET helpful_count = helpful_count + 1 WHERE id = %d",
        $review_id
    ) );

    $new_count = $wpdb->get_var( $wpdb->prepare(
        "SELECT helpful_count FROM $table_name WHERE id = %d",
        $review_id
    ) );

    wp_send_json_success( array(
        'count' => $new_count,
    ) );
}
add_action( 'wp_ajax_mark_review_helpful', 'connectpro_mark_review_helpful' );
add_action( 'wp_ajax_nopriv_mark_review_helpful', 'connectpro_mark_review_helpful' );

/**
 * Display Review Form
 */
function connectpro_review_form( $listing_id ) {
    if ( ! is_user_logged_in() ) {
        echo '<p>' . sprintf(
            __( 'You must be <a href="%s">logged in</a> to leave a review.', 'connectpro' ),
            wp_login_url( get_permalink() )
        ) . '</p>';
        return;
    }

    // Check if user already reviewed
    global $wpdb;
    $table_name = $wpdb->prefix . 'connectpro_reviews';
    $existing_review = $wpdb->get_var( $wpdb->prepare(
        "SELECT id FROM $table_name WHERE listing_id = %d AND user_id = %d",
        $listing_id,
        get_current_user_id()
    ) );

    if ( $existing_review ) {
        echo '<p>' . __( 'You have already reviewed this listing.', 'connectpro' ) . '</p>';
        return;
    }

    // Check if user is the listing author
    $listing_author = get_post_field( 'post_author', $listing_id );
    if ( get_current_user_id() == $listing_author ) {
        echo '<p>' . __( 'You cannot review your own listing.', 'connectpro' ) . '</p>';
        return;
    }
    ?>

    <div class="review-form-wrapper">
        <h3><?php esc_html_e( 'Leave a Review', 'connectpro' ); ?></h3>

        <form id="review-form" class="review-form" data-listing-id="<?php echo esc_attr( $listing_id ); ?>">
            <div class="form-group">
                <label><?php esc_html_e( 'Your Rating', 'connectpro' ); ?> *</label>
                <div class="star-rating-input">
                    <input type="radio" id="star5" name="rating" value="5" required>
                    <label for="star5"><i class="fas fa-star"></i></label>

                    <input type="radio" id="star4" name="rating" value="4">
                    <label for="star4"><i class="fas fa-star"></i></label>

                    <input type="radio" id="star3" name="rating" value="3">
                    <label for="star3"><i class="fas fa-star"></i></label>

                    <input type="radio" id="star2" name="rating" value="2">
                    <label for="star2"><i class="fas fa-star"></i></label>

                    <input type="radio" id="star1" name="rating" value="1">
                    <label for="star1"><i class="fas fa-star"></i></label>
                </div>
            </div>

            <div class="form-group">
                <label for="review-title"><?php esc_html_e( 'Review Title', 'connectpro' ); ?></label>
                <input type="text" id="review-title" name="title" class="form-control" placeholder="<?php esc_attr_e( 'Summarize your experience', 'connectpro' ); ?>">
            </div>

            <div class="form-group">
                <label for="review-comment"><?php esc_html_e( 'Your Review', 'connectpro' ); ?> *</label>
                <textarea id="review-comment" name="comment" class="form-control" rows="5" placeholder="<?php esc_attr_e( 'Share your experience...', 'connectpro' ); ?>" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                <?php esc_html_e( 'Submit Review', 'connectpro' ); ?>
            </button>
        </form>
    </div>

    <style>
        .star-rating-input {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 5px;
        }

        .star-rating-input input {
            display: none;
        }

        .star-rating-input label {
            cursor: pointer;
            font-size: 24px;
            color: #ddd;
            transition: color 0.2s;
        }

        .star-rating-input label:hover,
        .star-rating-input label:hover ~ label,
        .star-rating-input input:checked ~ label {
            color: #ffa500;
        }
    </style>
    <?php
}

/**
 * Display Reviews List
 */
function connectpro_display_reviews( $listing_id ) {
    $reviews = connectpro_get_listing_reviews( $listing_id );
    $total_reviews = count( $reviews );
    $rating_stats = connectpro_get_listing_rating_stats( $listing_id );
    $average_rating = get_post_meta( $listing_id, '_listing_rating', true );

    if ( empty( $reviews ) ) {
        echo '<p>' . __( 'No reviews yet. Be the first to review!', 'connectpro' ) . '</p>';
        return;
    }
    ?>

    <div class="reviews-section">
        <div class="reviews-summary">
            <div class="average-rating">
                <div class="rating-number"><?php echo esc_html( number_format( $average_rating, 1 ) ); ?></div>
                <?php connectpro_display_rating( $average_rating, false ); ?>
                <div class="total-reviews"><?php echo esc_html( $total_reviews ); ?> <?php esc_html_e( 'reviews', 'connectpro' ); ?></div>
            </div>

            <div class="rating-breakdown">
                <?php foreach ( $rating_stats as $stars => $count ) : ?>
                    <?php $percentage = $total_reviews > 0 ? ( $count / $total_reviews ) * 100 : 0; ?>
                    <div class="rating-bar">
                        <span class="star-label"><?php echo esc_html( $stars ); ?> <i class="fas fa-star"></i></span>
                        <div class="bar-container">
                            <div class="bar-fill" style="width: <?php echo esc_attr( $percentage ); ?>%;"></div>
                        </div>
                        <span class="count"><?php echo esc_html( $count ); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="reviews-list">
            <?php foreach ( $reviews as $review ) : ?>
                <?php
                $reviewer = get_userdata( $review->user_id );
                ?>
                <div class="review-item" data-review-id="<?php echo esc_attr( $review->id ); ?>">
                    <div class="review-header">
                        <div class="reviewer-avatar">
                            <?php echo get_avatar( $review->user_id, 60 ); ?>
                        </div>
                        <div class="reviewer-info">
                            <h4><?php echo esc_html( $reviewer->display_name ); ?></h4>
                            <div class="review-meta">
                                <?php connectpro_display_rating( $review->rating, false ); ?>
                                <span class="review-date"><?php echo esc_html( human_time_diff( strtotime( $review->date ), current_time( 'timestamp' ) ) ); ?> <?php esc_html_e( 'ago', 'connectpro' ); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="review-content">
                        <?php if ( $review->title ) : ?>
                            <h5><?php echo esc_html( $review->title ); ?></h5>
                        <?php endif; ?>
                        <p><?php echo esc_html( $review->comment ); ?></p>
                    </div>

                    <div class="review-footer">
                        <button class="mark-helpful" data-review-id="<?php echo esc_attr( $review->id ); ?>">
                            <i class="fas fa-thumbs-up"></i>
                            <?php esc_html_e( 'Helpful', 'connectpro' ); ?>
                            <?php if ( $review->helpful_count > 0 ) : ?>
                                <span class="helpful-count">(<?php echo esc_html( $review->helpful_count ); ?>)</span>
                            <?php endif; ?>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

/**
 * Enqueue Review Scripts
 */
function connectpro_enqueue_review_scripts() {
    if ( is_singular( 'listing' ) || is_author() ) {
        wp_enqueue_script(
            'connectpro-reviews',
            CONNECTPRO_THEME_URI . '/assets/js/reviews.js',
            array( 'jquery' ),
            CONNECTPRO_VERSION,
            true
        );

        wp_localize_script( 'connectpro-reviews', 'connectproReviews', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'connectpro_nonce' ),
        ) );
    }
}
add_action( 'wp_enqueue_scripts', 'connectpro_enqueue_review_scripts' );
