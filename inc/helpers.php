<?php
/**
 * Helper Functions
 *
 * @package ConnectPro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Get listing price
 */
function connectpro_get_listing_price( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $price = get_post_meta( $post_id, '_listing_price', true );
    $currency = get_post_meta( $post_id, '_listing_currency', true );
    $period = get_post_meta( $post_id, '_listing_price_period', true );

    if ( ! $price ) {
        return '';
    }

    $currency_symbol = connectpro_get_currency_symbol( $currency );
    $formatted_price = $currency_symbol . number_format( $price, 2 );

    if ( $period ) {
        $formatted_price .= ' / ' . esc_html( $period );
    }

    return $formatted_price;
}

/**
 * Get currency symbol
 */
function connectpro_get_currency_symbol( $currency = 'USD' ) {
    $symbols = array(
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£',
        'JPY' => '¥',
        'CAD' => 'C$',
        'AUD' => 'A$',
    );

    return isset( $symbols[ $currency ] ) ? $symbols[ $currency ] : $currency . ' ';
}

/**
 * Get listing location
 */
function connectpro_get_listing_location( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $city = get_post_meta( $post_id, '_listing_city', true );
    $state = get_post_meta( $post_id, '_listing_state', true );
    $country = get_post_meta( $post_id, '_listing_country', true );

    $location_parts = array_filter( array( $city, $state, $country ) );

    return implode( ', ', $location_parts );
}

/**
 * Get listing type label
 */
function connectpro_get_listing_type_label( $type ) {
    $labels = array(
        'rent'    => __( 'For Rent', 'connectpro' ),
        'sale'    => __( 'For Sale', 'connectpro' ),
        'service' => __( 'Service', 'connectpro' ),
        'event'   => __( 'Event', 'connectpro' ),
    );

    return isset( $labels[ $type ] ) ? $labels[ $type ] : ucfirst( $type );
}

/**
 * Check if listing is favorited
 */
function connectpro_is_listing_favorited( $post_id ) {
    if ( ! is_user_logged_in() ) {
        return false;
    }

    $user_id = get_current_user_id();
    $favorites = get_user_meta( $user_id, 'listing_favorites', true );

    if ( ! is_array( $favorites ) ) {
        $favorites = array();
    }

    return in_array( $post_id, $favorites );
}

/**
 * Get user's favorite listings count
 */
function connectpro_get_favorites_count() {
    if ( ! is_user_logged_in() ) {
        return 0;
    }

    $user_id = get_current_user_id();
    $favorites = get_user_meta( $user_id, 'listing_favorites', true );

    if ( ! is_array( $favorites ) ) {
        return 0;
    }

    return count( $favorites );
}

/**
 * Get listing author info
 */
function connectpro_get_listing_author_info( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $author_id = get_post_field( 'post_author', $post_id );

    return array(
        'id'     => $author_id,
        'name'   => get_the_author_meta( 'display_name', $author_id ),
        'email'  => get_the_author_meta( 'user_email', $author_id ),
        'url'    => get_author_posts_url( $author_id ),
        'avatar' => get_avatar_url( $author_id ),
    );
}

/**
 * Get time ago
 */
function connectpro_time_ago( $time ) {
    $time_difference = time() - $time;

    if ( $time_difference < 1 ) {
        return __( 'just now', 'connectpro' );
    }

    $condition = array(
        12 * 30 * 24 * 60 * 60 => __( 'year', 'connectpro' ),
        30 * 24 * 60 * 60      => __( 'month', 'connectpro' ),
        24 * 60 * 60           => __( 'day', 'connectpro' ),
        60 * 60                => __( 'hour', 'connectpro' ),
        60                     => __( 'minute', 'connectpro' ),
        1                      => __( 'second', 'connectpro' ),
    );

    foreach ( $condition as $secs => $str ) {
        $d = $time_difference / $secs;

        if ( $d >= 1 ) {
            $t = round( $d );
            return $t . ' ' . $str . ( $t > 1 ? 's' : '' ) . ' ' . __( 'ago', 'connectpro' );
        }
    }
}

/**
 * Sanitize array
 */
function connectpro_sanitize_array( $array ) {
    if ( ! is_array( $array ) ) {
        return array();
    }

    return array_map( 'sanitize_text_field', $array );
}

/**
 * Get reading time
 */
function connectpro_get_reading_time( $content = '' ) {
    if ( empty( $content ) ) {
        $content = get_the_content();
    }

    $word_count = str_word_count( strip_tags( $content ) );
    $minutes = floor( $word_count / 200 );

    if ( $minutes < 1 ) {
        return '1 ' . __( 'min read', 'connectpro' );
    }

    return $minutes . ' ' . __( 'min read', 'connectpro' );
}

/**
 * Truncate text
 */
function connectpro_truncate_text( $text, $length = 100, $suffix = '...' ) {
    if ( strlen( $text ) <= $length ) {
        return $text;
    }

    return substr( $text, 0, $length ) . $suffix;
}

/**
 * Get social share links
 */
function connectpro_get_social_share_links( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $title = get_the_title( $post_id );
    $url = get_permalink( $post_id );
    $thumbnail = get_the_post_thumbnail_url( $post_id, 'large' );

    return array(
        'facebook'  => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode( $url ),
        'twitter'   => 'https://twitter.com/intent/tweet?url=' . urlencode( $url ) . '&text=' . urlencode( $title ),
        'linkedin'  => 'https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode( $url ) . '&title=' . urlencode( $title ),
        'pinterest' => 'https://pinterest.com/pin/create/button/?url=' . urlencode( $url ) . '&media=' . urlencode( $thumbnail ) . '&description=' . urlencode( $title ),
        'whatsapp'  => 'https://wa.me/?text=' . urlencode( $title . ' ' . $url ),
        'email'     => 'mailto:?subject=' . urlencode( $title ) . '&body=' . urlencode( $url ),
    );
}

/**
 * Check if user is listing author
 */
function connectpro_is_listing_author( $post_id = null, $user_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    if ( ! $user_id ) {
        $user_id = get_current_user_id();
    }

    $author_id = get_post_field( 'post_author', $post_id );

    return $author_id == $user_id;
}

/**
 * Get listing status badge
 */
function connectpro_get_listing_status_badge( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $status = get_post_status( $post_id );
    $badges = array(
        'publish' => array(
            'label' => __( 'Active', 'connectpro' ),
            'class' => 'status-active',
        ),
        'pending' => array(
            'label' => __( 'Pending', 'connectpro' ),
            'class' => 'status-pending',
        ),
        'draft'   => array(
            'label' => __( 'Draft', 'connectpro' ),
            'class' => 'status-draft',
        ),
    );

    if ( isset( $badges[ $status ] ) ) {
        return '<span class="listing-status ' . esc_attr( $badges[ $status ]['class'] ) . '">' . esc_html( $badges[ $status ]['label'] ) . '</span>';
    }

    return '';
}
