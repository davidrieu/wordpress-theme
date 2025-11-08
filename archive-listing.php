<?php
/**
 * Archive Listing Template
 *
 * @package ConnectPro
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main listings-archive">
    <div class="container">
        <header class="page-header">
            <h1 class="page-title"><?php esc_html_e( 'All Listings', 'connectpro' ); ?></h1>
            <div class="archive-description">
                <p><?php esc_html_e( 'Browse all available listings', 'connectpro' ); ?></p>
            </div>
        </header>

        <div class="listings-filter-bar">
            <?php echo do_shortcode( '[listing_search]' ); ?>
        </div>

        <?php if ( have_posts() ) : ?>

            <div class="listings-grid">
                <?php
                while ( have_posts() ) :
                    the_post();
                    get_template_part( 'template-parts/listings/listing-card' );
                endwhile;
                ?>
            </div>

            <?php
            the_posts_pagination( array(
                'mid_size'  => 2,
                'prev_text' => __( '&laquo; Previous', 'connectpro' ),
                'next_text' => __( 'Next &raquo;', 'connectpro' ),
            ) );

        else :

            echo '<div class="no-listings-found">';
            echo '<h2>' . esc_html__( 'No Listings Found', 'connectpro' ) . '</h2>';
            echo '<p>' . esc_html__( 'There are no listings available at the moment. Please check back later.', 'connectpro' ) . '</p>';
            echo '</div>';

        endif;
        ?>
    </div>
</main>

<?php
get_footer();
