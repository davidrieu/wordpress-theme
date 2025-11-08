<?php
/**
 * Dashboard Footer
 *
 * Simplified footer for dashboard pages
 *
 * @package ConnectPro
 * @since 1.0.0
 */
?>

    </div><!-- #content -->

    <footer id="dashboard-footer" class="dashboard-footer">
        <div class="dashboard-footer-inner">
            <div class="footer-left">
                <p>
                    <?php
                    /* translators: %1$s: Current year, %2$s: Site name */
                    printf(
                        esc_html__( '&copy; %1$s %2$s. All rights reserved.', 'connectpro' ),
                        date( 'Y' ),
                        get_bloginfo( 'name' )
                    );
                    ?>
                </p>
            </div>

            <div class="footer-right">
                <nav class="dashboard-footer-nav">
                    <a href="<?php echo esc_url( home_url( '/about' ) ); ?>"><?php esc_html_e( 'About', 'connectpro' ); ?></a>
                    <a href="<?php echo esc_url( home_url( '/help' ) ); ?>"><?php esc_html_e( 'Help', 'connectpro' ); ?></a>
                    <a href="<?php echo esc_url( home_url( '/privacy-policy' ) ); ?>"><?php esc_html_e( 'Privacy', 'connectpro' ); ?></a>
                    <a href="<?php echo esc_url( home_url( '/terms' ) ); ?>"><?php esc_html_e( 'Terms', 'connectpro' ); ?></a>
                </nav>
            </div>
        </div>
    </footer>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
