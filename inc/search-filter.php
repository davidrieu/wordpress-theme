<?php
/**
 * Search and Filter System
 *
 * @package ConnectPro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Advanced listing search
 */
function connectpro_listing_search( $query ) {
    if ( ! is_admin() && $query->is_main_query() && $query->is_post_type_archive( 'listing' ) ) {
        // Search by keyword
        if ( isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {
            $query->set( 's', sanitize_text_field( $_GET['s'] ) );
        }

        // Filter by category
        if ( isset( $_GET['category'] ) && ! empty( $_GET['category'] ) ) {
            $query->set( 'tax_query', array(
                array(
                    'taxonomy' => 'listing_category',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field( $_GET['category'] ),
                ),
            ) );
        }

        // Filter by location
        if ( isset( $_GET['location'] ) && ! empty( $_GET['location'] ) ) {
            $query->set( 'tax_query', array(
                array(
                    'taxonomy' => 'listing_location',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field( $_GET['location'] ),
                ),
            ) );
        }

        // Filter by listing type
        if ( isset( $_GET['listing_type'] ) && ! empty( $_GET['listing_type'] ) ) {
            $query->set( 'meta_query', array(
                array(
                    'key'     => '_listing_type',
                    'value'   => sanitize_text_field( $_GET['listing_type'] ),
                    'compare' => '=',
                ),
            ) );
        }

        // Filter by price range
        if ( isset( $_GET['min_price'] ) || isset( $_GET['max_price'] ) ) {
            $meta_query = array( 'relation' => 'AND' );

            if ( isset( $_GET['min_price'] ) && ! empty( $_GET['min_price'] ) ) {
                $meta_query[] = array(
                    'key'     => '_listing_price',
                    'value'   => floatval( $_GET['min_price'] ),
                    'type'    => 'NUMERIC',
                    'compare' => '>=',
                );
            }

            if ( isset( $_GET['max_price'] ) && ! empty( $_GET['max_price'] ) ) {
                $meta_query[] = array(
                    'key'     => '_listing_price',
                    'value'   => floatval( $_GET['max_price'] ),
                    'type'    => 'NUMERIC',
                    'compare' => '<=',
                );
            }

            $query->set( 'meta_query', $meta_query );
        }

        // Sorting
        if ( isset( $_GET['orderby'] ) ) {
            switch ( $_GET['orderby'] ) {
                case 'price_low':
                    $query->set( 'meta_key', '_listing_price' );
                    $query->set( 'orderby', 'meta_value_num' );
                    $query->set( 'order', 'ASC' );
                    break;

                case 'price_high':
                    $query->set( 'meta_key', '_listing_price' );
                    $query->set( 'orderby', 'meta_value_num' );
                    $query->set( 'order', 'DESC' );
                    break;

                case 'date_new':
                    $query->set( 'orderby', 'date' );
                    $query->set( 'order', 'DESC' );
                    break;

                case 'date_old':
                    $query->set( 'orderby', 'date' );
                    $query->set( 'order', 'ASC' );
                    break;

                case 'title_asc':
                    $query->set( 'orderby', 'title' );
                    $query->set( 'order', 'ASC' );
                    break;

                case 'title_desc':
                    $query->set( 'orderby', 'title' );
                    $query->set( 'order', 'DESC' );
                    break;
            }
        }
    }
}
add_action( 'pre_get_posts', 'connectpro_listing_search' );

/**
 * AJAX filter listings
 */
function connectpro_ajax_filter_listings() {
    $args = array(
        'post_type'      => 'listing',
        'posts_per_page' => isset( $_GET['per_page'] ) ? intval( $_GET['per_page'] ) : 12,
        'paged'          => isset( $_GET['paged'] ) ? intval( $_GET['paged'] ) : 1,
    );

    // Search
    if ( isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {
        $args['s'] = sanitize_text_field( $_GET['s'] );
    }

    // Category
    if ( isset( $_GET['category'] ) && ! empty( $_GET['category'] ) ) {
        $args['tax_query'][] = array(
            'taxonomy' => 'listing_category',
            'field'    => 'slug',
            'terms'    => sanitize_text_field( $_GET['category'] ),
        );
    }

    // Location
    if ( isset( $_GET['location'] ) && ! empty( $_GET['location'] ) ) {
        $args['tax_query'][] = array(
            'taxonomy' => 'listing_location',
            'field'    => 'slug',
            'terms'    => sanitize_text_field( $_GET['location'] ),
        );
    }

    // Price range
    $meta_query = array( 'relation' => 'AND' );

    if ( isset( $_GET['min_price'] ) && ! empty( $_GET['min_price'] ) ) {
        $meta_query[] = array(
            'key'     => '_listing_price',
            'value'   => floatval( $_GET['min_price'] ),
            'type'    => 'NUMERIC',
            'compare' => '>=',
        );
    }

    if ( isset( $_GET['max_price'] ) && ! empty( $_GET['max_price'] ) ) {
        $meta_query[] = array(
            'key'     => '_listing_price',
            'value'   => floatval( $_GET['max_price'] ),
            'type'    => 'NUMERIC',
            'compare' => '<=',
        );
    }

    if ( count( $meta_query ) > 1 ) {
        $args['meta_query'] = $meta_query;
    }

    $query = new WP_Query( $args );

    ob_start();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            get_template_part( 'template-parts/listings/listing-card' );
        }
    } else {
        echo '<p class="no-listings">' . esc_html__( 'No listings found.', 'connectpro' ) . '</p>';
    }

    $html = ob_get_clean();

    wp_reset_postdata();

    wp_send_json_success( array(
        'html'       => $html,
        'found'      => $query->found_posts,
        'max_pages'  => $query->max_num_pages,
    ) );
}
add_action( 'wp_ajax_filter_listings', 'connectpro_ajax_filter_listings' );
add_action( 'wp_ajax_nopriv_filter_listings', 'connectpro_ajax_filter_listings' );

/**
 * Search form shortcode
 */
function connectpro_search_form_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'style' => 'default',
    ), $atts );

    ob_start();
    ?>
    <form id="listing-search-form" class="listing-search-form" method="get" action="<?php echo esc_url( get_post_type_archive_link( 'listing' ) ); ?>">
        <div class="search-form-row">
            <div class="search-field-wrap">
                <input type="text" name="s" placeholder="<?php esc_attr_e( 'Search listings...', 'connectpro' ); ?>" value="<?php echo isset( $_GET['s'] ) ? esc_attr( $_GET['s'] ) : ''; ?>">
            </div>

            <div class="search-field-wrap">
                <select name="category">
                    <option value=""><?php esc_html_e( 'All Categories', 'connectpro' ); ?></option>
                    <?php
                    $categories = get_terms( array(
                        'taxonomy'   => 'listing_category',
                        'hide_empty' => false,
                    ) );

                    foreach ( $categories as $category ) {
                        $selected = isset( $_GET['category'] ) && $_GET['category'] === $category->slug ? 'selected' : '';
                        echo '<option value="' . esc_attr( $category->slug ) . '" ' . $selected . '>' . esc_html( $category->name ) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="search-field-wrap">
                <select name="location">
                    <option value=""><?php esc_html_e( 'All Locations', 'connectpro' ); ?></option>
                    <?php
                    $locations = get_terms( array(
                        'taxonomy'   => 'listing_location',
                        'hide_empty' => false,
                    ) );

                    foreach ( $locations as $location ) {
                        $selected = isset( $_GET['location'] ) && $_GET['location'] === $location->slug ? 'selected' : '';
                        echo '<option value="' . esc_attr( $location->slug ) . '" ' . $selected . '>' . esc_html( $location->name ) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="search-field-wrap price-range">
                <input type="number" name="min_price" placeholder="<?php esc_attr_e( 'Min Price', 'connectpro' ); ?>" value="<?php echo isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : ''; ?>">
                <span>-</span>
                <input type="number" name="max_price" placeholder="<?php esc_attr_e( 'Max Price', 'connectpro' ); ?>" value="<?php echo isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : ''; ?>">
            </div>

            <button type="submit" class="btn btn-primary">
                <?php esc_html_e( 'Search', 'connectpro' ); ?>
            </button>
        </div>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode( 'listing_search', 'connectpro_search_form_shortcode' );
