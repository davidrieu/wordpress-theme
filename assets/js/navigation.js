/**
 * Navigation JavaScript
 *
 * @package ConnectPro
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Keyboard navigation for menus
     */
    var navigation = document.querySelector('.main-navigation');
    if (!navigation) {
        return;
    }

    var menu = navigation.querySelector('ul');
    if (!menu) {
        return;
    }

    // Get all the link elements within the menu
    var links = menu.querySelectorAll('a');
    var subMenus = menu.querySelectorAll('.sub-menu');

    // Set menu items with submenus to aria-haspopup="true"
    for (var i = 0; i < subMenus.length; i++) {
        subMenus[i].parentNode.querySelector('a').setAttribute('aria-haspopup', 'true');
    }

    // Each time a menu link is focused or blurred, toggle focus
    for (i = 0; i < links.length; i++) {
        links[i].addEventListener('focus', toggleFocus, true);
        links[i].addEventListener('blur', toggleFocus, true);
    }

    /**
     * Sets or removes .focus class on an element
     */
    function toggleFocus() {
        var self = this;

        // Move up through the ancestors of the current link until we hit .nav-menu
        while (-1 === self.className.indexOf('main-navigation')) {
            // On li elements toggle the class .focus
            if ('li' === self.tagName.toLowerCase()) {
                if (-1 !== self.className.indexOf('focus')) {
                    self.className = self.className.replace(' focus', '');
                } else {
                    self.className += ' focus';
                }
            }
            self = self.parentElement;
        }
    }

    /**
     * Toggles `focus` class to allow submenu access on tablets
     */
    (function(container) {
        var touchStartFn,
            i,
            parentLink = container.querySelectorAll('.menu-item-has-children > a, .page_item_has_children > a');

        if ('ontouchstart' in window) {
            touchStartFn = function(e) {
                var menuItem = this.parentNode,
                    i;

                if (!menuItem.classList.contains('focus')) {
                    e.preventDefault();
                    for (i = 0; i < menuItem.parentNode.children.length; ++i) {
                        if (menuItem === menuItem.parentNode.children[i]) {
                            continue;
                        }
                        menuItem.parentNode.children[i].classList.remove('focus');
                    }
                    menuItem.classList.add('focus');
                } else {
                    menuItem.classList.remove('focus');
                }
            };

            for (i = 0; i < parentLink.length; ++i) {
                parentLink[i].addEventListener('touchstart', touchStartFn, false);
            }
        }
    })(navigation);

})(jQuery);
