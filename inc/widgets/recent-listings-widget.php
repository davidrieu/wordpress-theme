<?php
/**
 * Recent Listings Widget
 *
 * @package ConnectPro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class ConnectPro_Recent_Listings_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'connectpro_recent_listings',
            __( 'ConnectPro: Recent Listings', 'connectpro' ),
            array( 'description' => __( 'Display recent listings', 'connectpro' ) )
        );
    }

    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Recent Listings', 'connectpro' );
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;

        echo $args['before_widget'];

        if ( ! empty( $title ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $title ) . $args['after_title'];
        }

        $query_args = array(
            'post_type'      => 'listing',
            'posts_per_page' => $number,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'post_status'    => 'publish',
        );

        $listings = new WP_Query( $query_args );

        if ( $listings->have_posts() ) {
            echo '<ul class="recent-listings-widget">';

            while ( $listings->have_posts() ) {
                $listings->the_post();
                $price = connectpro_get_listing_price( get_the_ID() );
                ?>
                <li>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="widget-listing-thumb">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail( 'thumbnail' ); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="widget-listing-content">
                        <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                        <?php if ( $price ) : ?>
                            <span class="widget-listing-price"><?php echo esc_html( $price ); ?></span>
                        <?php endif; ?>
                    </div>
                </li>
                <?php
            }

            echo '</ul>';

            wp_reset_postdata();
        } else {
            echo '<p>' . esc_html__( 'No listings found.', 'connectpro' ) . '</p>';
        }

        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Recent Listings', 'connectpro' );
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'connectpro' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of listings:', 'connectpro' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3">
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['number'] = ! empty( $new_instance['number'] ) ? absint( $new_instance['number'] ) : 5;

        return $instance;
    }
}

function connectpro_register_recent_listings_widget() {
    register_widget( 'ConnectPro_Recent_Listings_Widget' );
}
add_action( 'widgets_init', 'connectpro_register_recent_listings_widget' );
