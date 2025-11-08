<?php
/**
 * Listing Search Widget
 *
 * @package ConnectPro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class ConnectPro_Listing_Search_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'connectpro_listing_search',
            __( 'ConnectPro: Listing Search', 'connectpro' ),
            array( 'description' => __( 'Advanced listing search form', 'connectpro' ) )
        );
    }

    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Search Listings', 'connectpro' );

        echo $args['before_widget'];

        if ( ! empty( $title ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $title ) . $args['after_title'];
        }

        echo do_shortcode( '[listing_search]' );

        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Search Listings', 'connectpro' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'connectpro' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';

        return $instance;
    }
}

function connectpro_register_listing_search_widget() {
    register_widget( 'ConnectPro_Listing_Search_Widget' );
}
add_action( 'widgets_init', 'connectpro_register_listing_search_widget' );
