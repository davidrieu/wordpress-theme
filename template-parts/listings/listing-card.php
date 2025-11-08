<?php
/**
 * Template part for displaying listing cards
 *
 * @package ConnectPro
 * @since 1.0.0
 */

$listing_id = get_the_ID();
$price = connectpro_get_listing_price( $listing_id );
$location = connectpro_get_listing_location( $listing_id );
$type = get_post_meta( $listing_id, '_listing_type', true );
$bedrooms = get_post_meta( $listing_id, '_listing_bedrooms', true );
$bathrooms = get_post_meta( $listing_id, '_listing_bathrooms', true );
$area = get_post_meta( $listing_id, '_listing_area', true );
$is_favorited = connectpro_is_listing_favorited( $listing_id );
?>

<div class="listing-card">
    <div class="listing-card-image">
        <a href="<?php the_permalink(); ?>">
            <?php
            if ( has_post_thumbnail() ) {
                the_post_thumbnail( 'connectpro-listing-thumb' );
            } else {
                echo '<img src="' . esc_url( CONNECTPRO_THEME_URI . '/assets/images/placeholder.jpg' ) . '" alt="' . esc_attr( get_the_title() ) . '">';
            }
            ?>
        </a>

        <?php if ( $type ) : ?>
            <span class="listing-badge"><?php echo esc_html( connectpro_get_listing_type_label( $type ) ); ?></span>
        <?php endif; ?>

        <button class="listing-favorite <?php echo $is_favorited ? 'active' : ''; ?>" data-listing-id="<?php echo esc_attr( $listing_id ); ?>" aria-label="<?php esc_attr_e( 'Add to favorites', 'connectpro' ); ?>">
            <?php if ( $is_favorited ) : ?>
                <svg width="20" height="20" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
            <?php else : ?>
                <svg width="20" height="20" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            <?php endif; ?>
        </button>
    </div>

    <div class="listing-card-content">
        <?php if ( $price ) : ?>
            <div class="listing-price"><?php echo esc_html( $price ); ?></div>
        <?php endif; ?>

        <h3 class="listing-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <?php if ( $location ) : ?>
            <div class="listing-location">
                <svg width="16" height="16" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                <?php echo esc_html( $location ); ?>
            </div>
        <?php endif; ?>

        <?php if ( $bedrooms || $bathrooms || $area ) : ?>
            <div class="listing-meta">
                <?php if ( $bedrooms ) : ?>
                    <span class="listing-meta-item">
                        <svg width="16" height="16" fill="currentColor"><path d="M3 19h18v2H3v-2zm0-2h18v2H3v-2zM3 3v8h18V3H3zm8 6H5V9h6v0zm2 0h6V9h-6v0z"/></svg>
                        <?php echo esc_html( $bedrooms . ' ' . __( 'Beds', 'connectpro' ) ); ?>
                    </span>
                <?php endif; ?>

                <?php if ( $bathrooms ) : ?>
                    <span class="listing-meta-item">
                        <svg width="16" height="16" fill="currentColor"><path d="M9 2v1h6v1H9v1c0 1.1.9 2 2 2h1v1c0 1.1-.9 2-2 2H9c-1.1 0-2-.9-2-2V7H6c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h3z"/></svg>
                        <?php echo esc_html( $bathrooms . ' ' . __( 'Baths', 'connectpro' ) ); ?>
                    </span>
                <?php endif; ?>

                <?php if ( $area ) : ?>
                    <span class="listing-meta-item">
                        <svg width="16" height="16" fill="currentColor"><path d="M3 3h18v18H3V3zm16 16V5H5v14h14z"/></svg>
                        <?php echo esc_html( number_format( $area ) . ' sqft' ); ?>
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
