<?php
/**
 * Template part for displaying single posts
 *
 * @package ConnectPro
 * @since 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post' ); ?>>
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="post-thumbnail">
            <?php the_post_thumbnail( 'full' ); ?>
        </div>
    <?php endif; ?>

    <header class="entry-header">
        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

        <div class="entry-meta">
            <span class="posted-on">
                <time class="entry-date published" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                    <?php echo esc_html( get_the_date() ); ?>
                </time>
            </span>
            <span class="byline">
                <?php
                printf(
                    esc_html__( 'by %s', 'connectpro' ),
                    '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a>'
                );
                ?>
            </span>
            <span class="cat-links">
                <?php
                $categories_list = get_the_category_list( ', ' );
                if ( $categories_list ) {
                    printf( esc_html__( 'in %s', 'connectpro' ), $categories_list );
                }
                ?>
            </span>
        </div>
    </header>

    <div class="entry-content">
        <?php
        the_content();

        wp_link_pages( array(
            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'connectpro' ),
            'after'  => '</div>',
        ) );
        ?>
    </div>

    <?php if ( get_the_tags() ) : ?>
        <footer class="entry-footer">
            <?php
            the_tags( '<div class="tags-links"><span class="tags-title">' . esc_html__( 'Tags:', 'connectpro' ) . '</span> ', ', ', '</div>' );
            ?>
        </footer>
    <?php endif; ?>
</article>
