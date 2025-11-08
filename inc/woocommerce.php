<?php
/**
 * WooCommerce Integration
 *
 * @package ConnectPro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Remove default WooCommerce wrappers
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
 * Add custom WooCommerce wrappers
 */
add_action( 'woocommerce_before_main_content', 'connectpro_woocommerce_wrapper_start', 10 );
add_action( 'woocommerce_after_main_content', 'connectpro_woocommerce_wrapper_end', 10 );

function connectpro_woocommerce_wrapper_start() {
    echo '<div id="primary" class="site-main woocommerce-page"><div class="container">';
}

function connectpro_woocommerce_wrapper_end() {
    echo '</div></div>';
}

/**
 * Change number of products per row
 */
function connectpro_woocommerce_loop_columns() {
    return 3;
}
add_filter( 'loop_shop_columns', 'connectpro_woocommerce_loop_columns' );

/**
 * Change number of products per page
 */
function connectpro_woocommerce_products_per_page() {
    return 12;
}
add_filter( 'loop_shop_per_page', 'connectpro_woocommerce_products_per_page', 20 );

/**
 * Remove WooCommerce breadcrumbs
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/**
 * Add WooCommerce cart to header
 */
function connectpro_woocommerce_cart_link() {
    if ( ! function_exists( 'WC' ) ) {
        return;
    }

    $cart_count = WC()->cart->get_cart_contents_count();
    ?>
    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-link">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        <?php if ( $cart_count > 0 ) : ?>
            <span class="cart-count"><?php echo esc_html( $cart_count ); ?></span>
        <?php endif; ?>
    </a>
    <?php
}

/**
 * Update cart count via AJAX
 */
function connectpro_woocommerce_update_cart_count() {
    if ( ! function_exists( 'WC' ) ) {
        return;
    }

    add_filter( 'woocommerce_add_to_cart_fragments', function( $fragments ) {
        ob_start();
        connectpro_woocommerce_cart_link();
        $fragments['.cart-link'] = ob_get_clean();
        return $fragments;
    });
}
add_action( 'wp_footer', 'connectpro_woocommerce_update_cart_count' );

/**
 * Customize product archive title
 */
function connectpro_woocommerce_show_page_title() {
    return true;
}
add_filter( 'woocommerce_show_page_title', 'connectpro_woocommerce_show_page_title' );

/**
 * Customize sale badge
 */
function connectpro_custom_sale_badge( $html, $post, $product ) {
    if ( $product->is_on_sale() ) {
        if ( $product->is_type( 'variable' ) ) {
            $html = '<span class="onsale">' . esc_html__( 'Sale!', 'connectpro' ) . '</span>';
        } else {
            $percentage = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
            $html = '<span class="onsale">-' . $percentage . '%</span>';
        }
    }
    return $html;
}
add_filter( 'woocommerce_sale_flash', 'connectpro_custom_sale_badge', 10, 3 );

/**
 * Add custom product fields
 */
function connectpro_custom_product_fields() {
    global $post;

    echo '<div class="product-custom-fields">';

    woocommerce_wp_text_input( array(
        'id'          => '_product_condition',
        'label'       => __( 'Condition', 'connectpro' ),
        'placeholder' => __( 'New, Used, Refurbished', 'connectpro' ),
        'desc_tip'    => true,
        'description' => __( 'Enter product condition', 'connectpro' ),
    ) );

    woocommerce_wp_text_input( array(
        'id'          => '_product_warranty',
        'label'       => __( 'Warranty', 'connectpro' ),
        'placeholder' => __( '1 Year, 2 Years', 'connectpro' ),
        'desc_tip'    => true,
        'description' => __( 'Enter warranty information', 'connectpro' ),
    ) );

    echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'connectpro_custom_product_fields' );

/**
 * Save custom product fields
 */
function connectpro_save_custom_product_fields( $post_id ) {
    $product_condition = isset( $_POST['_product_condition'] ) ? sanitize_text_field( $_POST['_product_condition'] ) : '';
    $product_warranty = isset( $_POST['_product_warranty'] ) ? sanitize_text_field( $_POST['_product_warranty'] ) : '';

    update_post_meta( $post_id, '_product_condition', $product_condition );
    update_post_meta( $post_id, '_product_warranty', $product_warranty );
}
add_action( 'woocommerce_process_product_meta', 'connectpro_save_custom_product_fields' );

/**
 * Display custom product fields on frontend
 */
function connectpro_display_custom_product_fields() {
    global $product;

    $condition = get_post_meta( $product->get_id(), '_product_condition', true );
    $warranty = get_post_meta( $product->get_id(), '_product_warranty', true );

    if ( $condition || $warranty ) {
        echo '<div class="product-meta-custom">';

        if ( $condition ) {
            echo '<div class="product-condition"><strong>' . esc_html__( 'Condition:', 'connectpro' ) . '</strong> ' . esc_html( $condition ) . '</div>';
        }

        if ( $warranty ) {
            echo '<div class="product-warranty"><strong>' . esc_html__( 'Warranty:', 'connectpro' ) . '</strong> ' . esc_html( $warranty ) . '</div>';
        }

        echo '</div>';
    }
}
add_action( 'woocommerce_single_product_summary', 'connectpro_display_custom_product_fields', 25 );

/**
 * Enqueue WooCommerce custom styles
 */
function connectpro_woocommerce_styles() {
    wp_enqueue_style( 'connectpro-woocommerce', CONNECTPRO_THEME_URI . '/assets/css/woocommerce.css', array(), CONNECTPRO_VERSION );
}
add_action( 'wp_enqueue_scripts', 'connectpro_woocommerce_styles' );
