<?php
/**
 * Messaging System
 *
 * @package ConnectPro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Create messages table on theme activation
 */
function connectpro_create_messages_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'connectpro_messages';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        sender_id bigint(20) NOT NULL,
        recipient_id bigint(20) NOT NULL,
        listing_id bigint(20) DEFAULT NULL,
        subject varchar(255) NOT NULL,
        message longtext NOT NULL,
        is_read tinyint(1) DEFAULT 0,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id),
        KEY sender_id (sender_id),
        KEY recipient_id (recipient_id),
        KEY listing_id (listing_id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );
}
// Uncomment to create table: add_action( 'after_switch_theme', 'connectpro_create_messages_table' );

/**
 * Send message
 */
function connectpro_send_message( $sender_id, $recipient_id, $subject, $message, $listing_id = null ) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'connectpro_messages';

    $result = $wpdb->insert(
        $table_name,
        array(
            'sender_id'     => $sender_id,
            'recipient_id'  => $recipient_id,
            'listing_id'    => $listing_id,
            'subject'       => $subject,
            'message'       => $message,
            'created_at'    => current_time( 'mysql' ),
        ),
        array( '%d', '%d', '%d', '%s', '%s', '%s' )
    );

    if ( $result ) {
        // Send email notification
        connectpro_send_message_notification( $recipient_id, $sender_id, $subject, $message );
        return true;
    }

    return false;
}

/**
 * Get user messages
 */
function connectpro_get_user_messages( $user_id, $limit = 20, $offset = 0 ) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'connectpro_messages';

    $messages = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table_name
            WHERE recipient_id = %d OR sender_id = %d
            ORDER BY created_at DESC
            LIMIT %d OFFSET %d",
            $user_id,
            $user_id,
            $limit,
            $offset
        )
    );

    return $messages;
}

/**
 * Send message notification email
 */
function connectpro_send_message_notification( $recipient_id, $sender_id, $subject, $message ) {
    $recipient = get_userdata( $recipient_id );
    $sender = get_userdata( $sender_id );

    if ( ! $recipient || ! $sender ) {
        return;
    }

    $to = $recipient->user_email;
    $email_subject = sprintf( __( 'New message from %s', 'connectpro' ), $sender->display_name );
    $email_message = sprintf(
        __( 'You have received a new message from %1$s:

Subject: %2$s

Message:
%3$s

View and reply to this message in your dashboard: %4$s', 'connectpro' ),
        $sender->display_name,
        $subject,
        $message,
        home_url( '/dashboard?tab=messages' )
    );

    wp_mail( $to, $email_subject, $email_message );
}

/**
 * AJAX send message handler
 */
function connectpro_ajax_send_message() {
    check_ajax_referer( 'connectpro_nonce', 'nonce' );

    if ( ! is_user_logged_in() ) {
        wp_send_json_error( array( 'message' => __( 'You must be logged in to send messages.', 'connectpro' ) ) );
    }

    $sender_id = get_current_user_id();
    $recipient_id = isset( $_POST['recipient_id'] ) ? intval( $_POST['recipient_id'] ) : 0;
    $listing_id = isset( $_POST['listing_id'] ) ? intval( $_POST['listing_id'] ) : null;
    $subject = isset( $_POST['subject'] ) ? sanitize_text_field( $_POST['subject'] ) : '';
    $message = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';

    if ( ! $recipient_id || ! $subject || ! $message ) {
        wp_send_json_error( array( 'message' => __( 'All fields are required.', 'connectpro' ) ) );
    }

    $result = connectpro_send_message( $sender_id, $recipient_id, $subject, $message, $listing_id );

    if ( $result ) {
        wp_send_json_success( array( 'message' => __( 'Message sent successfully!', 'connectpro' ) ) );
    } else {
        wp_send_json_error( array( 'message' => __( 'Failed to send message. Please try again.', 'connectpro' ) ) );
    }
}
add_action( 'wp_ajax_send_message', 'connectpro_ajax_send_message' );
