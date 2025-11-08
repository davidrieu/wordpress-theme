<?php
/**
 * Author Archive Template
 *
 * Advanced author profile page with listings, reviews, and stats
 *
 * @package ConnectPro
 * @since 1.0.0
 */

get_header();

$author = get_queried_object();
$author_id = $author->ID;

// Get author meta
$author_description = get_the_author_meta( 'description', $author_id );
$author_website = get_the_author_meta( 'url', $author_id );
$author_phone = get_user_meta( $author_id, 'phone', true );
$author_location = get_user_meta( $author_id, 'location', true );
$author_facebook = get_user_meta( $author_id, 'facebook', true );
$author_twitter = get_user_meta( $author_id, 'twitter', true );
$author_instagram = get_user_meta( $author_id, 'instagram', true );
$author_linkedin = get_user_meta( $author_id, 'linkedin', true );

// Get author stats
$listings_count = count_user_posts( $author_id, 'listing' );
$reviews_count = connectpro_get_author_reviews_count( $author_id );
$average_rating = connectpro_get_author_average_rating( $author_id );
$member_since = date_i18n( 'F Y', strtotime( $author->user_registered ) );
$verified_badge = get_user_meta( $author_id, 'verified_seller', true );
$response_rate = get_user_meta( $author_id, 'response_rate', true ) ?: '95';
$response_time = get_user_meta( $author_id, 'response_time', true ) ?: __( 'within hours', 'connectpro' );

?>

<main id="primary" class="site-main author-page">
    <!-- Author Header -->
    <section class="author-header">
        <div class="author-header-bg" style="background-image: url('<?php echo esc_url( get_user_meta( $author_id, 'cover_image', true ) ?: CONNECTPRO_THEME_URI . '/assets/images/default-cover.jpg' ); ?>');">
            <div class="author-header-overlay"></div>
        </div>

        <div class="container">
            <div class="author-header-content">
                <div class="author-profile-card">
                    <div class="author-avatar">
                        <?php echo get_avatar( $author_id, 150 ); ?>
                        <?php if ( $verified_badge ) : ?>
                            <span class="verified-badge" title="<?php esc_attr_e( 'Verified Seller', 'connectpro' ); ?>">
                                <i class="fas fa-check-circle"></i>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="author-info">
                        <h1 class="author-name"><?php echo esc_html( $author->display_name ); ?></h1>

                        <?php if ( $author_location ) : ?>
                            <div class="author-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo esc_html( $author_location ); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( $average_rating ) : ?>
                            <div class="author-rating">
                                <?php connectpro_display_rating( $average_rating ); ?>
                                <span class="rating-count">(<?php echo esc_html( $reviews_count ); ?> <?php esc_html_e( 'reviews', 'connectpro' ); ?>)</span>
                            </div>
                        <?php endif; ?>

                        <div class="author-member-since">
                            <i class="fas fa-calendar"></i>
                            <?php printf( __( 'Member since %s', 'connectpro' ), esc_html( $member_since ) ); ?>
                        </div>

                        <!-- Social Links -->
                        <?php if ( $author_facebook || $author_twitter || $author_instagram || $author_linkedin ) : ?>
                            <div class="author-social-links">
                                <?php if ( $author_facebook ) : ?>
                                    <a href="<?php echo esc_url( $author_facebook ); ?>" target="_blank" rel="noopener" class="social-link">
                                        <i class="fab fa-facebook"></i>
                                    </a>
                                <?php endif; ?>

                                <?php if ( $author_twitter ) : ?>
                                    <a href="<?php echo esc_url( $author_twitter ); ?>" target="_blank" rel="noopener" class="social-link">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                <?php endif; ?>

                                <?php if ( $author_instagram ) : ?>
                                    <a href="<?php echo esc_url( $author_instagram ); ?>" target="_blank" rel="noopener" class="social-link">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                <?php endif; ?>

                                <?php if ( $author_linkedin ) : ?>
                                    <a href="<?php echo esc_url( $author_linkedin ); ?>" target="_blank" rel="noopener" class="social-link">
                                        <i class="fab fa-linkedin"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( ! is_user_logged_in() || get_current_user_id() != $author_id ) : ?>
                            <div class="author-actions">
                                <a href="#" class="btn btn-primary contact-author" data-author-id="<?php echo esc_attr( $author_id ); ?>">
                                    <i class="fas fa-envelope"></i>
                                    <?php esc_html_e( 'Contact', 'connectpro' ); ?>
                                </a>

                                <button class="btn btn-outline follow-author" data-author-id="<?php echo esc_attr( $author_id ); ?>">
                                    <i class="fas fa-user-plus"></i>
                                    <?php esc_html_e( 'Follow', 'connectpro' ); ?>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Author Stats -->
                <div class="author-stats-grid">
                    <div class="stat-box">
                        <div class="stat-icon">
                            <i class="fas fa-list"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo esc_html( $listings_count ); ?></div>
                            <div class="stat-label"><?php esc_html_e( 'Listings', 'connectpro' ); ?></div>
                        </div>
                    </div>

                    <div class="stat-box">
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo esc_html( number_format( $average_rating, 1 ) ); ?></div>
                            <div class="stat-label"><?php esc_html_e( 'Rating', 'connectpro' ); ?></div>
                        </div>
                    </div>

                    <div class="stat-box">
                        <div class="stat-icon">
                            <i class="fas fa-comment"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo esc_html( $reviews_count ); ?></div>
                            <div class="stat-label"><?php esc_html_e( 'Reviews', 'connectpro' ); ?></div>
                        </div>
                    </div>

                    <div class="stat-box">
                        <div class="stat-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo esc_html( $response_rate ); ?>%</div>
                            <div class="stat-label"><?php esc_html_e( 'Response Rate', 'connectpro' ); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Author Content -->
    <section class="author-content">
        <div class="container">
            <div class="author-content-wrapper">
                <div class="author-main-content">
                    <!-- Tabs -->
                    <div class="author-tabs">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#listings" role="tab">
                                    <?php esc_html_e( 'Listings', 'connectpro' ); ?>
                                    <span class="badge"><?php echo esc_html( $listings_count ); ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#reviews" role="tab">
                                    <?php esc_html_e( 'Reviews', 'connectpro' ); ?>
                                    <span class="badge"><?php echo esc_html( $reviews_count ); ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#about" role="tab">
                                    <?php esc_html_e( 'About', 'connectpro' ); ?>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- Listings Tab -->
                            <div class="tab-pane fade show active" id="listings" role="tabpanel">
                                <div class="listings-grid">
                                    <?php
                                    $author_listings = new WP_Query( array(
                                        'post_type' => 'listing',
                                        'author' => $author_id,
                                        'posts_per_page' => 12,
                                        'post_status' => 'publish',
                                    ) );

                                    if ( $author_listings->have_posts() ) :
                                        while ( $author_listings->have_posts() ) :
                                            $author_listings->the_post();
                                            get_template_part( 'template-parts/listings/listing-card' );
                                        endwhile;
                                        wp_reset_postdata();
                                    else :
                                        echo '<p>' . __( 'No listings found.', 'connectpro' ) . '</p>';
                                    endif;
                                    ?>
                                </div>

                                <?php if ( $author_listings->max_num_pages > 1 ) : ?>
                                    <div class="pagination">
                                        <?php
                                        echo paginate_links( array(
                                            'total' => $author_listings->max_num_pages,
                                        ) );
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Reviews Tab -->
                            <div class="tab-pane fade" id="reviews" role="tabpanel">
                                <?php
                                $reviews = connectpro_get_author_reviews( $author_id );
                                if ( $reviews ) :
                                    ?>
                                    <div class="reviews-list">
                                        <?php foreach ( $reviews as $review ) : ?>
                                            <div class="review-item">
                                                <div class="review-header">
                                                    <div class="reviewer-avatar">
                                                        <?php echo get_avatar( $review->user_id, 60 ); ?>
                                                    </div>
                                                    <div class="reviewer-info">
                                                        <h4><?php echo esc_html( get_userdata( $review->user_id )->display_name ); ?></h4>
                                                        <div class="review-meta">
                                                            <?php connectpro_display_rating( $review->rating ); ?>
                                                            <span class="review-date"><?php echo esc_html( human_time_diff( strtotime( $review->date ), current_time( 'timestamp' ) ) ); ?> <?php esc_html_e( 'ago', 'connectpro' ); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="review-content">
                                                    <p><?php echo esc_html( $review->comment ); ?></p>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php
                                else :
                                    echo '<p>' . __( 'No reviews yet.', 'connectpro' ) . '</p>';
                                endif;
                                ?>
                            </div>

                            <!-- About Tab -->
                            <div class="tab-pane fade" id="about" role="tabpanel">
                                <div class="about-content">
                                    <?php if ( $author_description ) : ?>
                                        <div class="author-bio">
                                            <h3><?php esc_html_e( 'About Me', 'connectpro' ); ?></h3>
                                            <p><?php echo wp_kses_post( $author_description ); ?></p>
                                        </div>
                                    <?php endif; ?>

                                    <div class="author-details-grid">
                                        <?php if ( $author_website ) : ?>
                                            <div class="detail-item">
                                                <i class="fas fa-globe"></i>
                                                <a href="<?php echo esc_url( $author_website ); ?>" target="_blank" rel="noopener">
                                                    <?php echo esc_html( $author_website ); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ( $author_phone ) : ?>
                                            <div class="detail-item">
                                                <i class="fas fa-phone"></i>
                                                <a href="tel:<?php echo esc_attr( $author_phone ); ?>">
                                                    <?php echo esc_html( $author_phone ); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                        <div class="detail-item">
                                            <i class="fas fa-clock"></i>
                                            <?php printf( __( 'Response time: %s', 'connectpro' ), esc_html( $response_time ) ); ?>
                                        </div>

                                        <div class="detail-item">
                                            <i class="fas fa-check"></i>
                                            <?php printf( __( 'Response rate: %s%%', 'connectpro' ), esc_html( $response_rate ) ); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <aside class="author-sidebar">
                    <!-- Contact Form Widget -->
                    <div class="widget author-contact-widget">
                        <h3 class="widget-title"><?php esc_html_e( 'Contact Author', 'connectpro' ); ?></h3>
                        <form class="author-contact-form" data-author-id="<?php echo esc_attr( $author_id ); ?>">
                            <div class="form-group">
                                <input type="text" name="name" class="form-control" placeholder="<?php esc_attr_e( 'Your Name', 'connectpro' ); ?>" required>
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" class="form-control" placeholder="<?php esc_attr_e( 'Your Email', 'connectpro' ); ?>" required>
                            </div>
                            <div class="form-group">
                                <textarea name="message" class="form-control" rows="5" placeholder="<?php esc_attr_e( 'Your Message', 'connectpro' ); ?>" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">
                                <?php esc_html_e( 'Send Message', 'connectpro' ); ?>
                            </button>
                        </form>
                    </div>

                    <!-- Response Info Widget -->
                    <div class="widget response-info-widget">
                        <h3 class="widget-title"><?php esc_html_e( 'Response Info', 'connectpro' ); ?></h3>
                        <ul class="response-info-list">
                            <li>
                                <i class="fas fa-bolt"></i>
                                <strong><?php esc_html_e( 'Response Rate:', 'connectpro' ); ?></strong>
                                <?php echo esc_html( $response_rate ); ?>%
                            </li>
                            <li>
                                <i class="fas fa-clock"></i>
                                <strong><?php esc_html_e( 'Response Time:', 'connectpro' ); ?></strong>
                                <?php echo esc_html( $response_time ); ?>
                            </li>
                            <?php if ( $verified_badge ) : ?>
                                <li>
                                    <i class="fas fa-check-circle"></i>
                                    <strong><?php esc_html_e( 'Verified Seller', 'connectpro' ); ?></strong>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
                        <?php dynamic_sidebar( 'sidebar-1' ); ?>
                    <?php endif; ?>
                </aside>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
