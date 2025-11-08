/**
 * Google Maps Functionality
 *
 * @package ConnectPro
 * @since 1.0.0
 */

(function($) {
    'use strict';

    var mapInstances = [];

    /**
     * Initialize all maps on page
     */
    $(document).ready(function() {
        // Wait for Google Maps API to load
        if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
            initAllMaps();
        } else {
            console.warn('Google Maps API not loaded');
        }

        // Listing card hover - highlight marker
        $('.listing-card-horizontal').on('mouseenter', function() {
            var markerId = $(this).data('marker-id');
            highlightMarker(markerId);
        }).on('mouseleave', function() {
            resetMarkers();
        });
    });

    /**
     * Initialize all map elements
     */
    function initAllMaps() {
        $('.map-container').each(function() {
            var mapElement = $(this)[0];
            var mapId = $(this).attr('id');

            if (mapId === 'split-map') {
                // Split map initialization is handled in template file
                return;
            }

            initMap(mapElement);
        });
    }

    /**
     * Initialize single map
     */
    function initMap(element) {
        var markers = JSON.parse(element.dataset.markers || '[]');
        var zoom = parseInt(element.dataset.zoom) || 12;
        var style = element.dataset.style || 'standard';

        // Default center
        var center = markers.length > 0 ?
            {lat: markers[0].lat, lng: markers[0].lng} :
            {lat: parseFloat(connectproMaps.default_lat), lng: parseFloat(connectproMaps.default_lng)};

        // Map options
        var mapOptions = {
            zoom: zoom,
            center: center,
            styles: getMapStyles(style),
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: true,
            zoomControl: true,
            scrollwheel: false,
            draggable: true
        };

        // Create map
        var map = new google.maps.Map(element, mapOptions);

        // Add markers
        var mapMarkers = addMarkers(map, markers);

        // Fit bounds if multiple markers
        if (markers.length > 1) {
            var bounds = new google.maps.LatLngBounds();
            markers.forEach(function(marker) {
                bounds.extend(new google.maps.LatLng(marker.lat, marker.lng));
            });
            map.fitBounds(bounds);
        }

        // Store instance
        mapInstances.push({
            element: element,
            map: map,
            markers: mapMarkers
        });

        return map;
    }

    /**
     * Add markers to map
     */
    function addMarkers(map, markersData) {
        var markers = [];

        markersData.forEach(function(markerData) {
            var marker = new google.maps.Marker({
                position: {lat: markerData.lat, lng: markerData.lng},
                map: map,
                title: markerData.title,
                icon: getMarkerIcon(),
                animation: google.maps.Animation.DROP,
                listingId: markerData.id
            });

            // Info window
            var infoWindow = new google.maps.InfoWindow({
                content: getInfoWindowContent(markerData)
            });

            // Click event
            marker.addListener('click', function() {
                // Close all info windows
                mapInstances.forEach(function(instance) {
                    instance.markers.forEach(function(m) {
                        if (m.infoWindow) {
                            m.infoWindow.close();
                        }
                    });
                });

                infoWindow.open(map, marker);

                // Highlight listing card
                highlightListingCard(markerData.id);
            });

            // Store info window reference
            marker.infoWindow = infoWindow;

            markers.push(marker);
        });

        return markers;
    }

    /**
     * Get marker icon
     */
    function getMarkerIcon() {
        return {
            url: connectproMaps.marker_icon,
            scaledSize: new google.maps.Size(40, 50),
            anchor: new google.maps.Point(20, 50)
        };
    }

    /**
     * Get highlighted marker icon
     */
    function getHighlightedMarkerIcon() {
        return {
            url: connectproMaps.marker_icon,
            scaledSize: new google.maps.Size(50, 60),
            anchor: new google.maps.Point(25, 60)
        };
    }

    /**
     * Get info window content
     */
    function getInfoWindowContent(data) {
        var html = '<div class="map-info-window">';

        if (data.image) {
            html += '<div class="info-image">';
            html += '<img src="' + data.image + '" alt="' + data.title + '">';
            html += '</div>';
        }

        html += '<div class="info-content">';
        html += '<h4>' + data.title + '</h4>';

        if (data.price) {
            html += '<p class="price">' + data.price + '</p>';
        }

        html += '<a href="' + data.url + '" class="view-listing-btn">';
        html += 'View Details <i class="fas fa-arrow-right"></i>';
        html += '</a>';
        html += '</div>';
        html += '</div>';

        return html;
    }

    /**
     * Get map styles
     */
    function getMapStyles(styleName) {
        var styles = {
            'standard': [],
            'silver': JSON.parse(connectproMaps.silver_style || '[]'),
            'retro': JSON.parse(connectproMaps.retro_style || '[]'),
            'dark': JSON.parse(connectproMaps.dark_style || '[]')
        };

        return styles[styleName] || [];
    }

    /**
     * Highlight marker
     */
    function highlightMarker(listingId) {
        mapInstances.forEach(function(instance) {
            instance.markers.forEach(function(marker) {
                if (marker.listingId == listingId) {
                    marker.setIcon(getHighlightedMarkerIcon());
                    marker.setZIndex(google.maps.Marker.MAX_ZINDEX + 1);
                } else {
                    marker.setIcon(getMarkerIcon());
                }
            });
        });
    }

    /**
     * Reset all markers
     */
    function resetMarkers() {
        mapInstances.forEach(function(instance) {
            instance.markers.forEach(function(marker) {
                marker.setIcon(getMarkerIcon());
                marker.setZIndex(null);
            });
        });
    }

    /**
     * Highlight listing card
     */
    function highlightListingCard(listingId) {
        $('.listing-card-horizontal').removeClass('highlighted');
        $('.listing-card-horizontal[data-marker-id="' + listingId + '"]').addClass('highlighted');

        // Scroll to listing
        var $listing = $('.listing-card-horizontal[data-marker-id="' + listingId + '"]');
        if ($listing.length) {
            var container = $('.split-map-listings-container');
            if (container.length) {
                container.animate({
                    scrollTop: $listing.offset().top - container.offset().top + container.scrollTop() - 20
                }, 300);
            }
        }
    }

    /**
     * Map controls functionality
     */
    $(document).on('click', '.map-control.zoom-in', function() {
        var map = mapInstances[0].map;
        map.setZoom(map.getZoom() + 1);
    });

    $(document).on('click', '.map-control.zoom-out', function() {
        var map = mapInstances[0].map;
        map.setZoom(map.getZoom() - 1);
    });

    $(document).on('click', '.map-control.locate-me', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                var map = mapInstances[0].map;
                map.setCenter(pos);
                map.setZoom(14);

                // Add marker for user location
                new google.maps.Marker({
                    position: pos,
                    map: map,
                    title: 'Your Location',
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 8,
                        fillColor: '#4285F4',
                        fillOpacity: 1,
                        strokeColor: '#ffffff',
                        strokeWeight: 2
                    }
                });
            });
        }
    });

    $(document).on('click', '.map-control.fullscreen-toggle', function() {
        var mapWrapper = $('.split-map-wrapper');
        if (!document.fullscreenElement) {
            mapWrapper[0].requestFullscreen();
            $(this).find('i').removeClass('fa-expand').addClass('fa-compress');
        } else {
            document.exitFullscreen();
            $(this).find('i').removeClass('fa-compress').addClass('fa-expand');
        }
    });

    // Export for global use
    window.connectproMapsInit = initMap;
    window.connectproMapsAddMarkers = addMarkers;

})(jQuery);
