<?php
/**
 * Register Listing Custom Post Type
 *
 * @package ConnectPro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Listing Post Type
 */
function connectpro_register_listing_post_type() {
    $labels = array(
        'name'                  => _x( 'Listings', 'Post Type General Name', 'connectpro' ),
        'singular_name'         => _x( 'Listing', 'Post Type Singular Name', 'connectpro' ),
        'menu_name'             => __( 'Listings', 'connectpro' ),
        'name_admin_bar'        => __( 'Listing', 'connectpro' ),
        'archives'              => __( 'Listing Archives', 'connectpro' ),
        'attributes'            => __( 'Listing Attributes', 'connectpro' ),
        'parent_item_colon'     => __( 'Parent Listing:', 'connectpro' ),
        'all_items'             => __( 'All Listings', 'connectpro' ),
        'add_new_item'          => __( 'Add New Listing', 'connectpro' ),
        'add_new'               => __( 'Add New', 'connectpro' ),
        'new_item'              => __( 'New Listing', 'connectpro' ),
        'edit_item'             => __( 'Edit Listing', 'connectpro' ),
        'update_item'           => __( 'Update Listing', 'connectpro' ),
        'view_item'             => __( 'View Listing', 'connectpro' ),
        'view_items'            => __( 'View Listings', 'connectpro' ),
        'search_items'          => __( 'Search Listing', 'connectpro' ),
        'not_found'             => __( 'Not found', 'connectpro' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'connectpro' ),
        'featured_image'        => __( 'Featured Image', 'connectpro' ),
        'set_featured_image'    => __( 'Set featured image', 'connectpro' ),
        'remove_featured_image' => __( 'Remove featured image', 'connectpro' ),
        'use_featured_image'    => __( 'Use as featured image', 'connectpro' ),
        'insert_into_item'      => __( 'Insert into listing', 'connectpro' ),
        'uploaded_to_this_item' => __( 'Uploaded to this listing', 'connectpro' ),
        'items_list'            => __( 'Listings list', 'connectpro' ),
        'items_list_navigation' => __( 'Listings list navigation', 'connectpro' ),
        'filter_items_list'     => __( 'Filter listings list', 'connectpro' ),
    );

    $args = array(
        'label'                 => __( 'Listing', 'connectpro' ),
        'description'           => __( 'Listings for marketplace', 'connectpro' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'author', 'comments', 'revisions', 'custom-fields' ),
        'taxonomies'            => array( 'listing_category', 'listing_location', 'listing_tag' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-location-alt',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rewrite'               => array( 'slug' => 'listing' ),
    );

    register_post_type( 'listing', $args );

    // Add meta boxes
    add_action( 'add_meta_boxes', 'connectpro_add_listing_meta_boxes' );
    add_action( 'save_post_listing', 'connectpro_save_listing_meta', 10, 2 );
}
add_action( 'init', 'connectpro_register_listing_post_type', 0 );

/**
 * Add Listing Meta Boxes
 */
function connectpro_add_listing_meta_boxes() {
    add_meta_box(
        'listing_details',
        __( 'Listing Details', 'connectpro' ),
        'connectpro_listing_details_callback',
        'listing',
        'normal',
        'high'
    );

    add_meta_box(
        'listing_location',
        __( 'Location Information', 'connectpro' ),
        'connectpro_listing_location_callback',
        'listing',
        'normal',
        'default'
    );

    add_meta_box(
        'listing_pricing',
        __( 'Pricing', 'connectpro' ),
        'connectpro_listing_pricing_callback',
        'listing',
        'side',
        'default'
    );

    add_meta_box(
        'listing_contact',
        __( 'Contact Information', 'connectpro' ),
        'connectpro_listing_contact_callback',
        'listing',
        'side',
        'default'
    );
}

/**
 * Listing Details Meta Box Callback
 */
function connectpro_listing_details_callback( $post ) {
    wp_nonce_field( 'connectpro_listing_meta', 'connectpro_listing_meta_nonce' );

    $listing_type = get_post_meta( $post->ID, '_listing_type', true );
    $features = get_post_meta( $post->ID, '_listing_features', true );
    $bedrooms = get_post_meta( $post->ID, '_listing_bedrooms', true );
    $bathrooms = get_post_meta( $post->ID, '_listing_bathrooms', true );
    $area = get_post_meta( $post->ID, '_listing_area', true );
    ?>
    <table class="form-table">
        <tr>
            <th><label for="listing_type"><?php esc_html_e( 'Listing Type', 'connectpro' ); ?></label></th>
            <td>
                <select name="listing_type" id="listing_type" class="widefat">
                    <option value=""><?php esc_html_e( 'Select Type', 'connectpro' ); ?></option>
                    <option value="rent" <?php selected( $listing_type, 'rent' ); ?>><?php esc_html_e( 'For Rent', 'connectpro' ); ?></option>
                    <option value="sale" <?php selected( $listing_type, 'sale' ); ?>><?php esc_html_e( 'For Sale', 'connectpro' ); ?></option>
                    <option value="service" <?php selected( $listing_type, 'service' ); ?>><?php esc_html_e( 'Service', 'connectpro' ); ?></option>
                    <option value="event" <?php selected( $listing_type, 'event' ); ?>><?php esc_html_e( 'Event', 'connectpro' ); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="listing_bedrooms"><?php esc_html_e( 'Bedrooms', 'connectpro' ); ?></label></th>
            <td>
                <input type="number" name="listing_bedrooms" id="listing_bedrooms" value="<?php echo esc_attr( $bedrooms ); ?>" min="0" step="1" class="small-text">
            </td>
        </tr>
        <tr>
            <th><label for="listing_bathrooms"><?php esc_html_e( 'Bathrooms', 'connectpro' ); ?></label></th>
            <td>
                <input type="number" name="listing_bathrooms" id="listing_bathrooms" value="<?php echo esc_attr( $bathrooms ); ?>" min="0" step="0.5" class="small-text">
            </td>
        </tr>
        <tr>
            <th><label for="listing_area"><?php esc_html_e( 'Area (sq ft)', 'connectpro' ); ?></label></th>
            <td>
                <input type="number" name="listing_area" id="listing_area" value="<?php echo esc_attr( $area ); ?>" min="0" class="regular-text">
            </td>
        </tr>
        <tr>
            <th><label for="listing_features"><?php esc_html_e( 'Features', 'connectpro' ); ?></label></th>
            <td>
                <textarea name="listing_features" id="listing_features" rows="5" class="large-text"><?php echo esc_textarea( $features ); ?></textarea>
                <p class="description"><?php esc_html_e( 'Enter features, one per line', 'connectpro' ); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Listing Location Meta Box Callback
 */
function connectpro_listing_location_callback( $post ) {
    $address = get_post_meta( $post->ID, '_listing_address', true );
    $city = get_post_meta( $post->ID, '_listing_city', true );
    $state = get_post_meta( $post->ID, '_listing_state', true );
    $zip = get_post_meta( $post->ID, '_listing_zip', true );
    $country = get_post_meta( $post->ID, '_listing_country', true );
    $latitude = get_post_meta( $post->ID, '_listing_latitude', true );
    $longitude = get_post_meta( $post->ID, '_listing_longitude', true );
    ?>
    <table class="form-table">
        <tr>
            <th><label for="listing_address"><?php esc_html_e( 'Street Address', 'connectpro' ); ?></label></th>
            <td><input type="text" name="listing_address" id="listing_address" value="<?php echo esc_attr( $address ); ?>" class="widefat"></td>
        </tr>
        <tr>
            <th><label for="listing_city"><?php esc_html_e( 'City', 'connectpro' ); ?></label></th>
            <td><input type="text" name="listing_city" id="listing_city" value="<?php echo esc_attr( $city ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="listing_state"><?php esc_html_e( 'State/Province', 'connectpro' ); ?></label></th>
            <td><input type="text" name="listing_state" id="listing_state" value="<?php echo esc_attr( $state ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="listing_zip"><?php esc_html_e( 'ZIP/Postal Code', 'connectpro' ); ?></label></th>
            <td><input type="text" name="listing_zip" id="listing_zip" value="<?php echo esc_attr( $zip ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="listing_country"><?php esc_html_e( 'Country', 'connectpro' ); ?></label></th>
            <td><input type="text" name="listing_country" id="listing_country" value="<?php echo esc_attr( $country ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="listing_latitude"><?php esc_html_e( 'Latitude', 'connectpro' ); ?></label></th>
            <td><input type="text" name="listing_latitude" id="listing_latitude" value="<?php echo esc_attr( $latitude ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="listing_longitude"><?php esc_html_e( 'Longitude', 'connectpro' ); ?></label></th>
            <td><input type="text" name="listing_longitude" id="listing_longitude" value="<?php echo esc_attr( $longitude ); ?>" class="regular-text"></td>
        </tr>
    </table>
    <?php
}

/**
 * Listing Pricing Meta Box Callback
 */
function connectpro_listing_pricing_callback( $post ) {
    $price = get_post_meta( $post->ID, '_listing_price', true );
    $currency = get_post_meta( $post->ID, '_listing_currency', true );
    $price_period = get_post_meta( $post->ID, '_listing_price_period', true );
    ?>
    <p>
        <label for="listing_price"><?php esc_html_e( 'Price', 'connectpro' ); ?></label>
        <input type="number" name="listing_price" id="listing_price" value="<?php echo esc_attr( $price ); ?>" min="0" step="0.01" class="widefat">
    </p>
    <p>
        <label for="listing_currency"><?php esc_html_e( 'Currency', 'connectpro' ); ?></label>
        <select name="listing_currency" id="listing_currency" class="widefat">
            <option value="USD" <?php selected( $currency, 'USD' ); ?>>USD ($)</option>
            <option value="EUR" <?php selected( $currency, 'EUR' ); ?>>EUR (€)</option>
            <option value="GBP" <?php selected( $currency, 'GBP' ); ?>>GBP (£)</option>
        </select>
    </p>
    <p>
        <label for="listing_price_period"><?php esc_html_e( 'Price Period', 'connectpro' ); ?></label>
        <select name="listing_price_period" id="listing_price_period" class="widefat">
            <option value=""><?php esc_html_e( 'One-time', 'connectpro' ); ?></option>
            <option value="hour" <?php selected( $price_period, 'hour' ); ?>><?php esc_html_e( 'Per Hour', 'connectpro' ); ?></option>
            <option value="day" <?php selected( $price_period, 'day' ); ?>><?php esc_html_e( 'Per Day', 'connectpro' ); ?></option>
            <option value="week" <?php selected( $price_period, 'week' ); ?>><?php esc_html_e( 'Per Week', 'connectpro' ); ?></option>
            <option value="month" <?php selected( $price_period, 'month' ); ?>><?php esc_html_e( 'Per Month', 'connectpro' ); ?></option>
            <option value="year" <?php selected( $price_period, 'year' ); ?>><?php esc_html_e( 'Per Year', 'connectpro' ); ?></option>
        </select>
    </p>
    <?php
}

/**
 * Listing Contact Meta Box Callback
 */
function connectpro_listing_contact_callback( $post ) {
    $contact_name = get_post_meta( $post->ID, '_listing_contact_name', true );
    $contact_email = get_post_meta( $post->ID, '_listing_contact_email', true );
    $contact_phone = get_post_meta( $post->ID, '_listing_contact_phone', true );
    ?>
    <p>
        <label for="listing_contact_name"><?php esc_html_e( 'Contact Name', 'connectpro' ); ?></label>
        <input type="text" name="listing_contact_name" id="listing_contact_name" value="<?php echo esc_attr( $contact_name ); ?>" class="widefat">
    </p>
    <p>
        <label for="listing_contact_email"><?php esc_html_e( 'Email', 'connectpro' ); ?></label>
        <input type="email" name="listing_contact_email" id="listing_contact_email" value="<?php echo esc_attr( $contact_email ); ?>" class="widefat">
    </p>
    <p>
        <label for="listing_contact_phone"><?php esc_html_e( 'Phone', 'connectpro' ); ?></label>
        <input type="tel" name="listing_contact_phone" id="listing_contact_phone" value="<?php echo esc_attr( $contact_phone ); ?>" class="widefat">
    </p>
    <?php
}

/**
 * Save Listing Meta
 */
function connectpro_save_listing_meta( $post_id, $post ) {
    // Verify nonce
    if ( ! isset( $_POST['connectpro_listing_meta_nonce'] ) || ! wp_verify_nonce( $_POST['connectpro_listing_meta_nonce'], 'connectpro_listing_meta' ) ) {
        return;
    }

    // Check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Save meta fields
    $meta_fields = array(
        'listing_type',
        'listing_bedrooms',
        'listing_bathrooms',
        'listing_area',
        'listing_features',
        'listing_address',
        'listing_city',
        'listing_state',
        'listing_zip',
        'listing_country',
        'listing_latitude',
        'listing_longitude',
        'listing_price',
        'listing_currency',
        'listing_price_period',
        'listing_contact_name',
        'listing_contact_email',
        'listing_contact_phone',
    );

    foreach ( $meta_fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, '_' . $field, sanitize_text_field( $_POST[ $field ] ) );
        }
    }
}

/**
 * Add custom columns to listing admin
 */
function connectpro_listing_custom_columns( $columns ) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['thumbnail'] = __( 'Image', 'connectpro' );
    $new_columns['title'] = $columns['title'];
    $new_columns['listing_type'] = __( 'Type', 'connectpro' );
    $new_columns['listing_price'] = __( 'Price', 'connectpro' );
    $new_columns['listing_location'] = __( 'Location', 'connectpro' );
    $new_columns['author'] = $columns['author'];
    $new_columns['date'] = $columns['date'];

    return $new_columns;
}
add_filter( 'manage_listing_posts_columns', 'connectpro_listing_custom_columns' );

/**
 * Display custom column content
 */
function connectpro_listing_custom_column_content( $column, $post_id ) {
    switch ( $column ) {
        case 'thumbnail':
            if ( has_post_thumbnail( $post_id ) ) {
                echo get_the_post_thumbnail( $post_id, array( 60, 60 ) );
            }
            break;

        case 'listing_type':
            $type = get_post_meta( $post_id, '_listing_type', true );
            echo esc_html( ucfirst( $type ) );
            break;

        case 'listing_price':
            $price = get_post_meta( $post_id, '_listing_price', true );
            $currency = get_post_meta( $post_id, '_listing_currency', true );
            $period = get_post_meta( $post_id, '_listing_price_period', true );

            if ( $price ) {
                $currency_symbol = $currency === 'EUR' ? '€' : ( $currency === 'GBP' ? '£' : '$' );
                echo esc_html( $currency_symbol . number_format( $price, 2 ) );
                if ( $period ) {
                    echo ' / ' . esc_html( $period );
                }
            }
            break;

        case 'listing_location':
            $city = get_post_meta( $post_id, '_listing_city', true );
            $state = get_post_meta( $post_id, '_listing_state', true );
            if ( $city || $state ) {
                echo esc_html( $city . ( $city && $state ? ', ' : '' ) . $state );
            }
            break;
    }
}
add_action( 'manage_listing_posts_custom_column', 'connectpro_listing_custom_column_content', 10, 2 );
