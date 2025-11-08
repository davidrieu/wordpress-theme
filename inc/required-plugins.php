<?php
/**
 * Required Plugins Configuration
 *
 * @package ConnectPro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register required and recommended plugins
 */
function connectpro_register_required_plugins() {
    $plugins = array(
        // Required Plugins
        array(
            'name'     => 'Elementor',
            'slug'     => 'elementor',
            'required' => true,
        ),
        array(
            'name'     => 'WooCommerce',
            'slug'     => 'woocommerce',
            'required' => true,
        ),
        array(
            'name'     => 'Contact Form 7',
            'slug'     => 'contact-form-7',
            'required' => true,
        ),

        // Recommended Plugins
        array(
            'name'     => 'Yoast SEO',
            'slug'     => 'wordpress-seo',
            'required' => false,
        ),
        array(
            'name'     => 'WP Super Cache',
            'slug'     => 'wp-super-cache',
            'required' => false,
        ),
        array(
            'name'     => 'Wordfence Security',
            'slug'     => 'wordfence',
            'required' => false,
        ),
        array(
            'name'     => 'UpdraftPlus',
            'slug'     => 'updraftplus',
            'required' => false,
        ),
    );

    $config = array(
        'id'           => 'connectpro',
        'default_path' => '',
        'menu'         => 'tgmpa-install-plugins',
        'has_notices'  => true,
        'dismissable'  => true,
        'dismiss_msg'  => '',
        'is_automatic' => false,
        'message'      => '',
        'strings'      => array(
            'page_title'                      => __( 'Install Required Plugins', 'connectpro' ),
            'menu_title'                      => __( 'Install Plugins', 'connectpro' ),
            'installing'                      => __( 'Installing Plugin: %s', 'connectpro' ),
            'updating'                        => __( 'Updating Plugin: %s', 'connectpro' ),
            'oops'                            => __( 'Something went wrong with the plugin API.', 'connectpro' ),
            'notice_can_install_required'     => _n_noop(
                'This theme requires the following plugin: %1$s.',
                'This theme requires the following plugins: %1$s.',
                'connectpro'
            ),
            'notice_can_install_recommended'  => _n_noop(
                'This theme recommends the following plugin: %1$s.',
                'This theme recommends the following plugins: %1$s.',
                'connectpro'
            ),
            'notice_ask_to_update'            => _n_noop(
                'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
                'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
                'connectpro'
            ),
            'notice_ask_to_update_maybe'      => _n_noop(
                'There is an update available for: %1$s.',
                'There are updates available for the following plugins: %1$s.',
                'connectpro'
            ),
            'notice_can_activate_required'    => _n_noop(
                'The following required plugin is currently inactive: %1$s.',
                'The following required plugins are currently inactive: %1$s.',
                'connectpro'
            ),
            'notice_can_activate_recommended' => _n_noop(
                'The following recommended plugin is currently inactive: %1$s.',
                'The following recommended plugins are currently inactive: %1$s.',
                'connectpro'
            ),
            'install_link'                    => _n_noop(
                'Begin installing plugin',
                'Begin installing plugins',
                'connectpro'
            ),
            'update_link'                     => _n_noop(
                'Begin updating plugin',
                'Begin updating plugins',
                'connectpro'
            ),
            'activate_link'                   => _n_noop(
                'Begin activating plugin',
                'Begin activating plugins',
                'connectpro'
            ),
            'return'                          => __( 'Return to Required Plugins Installer', 'connectpro' ),
            'plugin_activated'                => __( 'Plugin activated successfully.', 'connectpro' ),
            'activated_successfully'          => __( 'The following plugin was activated successfully:', 'connectpro' ),
            'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'connectpro' ),
            'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'connectpro' ),
            'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'connectpro' ),
            'dismiss'                         => __( 'Dismiss this notice', 'connectpro' ),
            'notice_cannot_install_activate'  => __( 'There are one or more required or recommended plugins to install, update or activate.', 'connectpro' ),
            'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'connectpro' ),
            'nag_type'                        => 'updated',
        ),
    );

    tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'connectpro_register_required_plugins' );
