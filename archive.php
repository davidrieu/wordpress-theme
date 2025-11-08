<?php
/**
 * The template for displaying archive pages
 *
 * @package ConnectPro
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php if ( have_posts() ) : ?>

            <header class="page-header">
                <?php
                the_archive_title( '<h1 class="page-title">', '</h1>' );
                the_archive_description( '<div class="archive-description">', '</div>' );
                ?>
            </header>

            <div class="archive-grid">
                <?php
                while ( have_posts() ) :
                    the_post();
                    get_template_part( 'template-parts/content/content', get_post_type() );
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

            get_template_part( 'template-parts/content/content', 'none' );

        endif;
        ?>
    </div>
</main>

<?php
get_footer();
