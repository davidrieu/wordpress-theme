/**
 * Customizer Live Preview
 *
 * @package ConnectPro
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Update primary color
    wp.customize('primary_color', function(value) {
        value.bind(function(newval) {
            $('style#primary-color-css').remove();
            $('head').append('<style id="primary-color-css">:root { --primary-color: ' + newval + '; }</style>');
        });
    });

    // Update secondary color
    wp.customize('secondary_color', function(value) {
        value.bind(function(newval) {
            $('style#secondary-color-css').remove();
            $('head').append('<style id="secondary-color-css">:root { --secondary-color: ' + newval + '; }</style>');
        });
    });

    // Update accent color
    wp.customize('accent_color', function(value) {
        value.bind(function(newval) {
            $('style#accent-color-css').remove();
            $('head').append('<style id="accent-color-css">:root { --accent-color: ' + newval + '; }</style>');
        });
    });

    // Update body font size
    wp.customize('body_font_size', function(value) {
        value.bind(function(newval) {
            $('body').css('font-size', newval + 'px');
        });
    });

    // Update logo width
    wp.customize('logo_width', function(value) {
        value.bind(function(newval) {
            $('.custom-logo').css('max-width', newval + 'px');
        });
    });

    // Update footer copyright
    wp.customize('footer_copyright', function(value) {
        value.bind(function(newval) {
            $('.copyright').html(newval);
        });
    });

})(jQuery);
