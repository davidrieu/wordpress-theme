<?php
/**
 * Horizontal Listing Card Template
 *
 * Used in split-map views
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
$rating = get_post_meta( $listing_id, '_listing_rating', true );
$reviews_count = get_comments_number( $listing_id );
$is_favorited = connectpro_is_listing_favorited( $listing_id );
?>

<div class="listing-card-horizontal" data-marker-id="<?php echo esc_attr( $listing_id ); ?>">
    <div class="listing-card-h-image">
        <a href="<?php the_permalink(); ?>">
            <?php
            if ( has_post_thumbnail() ) {
                the_post_thumbnail( 'connectpro-listing-thumb' );
            } else {
                echo '<img src="' . esc_url( CONNECTPRO_THEME_URI . '/assets/images/placeholder.jpg' ) . '" alt="' . esc_attr( get_the_title() ) . '">';
            }
            ?>
        </a>

        <!-- Badge -->
        <?php if ( $type ) : ?>
            <span class="listing-badge badge-<?php echo esc_attr( $type ); ?>">
                <?php echo esc_html( connectpro_get_listing_type_label( $type ) ); ?>
            </span>
        <?php endif; ?>

        <!-- Favorite Button -->
        <button class="listing-favorite-btn <?php echo $is_favorited ? 'active' : ''; ?>"
                data-listing-id="<?php echo esc_attr( $listing_id ); ?>"
                aria-label="<?php esc_attr_e( 'Add to favorites', 'connectpro' ); ?>">
            <?php if ( $is_favorited ) : ?>
                <i class="fas fa-heart"></i>
            <?php else : ?>
                <i class="far fa-heart"></i>
            <?php endif; ?>
        </button>

        <!-- Verified Badge -->
        <?php if ( get_post_meta( $listing_id, '_verified', true ) ) : ?>
            <span class="verified-badge" title="<?php esc_attr_e( 'Verified Listing', 'connectpro' ); ?>">
                <i class="fas fa-check-circle"></i>
            </span>
        <?php endif; ?>
    </div>

    <div class="listing-card-h-content">
        <div class="listing-card-h-header">
            <?php if ( $price ) : ?>
                <div class="listing-price">
                    <?php echo esc_html( $price ); ?>
                </div>
            <?php endif; ?>

            <?php if ( $rating || $reviews_count > 0 ) : ?>
                <div class="listing-rating">
                    <div class="star-rating">
                        <?php
                        $rating_value = $rating ? floatval( $rating ) : 0;
                        for ( $i = 1; $i <= 5; $i++ ) {
                            if ( $i <= $rating_value ) {
                                echo '<i class="fas fa-star"></i>';
                            } elseif ( $i - 0.5 <= $rating_value ) {
                                echo '<i class="fas fa-star-half-alt"></i>';
                            } else {
                                echo '<i class="far fa-star"></i>';
                            }
                        }
                        ?>
                    </div>
                    <?php if ( $reviews_count > 0 ) : ?>
                        <span class="reviews-count">(<?php echo esc_html( $reviews_count ); ?>)</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <h3 class="listing-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <?php if ( $location ) : ?>
            <div class="listing-location">
                <i class="fas fa-map-marker-alt"></i>
                <span><?php echo esc_html( $location ); ?></span>
            </div>
        <?php endif; ?>

        <div class="listing-excerpt">
            <?php echo wp_trim_words( get_the_excerpt(), 20 ); ?>
        </div>

        <?php if ( $bedrooms || $bathrooms || $area ) : ?>
            <div class="listing-features">
                <?php if ( $bedrooms ) : ?>
                    <span class="feature">
                        <i class="fas fa-bed"></i>
                        <?php echo esc_html( $bedrooms ); ?>
                    </span>
                <?php endif; ?>

                <?php if ( $bathrooms ) : ?>
                    <span class="feature">
                        <i class="fas fa-bath"></i>
                        <?php echo esc_html( $bathrooms ); ?>
                    </span>
                <?php endif; ?>

                <?php if ( $area ) : ?>
                    <span class="feature">
                        <i class="fas fa-expand-arrows-alt"></i>
                        <?php echo esc_html( number_format( $area ) . ' sqft' ); ?>
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="listing-card-h-footer">
            <!-- Author Info -->
            <div class="listing-author">
                <?php
                $author_id = get_the_author_meta( 'ID' );
                echo get_avatar( $author_id, 32 );
                ?>
                <span><?php the_author(); ?></span>
            </div>

            <!-- View Button -->
            <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary btn-sm">
                <?php esc_html_e( 'View Details', 'connectpro' ); ?>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
