/**
 * Reviews JavaScript
 *
 * @package ConnectPro
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Initialize
     */
    $(document).ready(function() {
        // Review form submission
        $('#review-form').on('submit', handleReviewSubmit);

        // Mark review as helpful
        $('.mark-helpful').on('click', handleMarkHelpful);

        // Star rating hover effect
        initStarRating();
    });

    /**
     * Initialize Star Rating Input
     */
    function initStarRating() {
        $('.star-rating-input label').on('mouseenter', function() {
            var $label = $(this);
            $label.addClass('hover');
            $label.nextAll('label').addClass('hover');
        });

        $('.star-rating-input').on('mouseleave', function() {
            $('.star-rating-input label').removeClass('hover');
        });
    }

    /**
     * Handle Review Form Submission
     */
    function handleReviewSubmit(e) {
        e.preventDefault();

        var $form = $(this);
        var $submitButton = $form.find('button[type="submit"]');
        var originalText = $submitButton.text();

        // Disable button
        $submitButton.prop('disabled', true).text('Submitting...');

        // Clear previous messages
        $('.review-message').remove();

        var formData = {
            action: 'submit_review',
            nonce: connectproReviews.nonce,
            listing_id: $form.data('listing-id'),
            rating: $form.find('input[name="rating"]:checked').val(),
            title: $form.find('input[name="title"]').val(),
            comment: $form.find('textarea[name="comment"]').val()
        };

        // Validate
        if (!formData.rating) {
            showMessage($form, 'error', 'Please select a rating.');
            $submitButton.prop('disabled', false).text(originalText);
            return;
        }

        if (!formData.comment.trim()) {
            showMessage($form, 'error', 'Please write a review.');
            $submitButton.prop('disabled', false).text(originalText);
            return;
        }

        $.ajax({
            url: connectproReviews.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    showMessage($form, 'success', response.data.message);

                    // Reset form
                    $form[0].reset();

                    // Reload page after 2 seconds to show new review
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else {
                    showMessage($form, 'error', response.data.message);
                }
            },
            error: function() {
                showMessage($form, 'error', 'An error occurred. Please try again.');
            },
            complete: function() {
                $submitButton.prop('disabled', false).text(originalText);
            }
        });
    }

    /**
     * Handle Mark Review Helpful
     */
    function handleMarkHelpful(e) {
        e.preventDefault();

        var $button = $(this);
        var reviewId = $button.data('review-id');

        // Check if already marked (using localStorage)
        var helpfulKey = 'review_helpful_' + reviewId;
        if (localStorage.getItem(helpfulKey)) {
            alert('You have already marked this review as helpful.');
            return;
        }

        $.ajax({
            url: connectproReviews.ajax_url,
            type: 'POST',
            data: {
                action: 'mark_review_helpful',
                nonce: connectproReviews.nonce,
                review_id: reviewId
            },
            success: function(response) {
                if (response.success) {
                    // Update count
                    var $count = $button.find('.helpful-count');
                    if ($count.length) {
                        $count.text('(' + response.data.count + ')');
                    } else {
                        $button.append('<span class="helpful-count">(' + response.data.count + ')</span>');
                    }

                    // Mark as clicked
                    $button.addClass('marked');
                    localStorage.setItem(helpfulKey, '1');

                    // Show feedback
                    $button.prepend('<i class="fas fa-check"></i> ');
                }
            }
        });
    }

    /**
     * Show Message
     */
    function showMessage($form, type, message) {
        var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        var $message = $('<div class="review-message alert ' + alertClass + '">' + message + '</div>');

        $form.prepend($message);

        // Scroll to message
        $('html, body').animate({
            scrollTop: $message.offset().top - 100
        }, 300);

        // Auto-remove error messages after 5 seconds
        if (type === 'error') {
            setTimeout(function() {
                $message.fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);
        }
    }

    /**
     * Review Filtering
     */
    if ($('.review-filters').length) {
        $('.review-filters button').on('click', function() {
            var $button = $(this);
            var filterRating = $button.data('rating');

            // Update active state
            $('.review-filters button').removeClass('active');
            $button.addClass('active');

            // Filter reviews
            if (filterRating === 'all') {
                $('.review-item').show();
            } else {
                $('.review-item').each(function() {
                    var $item = $(this);
                    var itemRating = parseFloat($item.data('rating'));

                    if (itemRating >= filterRating && itemRating < filterRating + 1) {
                        $item.show();
                    } else {
                        $item.hide();
                    }
                });
            }
        });
    }

    /**
     * Review Sorting
     */
    if ($('.review-sort').length) {
        $('.review-sort').on('change', function() {
            var sortBy = $(this).val();
            var $container = $('.reviews-list');
            var $items = $container.find('.review-item');

            $items.sort(function(a, b) {
                var $a = $(a);
                var $b = $(b);

                if (sortBy === 'newest') {
                    return $b.data('date') - $a.data('date');
                } else if (sortBy === 'oldest') {
                    return $a.data('date') - $b.data('date');
                } else if (sortBy === 'highest') {
                    return parseFloat($b.data('rating')) - parseFloat($a.data('rating'));
                } else if (sortBy === 'lowest') {
                    return parseFloat($a.data('rating')) - parseFloat($b.data('rating'));
                } else if (sortBy === 'helpful') {
                    return parseInt($b.data('helpful')) - parseInt($a.data('helpful'));
                }
            });

            $container.html($items);
        });
    }

    /**
     * Review Pagination
     */
    if ($('.review-pagination').length) {
        var reviewsPerPage = 10;
        var currentPage = 1;
        var $allReviews = $('.review-item');
        var totalPages = Math.ceil($allReviews.length / reviewsPerPage);

        function showPage(page) {
            var start = (page - 1) * reviewsPerPage;
            var end = start + reviewsPerPage;

            $allReviews.hide().slice(start, end).show();

            // Update pagination
            updatePagination(page);
        }

        function updatePagination(page) {
            var $pagination = $('.review-pagination');
            $pagination.empty();

            // Previous button
            if (page > 1) {
                $pagination.append('<button class="page-btn prev" data-page="' + (page - 1) + '">Previous</button>');
            }

            // Page numbers
            for (var i = 1; i <= totalPages; i++) {
                var activeClass = i === page ? ' active' : '';
                $pagination.append('<button class="page-btn' + activeClass + '" data-page="' + i + '">' + i + '</button>');
            }

            // Next button
            if (page < totalPages) {
                $pagination.append('<button class="page-btn next" data-page="' + (page + 1) + '">Next</button>');
            }
        }

        // Handle pagination clicks
        $(document).on('click', '.review-pagination .page-btn', function() {
            var page = parseInt($(this).data('page'));
            currentPage = page;
            showPage(page);

            // Scroll to reviews
            $('html, body').animate({
                scrollTop: $('.reviews-section').offset().top - 100
            }, 300);
        });

        // Initial load
        showPage(1);
    }

    /**
     * Review Image Upload (if enabled)
     */
    if ($('#review-images').length) {
        $('#review-images').on('change', function() {
            var files = this.files;
            var $preview = $('.review-images-preview');
            $preview.empty();

            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                if (file.type.match('image.*')) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $preview.append('<div class="preview-image"><img src="' + e.target.result + '" alt="Preview"></div>');
                    };

                    reader.readAsDataURL(file);
                }
            }
        });
    }

})(jQuery);
