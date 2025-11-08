<?php
/**
 * Booking System
 *
 * @package ConnectPro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Booking Post Type
 */
function connectpro_register_booking_post_type() {
    $labels = array(
        'name'                  => _x( 'Bookings', 'Post Type General Name', 'connectpro' ),
        'singular_name'         => _x( 'Booking', 'Post Type Singular Name', 'connectpro' ),
        'menu_name'             => __( 'Bookings', 'connectpro' ),
        'name_admin_bar'        => __( 'Booking', 'connectpro' ),
        'archives'              => __( 'Booking Archives', 'connectpro' ),
        'attributes'            => __( 'Booking Attributes', 'connectpro' ),
        'parent_item_colon'     => __( 'Parent Booking:', 'connectpro' ),
        'all_items'             => __( 'All Bookings', 'connectpro' ),
        'add_new_item'          => __( 'Add New Booking', 'connectpro' ),
        'add_new'               => __( 'Add New', 'connectpro' ),
        'new_item'              => __( 'New Booking', 'connectpro' ),
        'edit_item'             => __( 'Edit Booking', 'connectpro' ),
        'update_item'           => __( 'Update Booking', 'connectpro' ),
        'view_item'             => __( 'View Booking', 'connectpro' ),
        'view_items'            => __( 'View Bookings', 'connectpro' ),
        'search_items'          => __( 'Search Booking', 'connectpro' ),
        'not_found'             => __( 'Not found', 'connectpro' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'connectpro' ),
    );

    $args = array(
        'label'                 => __( 'Booking', 'connectpro' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'author' ),
        'hierarchical'          => false,
        'public'                => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 26,
        'menu_icon'             => 'dashicons-calendar-alt',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );

    register_post_type( 'booking', $args );
}
add_action( 'init', 'connectpro_register_booking_post_type', 0 );

/**
 * Add Booking Meta Fields to Listing
 */
function connectpro_add_booking_meta_boxes() {
    add_meta_box(
        'connectpro_booking_settings',
        __( 'Booking Settings', 'connectpro' ),
        'connectpro_booking_settings_callback',
        'listing',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'connectpro_add_booking_meta_boxes' );

/**
 * Booking Settings Meta Box Callback
 */
function connectpro_booking_settings_callback( $post ) {
    wp_nonce_field( 'connectpro_booking_settings_nonce', 'connectpro_booking_settings_nonce' );

    $booking_enabled = get_post_meta( $post->ID, '_booking_enabled', true );
    $booking_type = get_post_meta( $post->ID, '_booking_type', true ) ?: 'instant';
    $price_per_day = get_post_meta( $post->ID, '_price_per_day', true );
    $price_per_hour = get_post_meta( $post->ID, '_price_per_hour', true );
    $min_booking_days = get_post_meta( $post->ID, '_min_booking_days', true ) ?: 1;
    $max_booking_days = get_post_meta( $post->ID, '_max_booking_days', true );
    $unavailable_dates = get_post_meta( $post->ID, '_unavailable_dates', true );
    $check_in_time = get_post_meta( $post->ID, '_check_in_time', true ) ?: '14:00';
    $check_out_time = get_post_meta( $post->ID, '_check_out_time', true ) ?: '11:00';
    ?>

    <div class="connectpro-meta-box">
        <div class="meta-field">
            <label>
                <input type="checkbox" name="booking_enabled" value="1" <?php checked( $booking_enabled, '1' ); ?>>
                <?php esc_html_e( 'Enable Booking for this Listing', 'connectpro' ); ?>
            </label>
        </div>

        <div class="meta-field">
            <label for="booking_type"><?php esc_html_e( 'Booking Type', 'connectpro' ); ?></label>
            <select name="booking_type" id="booking_type">
                <option value="instant" <?php selected( $booking_type, 'instant' ); ?>><?php esc_html_e( 'Instant Booking', 'connectpro' ); ?></option>
                <option value="request" <?php selected( $booking_type, 'request' ); ?>><?php esc_html_e( 'Request to Book', 'connectpro' ); ?></option>
            </select>
        </div>

        <div class="meta-field">
            <label for="price_per_day"><?php esc_html_e( 'Price per Day', 'connectpro' ); ?></label>
            <input type="number" name="price_per_day" id="price_per_day" value="<?php echo esc_attr( $price_per_day ); ?>" step="0.01" min="0">
        </div>

        <div class="meta-field">
            <label for="price_per_hour"><?php esc_html_e( 'Price per Hour (optional)', 'connectpro' ); ?></label>
            <input type="number" name="price_per_hour" id="price_per_hour" value="<?php echo esc_attr( $price_per_hour ); ?>" step="0.01" min="0">
        </div>

        <div class="meta-field">
            <label for="min_booking_days"><?php esc_html_e( 'Minimum Booking Days', 'connectpro' ); ?></label>
            <input type="number" name="min_booking_days" id="min_booking_days" value="<?php echo esc_attr( $min_booking_days ); ?>" min="1">
        </div>

        <div class="meta-field">
            <label for="max_booking_days"><?php esc_html_e( 'Maximum Booking Days (optional)', 'connectpro' ); ?></label>
            <input type="number" name="max_booking_days" id="max_booking_days" value="<?php echo esc_attr( $max_booking_days ); ?>" min="1">
        </div>

        <div class="meta-field">
            <label for="check_in_time"><?php esc_html_e( 'Check-in Time', 'connectpro' ); ?></label>
            <input type="time" name="check_in_time" id="check_in_time" value="<?php echo esc_attr( $check_in_time ); ?>">
        </div>

        <div class="meta-field">
            <label for="check_out_time"><?php esc_html_e( 'Check-out Time', 'connectpro' ); ?></label>
            <input type="time" name="check_out_time" id="check_out_time" value="<?php echo esc_attr( $check_out_time ); ?>">
        </div>

        <div class="meta-field">
            <label for="unavailable_dates"><?php esc_html_e( 'Unavailable Dates (one per line, YYYY-MM-DD)', 'connectpro' ); ?></label>
            <textarea name="unavailable_dates" id="unavailable_dates" rows="5"><?php echo esc_textarea( $unavailable_dates ); ?></textarea>
        </div>
    </div>

    <style>
        .connectpro-meta-box .meta-field {
            margin-bottom: 20px;
        }
        .connectpro-meta-box .meta-field label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .connectpro-meta-box .meta-field input[type="text"],
        .connectpro-meta-box .meta-field input[type="number"],
        .connectpro-meta-box .meta-field input[type="time"],
        .connectpro-meta-box .meta-field select,
        .connectpro-meta-box .meta-field textarea {
            width: 100%;
            max-width: 400px;
        }
    </style>
    <?php
}

/**
 * Save Booking Settings
 */
function connectpro_save_booking_settings( $post_id ) {
    if ( ! isset( $_POST['connectpro_booking_settings_nonce'] ) ) {
        return;
    }

    if ( ! wp_verify_nonce( $_POST['connectpro_booking_settings_nonce'], 'connectpro_booking_settings_nonce' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $booking_enabled = isset( $_POST['booking_enabled'] ) ? '1' : '0';
    update_post_meta( $post_id, '_booking_enabled', $booking_enabled );

    $fields = array(
        'booking_type',
        'price_per_day',
        'price_per_hour',
        'min_booking_days',
        'max_booking_days',
        'check_in_time',
        'check_out_time',
        'unavailable_dates',
    );

    foreach ( $fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, '_' . $field, sanitize_text_field( $_POST[ $field ] ) );
        }
    }
}
add_action( 'save_post_listing', 'connectpro_save_booking_settings' );

/**
 * AJAX: Create Booking
 */
function connectpro_create_booking() {
    check_ajax_referer( 'connectpro_nonce', 'nonce' );

    if ( ! is_user_logged_in() ) {
        wp_send_json_error( array( 'message' => __( 'You must be logged in to make a booking.', 'connectpro' ) ) );
    }

    $listing_id = intval( $_POST['listing_id'] );
    $check_in = sanitize_text_field( $_POST['check_in'] );
    $check_out = sanitize_text_field( $_POST['check_out'] );
    $guests = intval( $_POST['guests'] ) ?: 1;
    $message = sanitize_textarea_field( $_POST['message'] );

    // Validate dates
    $check_in_date = strtotime( $check_in );
    $check_out_date = strtotime( $check_out );

    if ( ! $check_in_date || ! $check_out_date || $check_in_date >= $check_out_date ) {
        wp_send_json_error( array( 'message' => __( 'Invalid dates.', 'connectpro' ) ) );
    }

    // Check if booking is enabled
    $booking_enabled = get_post_meta( $listing_id, '_booking_enabled', true );
    if ( $booking_enabled !== '1' ) {
        wp_send_json_error( array( 'message' => __( 'Booking is not available for this listing.', 'connectpro' ) ) );
    }

    // Check availability
    if ( ! connectpro_check_availability( $listing_id, $check_in, $check_out ) ) {
        wp_send_json_error( array( 'message' => __( 'Selected dates are not available.', 'connectpro' ) ) );
    }

    // Calculate total price
    $days = ( $check_out_date - $check_in_date ) / DAY_IN_SECONDS;
    $price_per_day = floatval( get_post_meta( $listing_id, '_price_per_day', true ) );
    $total_price = $days * $price_per_day;

    // Create booking
    $booking_data = array(
        'post_title'   => sprintf( __( 'Booking #%s', 'connectpro' ), time() ),
        'post_content' => $message,
        'post_status'  => 'pending',
        'post_type'    => 'booking',
        'post_author'  => get_current_user_id(),
    );

    $booking_id = wp_insert_post( $booking_data );

    if ( is_wp_error( $booking_id ) ) {
        wp_send_json_error( array( 'message' => __( 'Failed to create booking.', 'connectpro' ) ) );
    }

    // Save booking meta
    update_post_meta( $booking_id, '_listing_id', $listing_id );
    update_post_meta( $booking_id, '_check_in', $check_in );
    update_post_meta( $booking_id, '_check_out', $check_out );
    update_post_meta( $booking_id, '_guests', $guests );
    update_post_meta( $booking_id, '_total_price', $total_price );
    update_post_meta( $booking_id, '_booking_status', 'pending' );

    // Get listing owner
    $listing = get_post( $listing_id );
    update_post_meta( $booking_id, '_owner_id', $listing->post_author );

    // Send notification (you can implement email notification here)
    do_action( 'connectpro_booking_created', $booking_id, $listing_id );

    wp_send_json_success( array(
        'message' => __( 'Booking request submitted successfully!', 'connectpro' ),
        'booking_id' => $booking_id,
    ) );
}
add_action( 'wp_ajax_create_booking', 'connectpro_create_booking' );

/**
 * AJAX: Update Booking Status
 */
function connectpro_update_booking_status() {
    check_ajax_referer( 'connectpro_nonce', 'nonce' );

    if ( ! is_user_logged_in() ) {
        wp_send_json_error( array( 'message' => __( 'Unauthorized.', 'connectpro' ) ) );
    }

    $booking_id = intval( $_POST['booking_id'] );
    $status = sanitize_text_field( $_POST['status'] );

    $allowed_statuses = array( 'pending', 'confirmed', 'cancelled', 'completed' );
    if ( ! in_array( $status, $allowed_statuses ) ) {
        wp_send_json_error( array( 'message' => __( 'Invalid status.', 'connectpro' ) ) );
    }

    // Check permission
    $owner_id = get_post_meta( $booking_id, '_owner_id', true );
    $current_user_id = get_current_user_id();
    $booking_author = get_post_field( 'post_author', $booking_id );

    if ( $current_user_id != $owner_id && $current_user_id != $booking_author && ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => __( 'You do not have permission to update this booking.', 'connectpro' ) ) );
    }

    // Update status
    update_post_meta( $booking_id, '_booking_status', $status );
    wp_update_post( array(
        'ID' => $booking_id,
        'post_status' => $status === 'confirmed' ? 'publish' : 'pending',
    ) );

    do_action( 'connectpro_booking_status_updated', $booking_id, $status );

    wp_send_json_success( array(
        'message' => __( 'Booking status updated.', 'connectpro' ),
    ) );
}
add_action( 'wp_ajax_update_booking_status', 'connectpro_update_booking_status' );

/**
 * AJAX: Get Available Dates
 */
function connectpro_get_available_dates() {
    check_ajax_referer( 'connectpro_nonce', 'nonce' );

    $listing_id = intval( $_POST['listing_id'] );

    // Get unavailable dates from listing meta
    $unavailable_dates = get_post_meta( $listing_id, '_unavailable_dates', true );
    $unavailable_array = array_filter( explode( "\n", $unavailable_dates ) );

    // Get booked dates
    $bookings = get_posts( array(
        'post_type' => 'booking',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_listing_id',
                'value' => $listing_id,
            ),
            array(
                'key' => '_booking_status',
                'value' => array( 'confirmed', 'pending' ),
                'compare' => 'IN',
            ),
        ),
    ) );

    $booked_dates = array();
    foreach ( $bookings as $booking ) {
        $check_in = get_post_meta( $booking->ID, '_check_in', true );
        $check_out = get_post_meta( $booking->ID, '_check_out', true );

        $check_in_timestamp = strtotime( $check_in );
        $check_out_timestamp = strtotime( $check_out );

        for ( $i = $check_in_timestamp; $i < $check_out_timestamp; $i += DAY_IN_SECONDS ) {
            $booked_dates[] = date( 'Y-m-d', $i );
        }
    }

    $all_unavailable = array_merge( $unavailable_array, $booked_dates );
    $all_unavailable = array_unique( $all_unavailable );
    $all_unavailable = array_values( $all_unavailable );

    wp_send_json_success( array(
        'unavailable_dates' => $all_unavailable,
    ) );
}
add_action( 'wp_ajax_get_available_dates', 'connectpro_get_available_dates' );
add_action( 'wp_ajax_nopriv_get_available_dates', 'connectpro_get_available_dates' );

/**
 * Check Availability
 */
function connectpro_check_availability( $listing_id, $check_in, $check_out ) {
    // Get unavailable dates
    $unavailable_dates = get_post_meta( $listing_id, '_unavailable_dates', true );
    $unavailable_array = array_filter( explode( "\n", $unavailable_dates ) );

    $check_in_timestamp = strtotime( $check_in );
    $check_out_timestamp = strtotime( $check_out );

    // Check manual unavailable dates
    for ( $i = $check_in_timestamp; $i < $check_out_timestamp; $i += DAY_IN_SECONDS ) {
        $current_date = date( 'Y-m-d', $i );
        if ( in_array( $current_date, $unavailable_array ) ) {
            return false;
        }
    }

    // Check existing bookings
    $bookings = get_posts( array(
        'post_type' => 'booking',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_listing_id',
                'value' => $listing_id,
            ),
            array(
                'key' => '_booking_status',
                'value' => array( 'confirmed', 'pending' ),
                'compare' => 'IN',
            ),
        ),
    ) );

    foreach ( $bookings as $booking ) {
        $booked_check_in = strtotime( get_post_meta( $booking->ID, '_check_in', true ) );
        $booked_check_out = strtotime( get_post_meta( $booking->ID, '_check_out', true ) );

        // Check if dates overlap
        if ( $check_in_timestamp < $booked_check_out && $check_out_timestamp > $booked_check_in ) {
            return false;
        }
    }

    return true;
}

/**
 * Enqueue Booking Scripts
 */
function connectpro_enqueue_booking_scripts() {
    if ( is_singular( 'listing' ) || is_page_template( 'page-dashboard.php' ) ) {
        wp_enqueue_script(
            'connectpro-bookings',
            CONNECTPRO_THEME_URI . '/assets/js/bookings.js',
            array( 'jquery' ),
            CONNECTPRO_VERSION,
            true
        );

        wp_localize_script( 'connectpro-bookings', 'connectproBookings', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'connectpro_nonce' ),
        ) );
    }
}
add_action( 'wp_enqueue_scripts', 'connectpro_enqueue_booking_scripts' );

/**
 * Add Booking Columns to Admin
 */
function connectpro_booking_columns( $columns ) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = __( 'Booking', 'connectpro' );
    $new_columns['listing'] = __( 'Listing', 'connectpro' );
    $new_columns['customer'] = __( 'Customer', 'connectpro' );
    $new_columns['dates'] = __( 'Dates', 'connectpro' );
    $new_columns['status'] = __( 'Status', 'connectpro' );
    $new_columns['total'] = __( 'Total', 'connectpro' );
    $new_columns['date'] = __( 'Created', 'connectpro' );

    return $new_columns;
}
add_filter( 'manage_booking_posts_columns', 'connectpro_booking_columns' );

/**
 * Populate Booking Columns
 */
function connectpro_booking_column_content( $column, $post_id ) {
    switch ( $column ) {
        case 'listing':
            $listing_id = get_post_meta( $post_id, '_listing_id', true );
            if ( $listing_id ) {
                echo '<a href="' . get_edit_post_link( $listing_id ) . '">' . get_the_title( $listing_id ) . '</a>';
            }
            break;

        case 'customer':
            $author_id = get_post_field( 'post_author', $post_id );
            $author = get_userdata( $author_id );
            if ( $author ) {
                echo esc_html( $author->display_name );
            }
            break;

        case 'dates':
            $check_in = get_post_meta( $post_id, '_check_in', true );
            $check_out = get_post_meta( $post_id, '_check_out', true );
            echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $check_in ) ) );
            echo ' - ';
            echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $check_out ) ) );
            break;

        case 'status':
            $status = get_post_meta( $post_id, '_booking_status', true );
            $status_labels = array(
                'pending' => '<span style="color: #f59e0b;">' . __( 'Pending', 'connectpro' ) . '</span>',
                'confirmed' => '<span style="color: #10b981;">' . __( 'Confirmed', 'connectpro' ) . '</span>',
                'cancelled' => '<span style="color: #ef4444;">' . __( 'Cancelled', 'connectpro' ) . '</span>',
                'completed' => '<span style="color: #3b82f6;">' . __( 'Completed', 'connectpro' ) . '</span>',
            );
            echo isset( $status_labels[ $status ] ) ? $status_labels[ $status ] : esc_html( $status );
            break;

        case 'total':
            $total = get_post_meta( $post_id, '_total_price', true );
            echo '$' . number_format( floatval( $total ), 2 );
            break;
    }
}
add_action( 'manage_booking_posts_custom_column', 'connectpro_booking_column_content', 10, 2 );
