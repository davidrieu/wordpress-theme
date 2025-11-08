/**
 * Booking System JavaScript
 *
 * @package ConnectPro
 * @since 1.0.0
 */

(function($) {
    'use strict';

    var unavailableDates = [];
    var selectedCheckIn = null;
    var selectedCheckOut = null;

    /**
     * Initialize Booking Calendar
     */
    $(document).ready(function() {
        // Load unavailable dates
        loadUnavailableDates();

        // Date picker initialization
        if ($.fn.datepicker) {
            initDatePickers();
        }

        // Booking form submission
        $('#booking-form').on('submit', handleBookingSubmit);

        // Update booking status
        $('.update-booking-status').on('click', handleStatusUpdate);

        // Calculate price on date change
        $('#check-in-date, #check-out-date').on('change', calculateTotalPrice);
    });

    /**
     * Load Unavailable Dates via AJAX
     */
    function loadUnavailableDates() {
        var listingId = $('#booking-form').data('listing-id');
        if (!listingId) return;

        $.ajax({
            url: connectproBookings.ajax_url,
            type: 'POST',
            data: {
                action: 'get_available_dates',
                nonce: connectproBookings.nonce,
                listing_id: listingId
            },
            success: function(response) {
                if (response.success) {
                    unavailableDates = response.data.unavailable_dates;
                    if ($.fn.datepicker) {
                        initDatePickers();
                    }
                }
            }
        });
    }

    /**
     * Initialize Date Pickers
     */
    function initDatePickers() {
        var today = new Date();

        $('#check-in-date').datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: today,
            beforeShowDay: function(date) {
                var dateString = $.datepicker.formatDate('yy-mm-dd', date);
                return [!isDateUnavailable(dateString), ''];
            },
            onSelect: function(dateText) {
                selectedCheckIn = dateText;
                $('#check-out-date').datepicker('option', 'minDate', new Date(dateText));
                calculateTotalPrice();
            }
        });

        $('#check-out-date').datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: today,
            beforeShowDay: function(date) {
                var dateString = $.datepicker.formatDate('yy-mm-dd', date);
                return [!isDateUnavailable(dateString), ''];
            },
            onSelect: function(dateText) {
                selectedCheckOut = dateText;
                calculateTotalPrice();
            }
        });
    }

    /**
     * Check if date is unavailable
     */
    function isDateUnavailable(dateString) {
        return unavailableDates.indexOf(dateString) !== -1;
    }

    /**
     * Calculate Total Price
     */
    function calculateTotalPrice() {
        var checkIn = $('#check-in-date').val();
        var checkOut = $('#check-out-date').val();

        if (!checkIn || !checkOut) {
            return;
        }

        var checkInDate = new Date(checkIn);
        var checkOutDate = new Date(checkOut);

        if (checkInDate >= checkOutDate) {
            $('#booking-total').text('Invalid dates');
            return;
        }

        var days = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
        var pricePerDay = parseFloat($('#booking-form').data('price-per-day')) || 0;
        var total = days * pricePerDay;

        $('#booking-days').text(days + ' ' + (days === 1 ? 'day' : 'days'));
        $('#booking-total').text('$' + total.toFixed(2));
        $('#booking-total-input').val(total);
    }

    /**
     * Handle Booking Form Submission
     */
    function handleBookingSubmit(e) {
        e.preventDefault();

        var $form = $(this);
        var $submitButton = $form.find('button[type="submit"]');
        var originalText = $submitButton.text();

        // Disable button
        $submitButton.prop('disabled', true).text('Processing...');

        // Clear previous errors
        $('.booking-error').remove();

        var formData = {
            action: 'create_booking',
            nonce: connectproBookings.nonce,
            listing_id: $form.data('listing-id'),
            check_in: $('#check-in-date').val(),
            check_out: $('#check-out-date').val(),
            guests: $('#guests').val(),
            message: $('#booking-message').val()
        };

        $.ajax({
            url: connectproBookings.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Show success message
                    $form.prepend('<div class="booking-success alert alert-success">' + response.data.message + '</div>');

                    // Reset form
                    $form[0].reset();
                    $('#booking-total').text('$0.00');

                    // Scroll to message
                    $('html, body').animate({
                        scrollTop: $form.offset().top - 100
                    }, 300);

                    // Redirect to bookings page after 2 seconds
                    setTimeout(function() {
                        window.location.href = '/dashboard?tab=bookings';
                    }, 2000);
                } else {
                    // Show error message
                    $form.prepend('<div class="booking-error alert alert-danger">' + response.data.message + '</div>');
                }
            },
            error: function() {
                $form.prepend('<div class="booking-error alert alert-danger">An error occurred. Please try again.</div>');
            },
            complete: function() {
                // Re-enable button
                $submitButton.prop('disabled', false).text(originalText);
            }
        });
    }

    /**
     * Handle Booking Status Update
     */
    function handleStatusUpdate(e) {
        e.preventDefault();

        var $button = $(this);
        var bookingId = $button.data('booking-id');
        var status = $button.data('status');
        var originalText = $button.text();

        if (!confirm('Are you sure you want to ' + status + ' this booking?')) {
            return;
        }

        $button.prop('disabled', true).text('Updating...');

        $.ajax({
            url: connectproBookings.ajax_url,
            type: 'POST',
            data: {
                action: 'update_booking_status',
                nonce: connectproBookings.nonce,
                booking_id: bookingId,
                status: status
            },
            success: function(response) {
                if (response.success) {
                    // Show success message
                    $button.closest('.booking-item').prepend(
                        '<div class="alert alert-success">' + response.data.message + '</div>'
                    );

                    // Reload page after 1 second
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    alert(response.data.message);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            },
            complete: function() {
                $button.prop('disabled', false).text(originalText);
            }
        });
    }

    /**
     * Booking Calendar Widget
     */
    if ($('.booking-calendar-widget').length) {
        initBookingCalendarWidget();
    }

    function initBookingCalendarWidget() {
        var $widget = $('.booking-calendar-widget');
        var listingId = $widget.data('listing-id');

        // Load calendar data
        $.ajax({
            url: connectproBookings.ajax_url,
            type: 'POST',
            data: {
                action: 'get_available_dates',
                nonce: connectproBookings.nonce,
                listing_id: listingId
            },
            success: function(response) {
                if (response.success) {
                    renderBookingCalendar(response.data.unavailable_dates);
                }
            }
        });
    }

    /**
     * Render Booking Calendar
     */
    function renderBookingCalendar(unavailableDates) {
        $('#booking-calendar').datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: new Date(),
            numberOfMonths: 2,
            beforeShowDay: function(date) {
                var dateString = $.datepicker.formatDate('yy-mm-dd', date);
                var isUnavailable = unavailableDates.indexOf(dateString) !== -1;

                if (isUnavailable) {
                    return [false, 'unavailable-date', 'Unavailable'];
                } else {
                    return [true, 'available-date', 'Available'];
                }
            },
            onSelect: function(dateText, inst) {
                // Handle date selection
                if (!selectedCheckIn || selectedCheckOut) {
                    // Set check-in
                    selectedCheckIn = dateText;
                    selectedCheckOut = null;
                    $('.selected-dates').html('<strong>Check-in:</strong> ' + dateText);
                } else {
                    // Set check-out
                    selectedCheckOut = dateText;
                    $('.selected-dates').html(
                        '<strong>Check-in:</strong> ' + selectedCheckIn + '<br>' +
                        '<strong>Check-out:</strong> ' + selectedCheckOut
                    );
                }
            }
        });
    }

    /**
     * Booking Quick View
     */
    $('.view-booking-details').on('click', function(e) {
        e.preventDefault();

        var bookingId = $(this).data('booking-id');
        var $modal = $('#booking-details-modal');

        // Load booking details via AJAX
        $.ajax({
            url: connectproBookings.ajax_url,
            type: 'POST',
            data: {
                action: 'get_booking_details',
                nonce: connectproBookings.nonce,
                booking_id: bookingId
            },
            success: function(response) {
                if (response.success) {
                    $modal.find('.modal-body').html(response.data.html);
                    $modal.modal('show');
                }
            }
        });
    });

})(jQuery);
