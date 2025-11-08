<?php
/**
 * Google Maps Integration
 *
 * @package ConnectPro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue Google Maps API
 */
function connectpro_enqueue_google_maps() {
    $api_key = get_option( 'connectpro_google_maps_api_key' );

    if ( ! $api_key ) {
        return;
    }

    // Only load on pages that need maps
    if ( is_page_template( 'template-split-map.php' ) ||
         is_page_template( 'template-listings-map.php' ) ||
         is_singular( 'listing' ) ||
         is_post_type_archive( 'listing' ) ) {

        wp_enqueue_script(
            'google-maps',
            'https://maps.googleapis.com/maps/api/js?key=' . esc_attr( $api_key ) . '&libraries=places',
            array(),
            null,
            true
        );

        wp_enqueue_script(
            'connectpro-maps',
            CONNECTPRO_THEME_URI . '/assets/js/maps.js',
            array( 'jquery', 'google-maps' ),
            CONNECTPRO_VERSION,
            true
        );

        // Localize map settings
        wp_localize_script( 'connectpro-maps', 'connectproMaps', array(
            'default_lat'    => get_option( 'connectpro_map_default_lat', '40.7128' ),
            'default_lng'    => get_option( 'connectpro_map_default_lng', '-74.0060' ),
            'default_zoom'   => get_option( 'connectpro_map_default_zoom', '12' ),
            'map_style'      => get_option( 'connectpro_map_style', 'standard' ),
            'marker_icon'    => CONNECTPRO_THEME_URI . '/assets/images/marker.png',
            'cluster_icon'   => CONNECTPRO_THEME_URI . '/assets/images/cluster.png',
        ) );
    }
}
add_action( 'wp_enqueue_scripts', 'connectpro_enqueue_google_maps' );

/**
 * Add Google Maps API Key Setting
 */
function connectpro_add_maps_settings( $wp_customize ) {
    // Maps Section
    $wp_customize->add_section( 'connectpro_maps', array(
        'title'    => __( 'Google Maps', 'connectpro' ),
        'priority' => 100,
    ) );

    // API Key
    $wp_customize->add_setting( 'connectpro_google_maps_api_key', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'connectpro_google_maps_api_key', array(
        'label'       => __( 'Google Maps API Key', 'connectpro' ),
        'section'     => 'connectpro_maps',
        'type'        => 'text',
        'description' => __( 'Enter your Google Maps API Key. Get one at https://developers.google.com/maps', 'connectpro' ),
    ) );

    // Default Latitude
    $wp_customize->add_setting( 'connectpro_map_default_lat', array(
        'default'           => '40.7128',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'connectpro_map_default_lat', array(
        'label'   => __( 'Default Latitude', 'connectpro' ),
        'section' => 'connectpro_maps',
        'type'    => 'text',
    ) );

    // Default Longitude
    $wp_customize->add_setting( 'connectpro_map_default_lng', array(
        'default'           => '-74.0060',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'connectpro_map_default_lng', array(
        'label'   => __( 'Default Longitude', 'connectpro' ),
        'section' => 'connectpro_maps',
        'type'    => 'text',
    ) );

    // Default Zoom
    $wp_customize->add_setting( 'connectpro_map_default_zoom', array(
        'default'           => '12',
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( 'connectpro_map_default_zoom', array(
        'label'       => __( 'Default Zoom Level', 'connectpro' ),
        'section'     => 'connectpro_maps',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 20,
            'step' => 1,
        ),
    ) );

    // Map Style
    $wp_customize->add_setting( 'connectpro_map_style', array(
        'default'           => 'standard',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'connectpro_map_style', array(
        'label'   => __( 'Map Style', 'connectpro' ),
        'section' => 'connectpro_maps',
        'type'    => 'select',
        'choices' => array(
            'standard'  => __( 'Standard', 'connectpro' ),
            'silver'    => __( 'Silver', 'connectpro' ),
            'retro'     => __( 'Retro', 'connectpro' ),
            'dark'      => __( 'Dark', 'connectpro' ),
            'night'     => __( 'Night', 'connectpro' ),
            'aubergine' => __( 'Aubergine', 'connectpro' ),
        ),
    ) );
}
add_action( 'customize_register', 'connectpro_add_maps_settings' );

/**
 * Get map style based on selected option
 */
function connectpro_get_map_style( $style = 'standard' ) {
    $styles = array(
        'silver' => '[{"elementType":"geometry","stylers":[{"color":"#f5f5f5"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#f5f5f5"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#ffffff"}]},{"featureType":"road.arterial","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#dadada"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#c9c9c9"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]}]',

        'retro' => '[{"elementType":"geometry","stylers":[{"color":"#ebe3cd"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#523735"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#f5f1e6"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#c9b2a6"}]},{"featureType":"administrative.land_parcel","elementType":"geometry.stroke","stylers":[{"color":"#dcd2be"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#ae9e90"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#93817c"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#a5b076"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#447530"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#f5f1e6"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#fdfcf8"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#f8c967"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#e9bc62"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry","stylers":[{"color":"#e98d58"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry.stroke","stylers":[{"color":"#db8555"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#806b63"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"transit.line","elementType":"labels.text.fill","stylers":[{"color":"#8f7d77"}]},{"featureType":"transit.line","elementType":"labels.text.stroke","stylers":[{"color":"#ebe3cd"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#b9d3c2"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#92998d"}]}]',

        'dark' => '[{"elementType":"geometry","stylers":[{"color":"#212121"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#212121"}]},{"featureType":"administrative","elementType":"geometry","stylers":[{"color":"#757575"}]},{"featureType":"administrative.country","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"administrative.land_parcel","stylers":[{"visibility":"off"}]},{"featureType":"administrative.locality","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#181818"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"poi.park","elementType":"labels.text.stroke","stylers":[{"color":"#1b1b1b"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#2c2c2c"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#8a8a8a"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#373737"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#3c3c3c"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry","stylers":[{"color":"#4e4e4e"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"transit","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#3d3d3d"}]}]',
    );

    return isset( $styles[ $style ] ) ? $styles[ $style ] : '[]';
}

/**
 * AJAX: Load more listings for map
 */
function connectpro_ajax_load_more_map_listings() {
    check_ajax_referer( 'connectpro_nonce', 'nonce' );

    $page = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
    $filters = isset( $_POST['filters'] ) ? $_POST['filters'] : array();

    $args = array(
        'post_type'      => 'listing',
        'posts_per_page' => 20,
        'paged'          => $page,
        'post_status'    => 'publish',
    );

    // Apply filters
    if ( ! empty( $filters['keyword'] ) ) {
        $args['s'] = sanitize_text_field( $filters['keyword'] );
    }

    if ( ! empty( $filters['category'] ) ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'listing_category',
                'field'    => 'slug',
                'terms'    => sanitize_text_field( $filters['category'] ),
            ),
        );
    }

    $query = new WP_Query( $args );
    $markers = array();

    ob_start();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();

            // Collect markers
            $lat = get_post_meta( get_the_ID(), '_listing_latitude', true );
            $lng = get_post_meta( get_the_ID(), '_listing_longitude', true );

            if ( $lat && $lng ) {
                $markers[] = array(
                    'lat'   => floatval( $lat ),
                    'lng'   => floatval( $lng ),
                    'title' => get_the_title(),
                    'url'   => get_permalink(),
                    'image' => get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ),
                    'price' => connectpro_get_listing_price( get_the_ID() ),
                    'id'    => get_the_ID(),
                );
            }

            get_template_part( 'template-parts/listings/listing-card-horizontal' );
        }
    }

    $html = ob_get_clean();
    wp_reset_postdata();

    wp_send_json_success( array(
        'html'    => $html,
        'markers' => $markers,
    ) );
}
add_action( 'wp_ajax_load_more_listings', 'connectpro_ajax_load_more_map_listings' );
add_action( 'wp_ajax_nopriv_load_more_listings', 'connectpro_ajax_load_more_map_listings' );
