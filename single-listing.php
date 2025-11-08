<?php
/**
 * Single Listing Template
 *
 * @package ConnectPro
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main single-listing-page">
    <div class="container">
        <?php
        while ( have_posts() ) :
            the_post();

            $listing_id = get_the_ID();
            $price = connectpro_get_listing_price( $listing_id );
            $location = connectpro_get_listing_location( $listing_id );
            $type = get_post_meta( $listing_id, '_listing_type', true );
            $bedrooms = get_post_meta( $listing_id, '_listing_bedrooms', true );
            $bathrooms = get_post_meta( $listing_id, '_listing_bathrooms', true );
            $area = get_post_meta( $listing_id, '_listing_area', true );
            $features = get_post_meta( $listing_id, '_listing_features', true );
            $author_info = connectpro_get_listing_author_info( $listing_id );
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class( 'single-listing' ); ?>>
                <div class="listing-content-wrapper">
                    <div class="listing-main-content">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="listing-featured-image">
                                <?php the_post_thumbnail( 'full' ); ?>
                            </div>
                        <?php endif; ?>

                        <header class="listing-header">
                            <h1 class="listing-title"><?php the_title(); ?></h1>

                            <?php if ( $location ) : ?>
                                <div class="listing-location">
                                    <svg width="20" height="20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                    <?php echo esc_html( $location ); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ( $bedrooms || $bathrooms || $area ) : ?>
                                <div class="listing-features-summary">
                                    <?php if ( $bedrooms ) : ?>
                                        <span><?php echo esc_html( $bedrooms . ' ' . __( 'Bedrooms', 'connectpro' ) ); ?></span>
                                    <?php endif; ?>

                                    <?php if ( $bathrooms ) : ?>
                                        <span><?php echo esc_html( $bathrooms . ' ' . __( 'Bathrooms', 'connectpro' ) ); ?></span>
                                    <?php endif; ?>

                                    <?php if ( $area ) : ?>
                                        <span><?php echo esc_html( number_format( $area ) . ' sqft' ); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </header>

                        <div class="listing-description">
                            <h2><?php esc_html_e( 'Description', 'connectpro' ); ?></h2>
                            <?php the_content(); ?>
                        </div>

                        <?php if ( $features ) : ?>
                            <div class="listing-features">
                                <h2><?php esc_html_e( 'Features', 'connectpro' ); ?></h2>
                                <ul>
                                    <?php
                                    $features_array = explode( "\n", $features );
                                    foreach ( $features_array as $feature ) {
                                        if ( trim( $feature ) ) {
                                            echo '<li>' . esc_html( trim( $feature ) ) . '</li>';
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>

                    <aside class="listing-sidebar">
                        <?php if ( $price ) : ?>
                            <div class="listing-price-box">
                                <div class="price-label"><?php esc_html_e( 'Price', 'connectpro' ); ?></div>
                                <div class="price-value"><?php echo esc_html( $price ); ?></div>
                            </div>
                        <?php endif; ?>

                        <div class="listing-author-box">
                            <h3><?php esc_html_e( 'Listed By', 'connectpro' ); ?></h3>
                            <div class="author-info">
                                <img src="<?php echo esc_url( $author_info['avatar'] ); ?>" alt="<?php echo esc_attr( $author_info['name'] ); ?>" class="author-avatar">
                                <div class="author-details">
                                    <h4><?php echo esc_html( $author_info['name'] ); ?></h4>
                                    <a href="<?php echo esc_url( $author_info['url'] ); ?>"><?php esc_html_e( 'View Profile', 'connectpro' ); ?></a>
                                </div>
                            </div>
                        </div>

                        <div class="listing-contact-box">
                            <h3><?php esc_html_e( 'Contact Seller', 'connectpro' ); ?></h3>
                            <?php echo do_shortcode( '[contact-form-7 id="1" title="Contact Form"]' ); ?>
                        </div>

                        <div class="listing-actions">
                            <button class="btn btn-primary btn-block listing-favorite" data-listing-id="<?php echo esc_attr( $listing_id ); ?>">
                                <?php esc_html_e( 'Add to Favorites', 'connectpro' ); ?>
                            </button>
                        </div>
                    </aside>
                </div>

                <?php
                // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;
                ?>
            </article>

        <?php endwhile; ?>
    </div>
</main>

<?php
get_footer();
