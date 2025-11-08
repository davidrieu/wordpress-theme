<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @package ConnectPro
 * @since 1.0.0
 */
?>

<section class="no-results not-found">
    <header class="page-header">
        <h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'connectpro' ); ?></h1>
    </header>

    <div class="page-content">
        <?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

            <p>
                <?php
                printf(
                    wp_kses(
                        /* translators: %s: URL to create a new post */
                        __( 'Ready to publish your first post? <a href="%s">Get started here</a>.', 'connectpro' ),
                        array(
                            'a' => array(
                                'href' => array(),
                            ),
                        )
                    ),
                    esc_url( admin_url( 'post-new.php' ) )
                );
                ?>
            </p>

        <?php elseif ( is_search() ) : ?>

            <p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with different keywords.', 'connectpro' ); ?></p>
            <?php get_search_form(); ?>

        <?php else : ?>

            <p><?php esc_html_e( 'It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'connectpro' ); ?></p>
            <?php get_search_form(); ?>

        <?php endif; ?>
    </div>
</section>
