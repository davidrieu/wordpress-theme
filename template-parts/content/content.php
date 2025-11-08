<?php
/**
 * Template part for displaying posts
 *
 * @package ConnectPro
 * @since 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-item' ); ?>>
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail( 'large' ); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="post-content">
        <header class="entry-header">
            <?php
            if ( is_singular() ) :
                the_title( '<h1 class="entry-title">', '</h1>' );
            else :
                the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
            endif;
            ?>

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
                <?php if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) : ?>
                    <span class="comments-link">
                        <?php comments_popup_link( esc_html__( 'Leave a comment', 'connectpro' ), esc_html__( '1 Comment', 'connectpro' ), esc_html__( '% Comments', 'connectpro' ) ); ?>
                    </span>
                <?php endif; ?>
            </div>
        </header>

        <div class="entry-content">
            <?php
            if ( is_singular() ) :
                the_content();

                wp_link_pages( array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'connectpro' ),
                    'after'  => '</div>',
                ) );
            else :
                the_excerpt();
                ?>
                <a href="<?php the_permalink(); ?>" class="read-more">
                    <?php esc_html_e( 'Read More', 'connectpro' ); ?>
                </a>
                <?php
            endif;
            ?>
        </div>

        <?php if ( is_singular() && get_the_tags() ) : ?>
            <footer class="entry-footer">
                <?php
                the_tags( '<div class="tags-links"><span class="tags-title">' . esc_html__( 'Tags:', 'connectpro' ) . '</span> ', ', ', '</div>' );
                ?>
            </footer>
        <?php endif; ?>
    </div>
</article>
