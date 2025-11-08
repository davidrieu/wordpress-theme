<?php
/**
 * The main template file
 *
 * @package ConnectPro
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <div class="content-area">
            <?php
            if ( have_posts() ) :

                // Page header
                if ( is_home() && ! is_front_page() ) :
                    ?>
                    <header class="page-header">
                        <h1 class="page-title"><?php single_post_title(); ?></h1>
                    </header>
                    <?php
                endif;

                // Start the Loop
                while ( have_posts() ) :
                    the_post();

                    /*
                     * Include the Post-Type-specific template for the content.
                     * If you want to override this in a child theme, then include a file
                     * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                     */
                    get_template_part( 'template-parts/content/content', get_post_type() );

                endwhile;

                // Pagination
                the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => __( '&laquo; Previous', 'connectpro' ),
                    'next_text' => __( 'Next &raquo;', 'connectpro' ),
                ) );

            else :

                get_template_part( 'template-parts/content/content', 'none' );

            endif;
            ?>
        </div>

        <?php get_sidebar(); ?>
    </div>
</main>

<?php
get_footer();
