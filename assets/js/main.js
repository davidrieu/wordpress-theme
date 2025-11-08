/**
 * Main JavaScript
 *
 * @package ConnectPro
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Document Ready
     */
    $(document).ready(function() {

        // Mobile Menu Toggle
        $('.menu-toggle').on('click', function() {
            $('.main-navigation').toggleClass('active');
            $(this).toggleClass('active');
        });

        // Mobile Submenu Toggle
        $('.menu-item-has-children > a').on('click', function(e) {
            if ($(window).width() <= 768) {
                e.preventDefault();
                $(this).parent().toggleClass('active');
            }
        });

        // Search Modal
        $('.search-toggle').on('click', function() {
            $('.search-modal').addClass('active');
            $('.search-field').focus();
        });

        $('.search-modal-close, .search-modal').on('click', function(e) {
            if (e.target === this) {
                $('.search-modal').removeClass('active');
            }
        });

        // Close search modal on Esc key
        $(document).on('keyup', function(e) {
            if (e.key === 'Escape') {
                $('.search-modal').removeClass('active');
            }
        });

        // Back to Top Button
        var backToTop = $('.back-to-top');

        $(window).on('scroll', function() {
            if ($(this).scrollTop() > 300) {
                backToTop.addClass('visible');
            } else {
                backToTop.removeClass('visible');
            }
        });

        backToTop.on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({ scrollTop: 0 }, 600);
        });

        // Sticky Header
        var header = $('.main-header');
        var headerOffset = header.offset().top;

        $(window).on('scroll', function() {
            if ($(this).scrollTop() > headerOffset) {
                header.addClass('sticky');
            } else {
                header.removeClass('sticky');
            }
        });

        // Smooth Scroll for Anchor Links
        $('a[href^="#"]').on('click', function(e) {
            var target = $(this.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 100
                }, 600);
            }
        });

        // Listing Favorites
        $('.listing-favorite').on('click', function(e) {
            e.preventDefault();
            var button = $(this);
            var listingId = button.data('listing-id');

            $.ajax({
                url: connectproData.ajax_url,
                type: 'POST',
                data: {
                    action: 'toggle_favorite',
                    nonce: connectproData.nonce,
                    listing_id: listingId
                },
                success: function(response) {
                    if (response.success) {
                        button.toggleClass('active');
                        if (response.data.favorited) {
                            button.html('<svg width="20" height="20" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>');
                        } else {
                            button.html('<svg width="20" height="20" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>');
                        }
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        });

        // Listing Search/Filter
        $('#listing-search-form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);

            $.ajax({
                url: connectproData.ajax_url,
                type: 'GET',
                data: form.serialize() + '&action=filter_listings',
                beforeSend: function() {
                    $('.listings-grid').addClass('loading');
                },
                success: function(response) {
                    if (response.success) {
                        $('.listings-grid').html(response.data.html);
                    }
                },
                complete: function() {
                    $('.listings-grid').removeClass('loading');
                }
            });
        });

        // Image Lazy Loading
        if ('IntersectionObserver' in window) {
            var imageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        var image = entry.target;
                        image.src = image.dataset.src;
                        image.classList.remove('lazy');
                        imageObserver.unobserve(image);
                    }
                });
            });

            document.querySelectorAll('img.lazy').forEach(function(img) {
                imageObserver.observe(img);
            });
        }

        // Form Validation
        $('form.needs-validation').on('submit', function(e) {
            var form = $(this);

            if (!form[0].checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }

            form.addClass('was-validated');
        });

        // Auto-hide messages
        $('.notice, .alert').delay(5000).fadeOut(300);

        // Prevent double form submission
        $('form').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true);
        });

        // Initialize tooltips (if using Bootstrap or similar)
        if (typeof $.fn.tooltip !== 'undefined') {
            $('[data-toggle="tooltip"]').tooltip();
        }

        // Accessibility: Skip to content
        $('.skip-link').on('click', function(e) {
            var target = $(this.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                target.attr('tabindex', '-1').focus();
            }
        });

    });

    /**
     * Window Load
     */
    $(window).on('load', function() {
        // Remove preloader if exists
        $('.preloader').fadeOut(300);
    });

    /**
     * Window Resize
     */
    var resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            // Close mobile menu on resize to desktop
            if ($(window).width() > 768) {
                $('.main-navigation').removeClass('active');
                $('.menu-toggle').removeClass('active');
            }
        }, 250);
    });

})(jQuery);
