/**
 * Admin JavaScript
 *
 * @package ConnectPro
 * @since 1.0.0
 */

(function($) {
    'use strict';

    $(document).ready(function() {

        // Settings Tabs
        $('.connectpro-settings-tabs a').on('click', function(e) {
            e.preventDefault();

            var tab = $(this).attr('href');

            $('.connectpro-settings-tabs a').removeClass('active');
            $(this).addClass('active');

            $('.connectpro-settings-panel').hide();
            $(tab).show();
        });

        // Image Upload
        $('.connectpro-upload-image').on('click', function(e) {
            e.preventDefault();

            var button = $(this);
            var custom_uploader = wp.media({
                title: 'Select Image',
                button: {
                    text: 'Use this image'
                },
                multiple: false
            })
            .on('select', function() {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                button.siblings('input').val(attachment.url);
                button.siblings('.image-preview').html('<img src="' + attachment.url + '" style="max-width: 200px;">');
            })
            .open();
        });

        // Color Picker
        if ($.fn.wpColorPicker) {
            $('.connectpro-color-picker').wpColorPicker();
        }

        // Sortable
        if ($.fn.sortable) {
            $('.connectpro-sortable').sortable({
                placeholder: 'sortable-placeholder',
                handle: '.sortable-handle'
            });
        }

        // Repeater Field
        $('.connectpro-add-repeater').on('click', function(e) {
            e.preventDefault();

            var container = $(this).siblings('.connectpro-repeater-container');
            var template = container.find('.repeater-template').html();

            container.find('.repeater-items').append(template);
        });

        $(document).on('click', '.connectpro-remove-repeater', function(e) {
            e.preventDefault();
            $(this).closest('.repeater-item').remove();
        });

        // Toggle Sections
        $('.connectpro-toggle-section').on('click', function() {
            $(this).toggleClass('active');
            $(this).next('.toggle-content').slideToggle();
        });

        // Confirm Delete
        $('.connectpro-delete-confirm').on('click', function(e) {
            if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                e.preventDefault();
                return false;
            }
        });

        // AJAX Save Settings
        $('#connectpro-save-settings').on('click', function(e) {
            e.preventDefault();

            var button = $(this);
            var form = button.closest('form');

            button.prop('disabled', true).text('Saving...');

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: form.serialize() + '&action=connectpro_save_settings',
                success: function(response) {
                    if (response.success) {
                        button.prop('disabled', false).text('Save Changes');
                        $('.connectpro-notice').remove();
                        form.prepend('<div class="notice notice-success connectpro-notice"><p>Settings saved successfully!</p></div>');
                    }
                },
                error: function() {
                    button.prop('disabled', false).text('Save Changes');
                    alert('An error occurred. Please try again.');
                }
            });
        });

    });

})(jQuery);
