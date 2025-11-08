/**
 * Dark Mode Toggle Functionality
 *
 * @package ConnectPro
 * @since 1.0.0
 */

(function($) {
    'use strict';

    var darkModeEnabled = false;
    var autoMode = connectproDarkMode.auto || false;

    /**
     * Initialize Dark Mode
     */
    $(document).ready(function() {
        // Check for saved preference
        var savedMode = localStorage.getItem('connectpro-dark-mode');

        if (autoMode) {
            // Auto mode based on system preference
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                enableDarkMode();
            }

            // Listen for system preference changes
            if (window.matchMedia) {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                    if (e.matches) {
                        enableDarkMode();
                    } else {
                        disableDarkMode();
                    }
                });
            }
        } else if (savedMode === 'enabled') {
            // Manual mode with saved preference
            enableDarkMode();
        }

        // Toggle button click
        $('#dark-mode-toggle').on('click', function() {
            toggleDarkMode();
        });
    });

    /**
     * Toggle Dark Mode
     */
    function toggleDarkMode() {
        if (darkModeEnabled) {
            disableDarkMode();
        } else {
            enableDarkMode();
        }
    }

    /**
     * Enable Dark Mode
     */
    function enableDarkMode() {
        $('body').addClass('dark-mode');
        darkModeEnabled = true;

        // Update toggle button icons
        $('.sun-icon').hide();
        $('.moon-icon').show();

        // Save preference
        localStorage.setItem('connectpro-dark-mode', 'enabled');

        // Update map style if maps are loaded
        updateMapStyle('dark');

        // Trigger custom event
        $(document).trigger('connectpro:darkModeEnabled');
    }

    /**
     * Disable Dark Mode
     */
    function disableDarkMode() {
        $('body').removeClass('dark-mode');
        darkModeEnabled = false;

        // Update toggle button icons
        $('.sun-icon').show();
        $('.moon-icon').hide();

        // Save preference
        localStorage.setItem('connectpro-dark-mode', 'disabled');

        // Update map style if maps are loaded
        updateMapStyle('standard');

        // Trigger custom event
        $(document).trigger('connectpro:darkModeDisabled');
    }

    /**
     * Update Google Maps Style
     */
    function updateMapStyle(style) {
        if (typeof window.connectproMapInstances !== 'undefined' && window.connectproMapInstances.length > 0) {
            window.connectproMapInstances.forEach(function(instance) {
                if (instance.map) {
                    var styles = getMapStyles(style);
                    instance.map.setOptions({styles: styles});
                }
            });
        }
    }

    /**
     * Get Map Styles
     */
    function getMapStyles(styleName) {
        if (typeof connectproMaps === 'undefined') {
            return [];
        }

        var styles = {
            'standard': [],
            'dark': JSON.parse(connectproMaps.dark_style || '[]')
        };

        return styles[styleName] || [];
    }

    /**
     * Check if Dark Mode is Enabled
     */
    function isDarkModeEnabled() {
        return darkModeEnabled;
    }

    // Export functions for global use
    window.connectproDarkMode = {
        toggle: toggleDarkMode,
        enable: enableDarkMode,
        disable: disableDarkMode,
        isEnabled: isDarkModeEnabled
    };

})(jQuery);
