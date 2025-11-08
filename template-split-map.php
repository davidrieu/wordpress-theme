<?php
/**
 * Template Name: Listings Split Map
 *
 * Full screen listing archive with split-view map
 *
 * @package ConnectPro
 * @since 1.0.0
 */

get_header( 'split' );

// Get search parameters
$keyword = isset( $_GET['keyword'] ) ? sanitize_text_field( $_GET['keyword'] ) : '';
$location = isset( $_GET['location'] ) ? sanitize_text_field( $_GET['location'] ) : '';
$category = isset( $_GET['category'] ) ? sanitize_text_field( $_GET['category'] ) : '';

// Build query
$args = array(
    'post_type'      => 'listing',
    'posts_per_page' => 20,
    'post_status'    => 'publish',
);

if ( $keyword ) {
    $args['s'] = $keyword;
}

if ( $category ) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'listing_category',
            'field'    => 'slug',
            'terms'    => $category,
        ),
    );
}

$listings_query = new WP_Query( $args );

// Collect listing coordinates for map
$map_markers = array();
?>

<div id="split-map-container" class="split-map-layout">
    <!-- Left Side - Listings List -->
    <div class="split-map-listings">
        <!-- Search Bar -->
        <div class="split-map-search-bar">
            <form class="split-map-search-form" method="get">
                <div class="search-field-group">
                    <input type="text"
                           name="keyword"
                           placeholder="<?php esc_attr_e( 'What are you looking for?', 'connectpro' ); ?>"
                           value="<?php echo esc_attr( $keyword ); ?>">
                </div>

                <div class="search-field-group">
                    <input type="text"
                           name="location"
                           id="split-location-input"
                           placeholder="<?php esc_attr_e( 'Where?', 'connectpro' ); ?>"
                           value="<?php echo esc_attr( $location ); ?>">
                </div>

                <div class="search-field-group">
                    <select name="category">
                        <option value=""><?php esc_html_e( 'All Categories', 'connectpro' ); ?></option>
                        <?php
                        $categories = get_terms( array(
                            'taxonomy'   => 'listing_category',
                            'hide_empty' => false,
                        ) );
                        foreach ( $categories as $cat ) {
                            printf(
                                '<option value="%s" %s>%s</option>',
                                esc_attr( $cat->slug ),
                                selected( $category, $cat->slug, false ),
                                esc_html( $cat->name )
                            );
                        }
                        ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                    <?php esc_html_e( 'Search', 'connectpro' ); ?>
                </button>
            </form>

            <!-- Filters Toggle (Mobile) -->
            <button class="filters-toggle mobile-only" id="filters-toggle">
                <i class="fas fa-sliders-h"></i>
                <?php esc_html_e( 'Filters', 'connectpro' ); ?>
            </button>

            <!-- Map Toggle (Mobile) -->
            <button class="map-toggle mobile-only" id="map-toggle">
                <i class="fas fa-map"></i>
                <?php esc_html_e( 'Map', 'connectpro' ); ?>
            </button>
        </div>

        <!-- Sorting and View Options -->
        <div class="listings-controls">
            <div class="results-count">
                <strong><?php echo esc_html( $listings_query->found_posts ); ?></strong>
                <?php esc_html_e( 'listings found', 'connectpro' ); ?>
            </div>

            <div class="sorting-options">
                <select id="listing-sort" class="sort-select">
                    <option value="date_new"><?php esc_html_e( 'Newest First', 'connectpro' ); ?></option>
                    <option value="date_old"><?php esc_html_e( 'Oldest First', 'connectpro' ); ?></option>
                    <option value="price_low"><?php esc_html_e( 'Price: Low to High', 'connectpro' ); ?></option>
                    <option value="price_high"><?php esc_html_e( 'Price: High to Low', 'connectpro' ); ?></option>
                    <option value="popular"><?php esc_html_e( 'Most Popular', 'connectpro' ); ?></option>
                </select>
            </div>
        </div>

        <!-- Listings Container -->
        <div class="split-map-listings-container" id="listings-container">
            <?php
            if ( $listings_query->have_posts() ) :
                while ( $listings_query->have_posts() ) :
                    $listings_query->the_post();

                    // Get listing coordinates
                    $lat = get_post_meta( get_the_ID(), '_listing_latitude', true );
                    $lng = get_post_meta( get_the_ID(), '_listing_longitude', true );

                    if ( $lat && $lng ) {
                        $map_markers[] = array(
                            'lat'      => floatval( $lat ),
                            'lng'      => floatval( $lng ),
                            'title'    => get_the_title(),
                            'url'      => get_permalink(),
                            'image'    => get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ),
                            'price'    => connectpro_get_listing_price( get_the_ID() ),
                            'id'       => get_the_ID(),
                        );
                    }

                    // Display listing card
                    get_template_part( 'template-parts/listings/listing-card-horizontal' );

                endwhile;
            else :
                ?>
                <div class="no-listings-found">
                    <i class="fas fa-search"></i>
                    <h3><?php esc_html_e( 'No Listings Found', 'connectpro' ); ?></h3>
                    <p><?php esc_html_e( 'Try adjusting your search filters', 'connectpro' ); ?></p>
                </div>
                <?php
            endif;
            wp_reset_postdata();
            ?>
        </div>

        <!-- Load More Button -->
        <?php if ( $listings_query->max_num_pages > 1 ) : ?>
            <div class="listings-pagination">
                <button class="btn btn-secondary load-more-listings" data-page="1" data-max="<?php echo esc_attr( $listings_query->max_num_pages ); ?>">
                    <?php esc_html_e( 'Load More Listings', 'connectpro' ); ?>
                </button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Right Side - Map -->
    <div class="split-map-wrapper">
        <div id="split-map" class="map-container"
             data-markers='<?php echo wp_json_encode( $map_markers ); ?>'
             data-zoom="12"
             data-style="standard">
        </div>

        <!-- Map Controls -->
        <div class="map-controls">
            <button class="map-control fullscreen-toggle" title="<?php esc_attr_e( 'Fullscreen', 'connectpro' ); ?>">
                <i class="fas fa-expand"></i>
            </button>
            <button class="map-control zoom-in" title="<?php esc_attr_e( 'Zoom In', 'connectpro' ); ?>">
                <i class="fas fa-plus"></i>
            </button>
            <button class="map-control zoom-out" title="<?php esc_attr_e( 'Zoom Out', 'connectpro' ); ?>">
                <i class="fas fa-minus"></i>
            </button>
            <button class="map-control locate-me" title="<?php esc_attr_e( 'My Location', 'connectpro' ); ?>">
                <i class="fas fa-location-arrow"></i>
            </button>
        </div>
    </div>
</div>

<script>
// Initialize map when page loads
jQuery(document).ready(function($) {
    // Map initialization
    if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
        connectproInitSplitMap();
    }

    // Mobile map toggle
    $('#map-toggle').on('click', function() {
        $('.split-map-wrapper').toggleClass('mobile-visible');
        $(this).toggleClass('active');
    });

    // Load more functionality
    $('.load-more-listings').on('click', function() {
        var button = $(this);
        var page = parseInt(button.data('page')) + 1;
        var maxPages = parseInt(button.data('max'));

        // AJAX load more
        $.ajax({
            url: connectproData.ajaxurl,
            type: 'POST',
            data: {
                action: 'load_more_listings',
                page: page,
                filters: getSearchFilters()
            },
            beforeSend: function() {
                button.text('<?php esc_html_e( 'Loading...', 'connectpro' ); ?>');
            },
            success: function(response) {
                if (response.success) {
                    $('#listings-container').append(response.data.html);
                    button.data('page', page);

                    if (page >= maxPages) {
                        button.hide();
                    } else {
                        button.text('<?php esc_html_e( 'Load More Listings', 'connectpro' ); ?>');
                    }

                    // Update map markers
                    if (response.data.markers) {
                        addMapMarkers(response.data.markers);
                    }
                }
            }
        });
    });

    // Get current search filters
    function getSearchFilters() {
        return {
            keyword: $('input[name="keyword"]').val(),
            location: $('input[name="location"]').val(),
            category: $('select[name="category"]').val()
        };
    }
});

/**
 * Initialize Split Map
 */
function connectproInitSplitMap() {
    var mapElement = document.getElementById('split-map');
    if (!mapElement) return;

    var markers = JSON.parse(mapElement.dataset.markers || '[]');

    // Create map
    var map = new google.maps.Map(mapElement, {
        zoom: 12,
        center: markers.length > 0 ? {lat: markers[0].lat, lng: markers[0].lng} : {lat: 40.7128, lng: -74.0060},
        styles: getMapStyle(),
        mapTypeControl: false,
        streetViewControl: false,
        fullscreenControl: false
    });

    // Add markers
    var bounds = new google.maps.LatLngBounds();
    var mapMarkers = [];

    markers.forEach(function(markerData) {
        var marker = new google.maps.Marker({
            position: {lat: markerData.lat, lng: markerData.lng},
            map: map,
            title: markerData.title,
            icon: getMarkerIcon()
        });

        // Info window
        var infoWindow = new google.maps.InfoWindow({
            content: getInfoWindowContent(markerData)
        });

        marker.addListener('click', function() {
            infoWindow.open(map, marker);
        });

        bounds.extend(marker.getPosition());
        mapMarkers.push(marker);
    });

    // Fit bounds
    if (markers.length > 0) {
        map.fitBounds(bounds);
    }

    // Store map instance globally
    window.splitMapInstance = {
        map: map,
        markers: mapMarkers
    };
}

/**
 * Get marker icon
 */
function getMarkerIcon() {
    return {
        url: connectproData.theme_url + '/assets/images/marker.png',
        scaledSize: new google.maps.Size(40, 40)
    };
}

/**
 * Get info window content
 */
function getInfoWindowContent(data) {
    var html = '<div class="map-info-window">';
    if (data.image) {
        html += '<img src="' + data.image + '" alt="' + data.title + '">';
    }
    html += '<h4>' + data.title + '</h4>';
    if (data.price) {
        html += '<p class="price">' + data.price + '</p>';
    }
    html += '<a href="' + data.url + '" class="view-listing"><?php esc_html_e( 'View Details', 'connectpro' ); ?></a>';
    html += '</div>';
    return html;
}

/**
 * Get map style
 */
function getMapStyle() {
    // Return custom map style
    return [];
}
</script>

<?php
get_footer( 'split' );
