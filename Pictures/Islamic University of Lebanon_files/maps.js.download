/**
 * This jQuery plugin displays map and it's components.
 * @author Flipper Code (hello *at* flippercode *dot* com)
 * @version 1.0
 */
(function($, window, document, undefined) {
    "use strict";

    var Map_Control = function(options) {
        this.options = options;
    }

    Map_Control.prototype.create_element = function(controlDiv, map, html_element) {

        // Set CSS for the control border
        controlDiv.className = 'wpgmp-control-outer';
        var controlUI = document.createElement('div');
        controlUI.className = 'wpgmp-control-inner';
        controlDiv.appendChild(controlUI);

        // Set CSS for the control interior
        var controlText = document.createElement('div');
        controlText.className = 'wpgmp-control-content';
        controlText.innerHTML = html_element;
        controlUI.appendChild(controlText);
    };

    var overlay_generator = function(tileSize, options) {
        this.tileSize = tileSize;
        this.overlay_options = options;
    }

    overlay_generator.prototype.getTile = function(coord, zoom, ownerDocument) {
        var div = ownerDocument.createElement("div");
        div.innerHTML = coord;
        div.style.width = "200px";
        div.style.height = "300px";
        div.style.fontSize = this.overlay_options.font_size + "px";
        div.style.borderStyle = this.overlay_options.border_style;
        div.style.borderWidth = this.overlay_options.border_width + "px";
        div.style.borderColor = this.overlay_options.border_color;
        return div;
    };

    var GoogleMaps = function(element, map_data) {
        var options;
        this.element = element;
        this.map_data = $.extend({}, {}, map_data);
        options = this.map_data.map_options;
        this.settings = $.extend({
            "min_zoom": "0",
            "max_zoom": "19",
            "zoom": "5",
            "map_type_id": "ROADMAP",
            "scroll_wheel": true,
            "map_visual_refresh": false,
            "full_screen_control": false,
            "full_screen_control_position": "BOTTOM_RIGHT",
            "zoom_control": true,
            "zoom_control_style": "SMALL",
            "zoom_control_position": "TOP_LEFT",
            "map_type_control": true,
            "map_type_control_style": "HORIZONTAL_BAR",
            "map_type_control_position": "RIGHT_TOP",
            "scale_control": true,
            "street_view_control": true,
            "street_view_control_position": "TOP_LEFT",
            "overview_map_control": true,
            "center_lat": "40.6153983",
            "center_lng": "-74.2535216",
            "draggable": true,
            "gesture": "auto",
        }, {}, options);

        this.container = $("div[rel='" + $(this.element).attr("id") + "']");
        var suppress_markers = false;
        if (this.map_data.map_tabs && this.map_data.map_tabs.direction_tab) {
            suppress_markers = this.map_data.map_tabs.direction_tab.suppress_markers;
        }
        this.directionsService = new google.maps.DirectionsService();
        this.directionsDisplay = new google.maps.DirectionsRenderer({
            suppressMarkers: suppress_markers,
        });
        this.drawingmanager = {};
        this.geocoder = new google.maps.Geocoder();
        this.places = [];
        this.show_places = [];
        this.places_for_category_tabs = [];
        this.categories = {};
        this.tabs = [];
        this.all_shapes = [];
        this.wpgmp_polylines = [];
        this.wpgmp_polygons = [];
        this.wpgmp_circles = [];
        this.wpgmp_shape_events = [];
        this.wpgmp_rectangles = [];
        this.per_page_value = 0;
        this.last_remove_cat_id = '';
        this.last_selected_cat_id = '';
        this.last_category_chkbox_action = '';
        this.current_amenities = [];
        this.route_directions = [];
        this.search_area = '';
        this.filter_position = '';
        this.filter_content = '';
        this.markerClusterer = null;
        this.url_filters = [];
        this.infowindow_marker = new google.maps.InfoWindow();
        this.infobox = new InfoBox();
        this.wpgmp_search_form = '';
        this.init();

    }

    GoogleMaps.prototype = {

        init: function() {
            var map_obj = this;

            if (map_obj.map_data.map_property && map_obj.map_data.map_property.debug_mode == true) {
                console.log('*********WPGMP Debug Mode Output*********');
                console.log('Map ID =' + map_obj.map_data.map_property.map_id);
                if (map_obj.map_data.places) {
                    console.log('Total Locations=' + map_obj.map_data.places.length);
                }
                if (map_obj.map_data.routes) {
                    console.log('Total Routes=' + map_obj.map_data.routes.length);
                }
                console.log('WPGMP Object=');
                console.log(map_obj.map_data);
                console.log('*********WPGMP Debug Mode End Output*********');
            }
            var isMobile = false;

            var screen_type = 'desktop';

            var screen_size = $(window).width();
            if (screen_size <= 480) {
                screen_type = 'smartphones';
            } else if (screen_size > 480 && screen_size <= 768) {
                screen_type = 'ipads';
            } else if (screen_size >= 1824) {
                screen_type = 'large-screens';
            }

            if (screen_type != 'desktop' && map_obj.settings.mobile_specific == true) {

                isMobile = true;

                if (map_obj.settings.screens && map_obj.settings.screens[screen_type]) {

                    map_obj.settings.width_mobile = map_obj.settings.screens[screen_type].map_width_mobile;
                    map_obj.settings.height_mobile = map_obj.settings.screens[screen_type].map_height_mobile;
                    map_obj.settings.zoom = parseInt(map_obj.settings.screens[screen_type].map_zoom_level_mobile);
                    map_obj.settings.draggable = (map_obj.settings.screens[screen_type].map_draggable_mobile !== 'false');
                    map_obj.settings.scroll_wheel = (map_obj.settings.screens[screen_type].map_scrolling_wheel_mobile !== 'false');

                } else {
                    map_obj.settings.width_mobile = '';
                    map_obj.settings.height_mobile = '';
                }

                if (map_obj.settings.width_mobile != '')
                    $(map_obj.element).css('width', map_obj.settings.width_mobile);

                if (map_obj.settings.height_mobile != '')
                    $(map_obj.element).css('height', map_obj.settings.height_mobile);
            }
            var center = new google.maps.LatLng(map_obj.settings.center_lat, map_obj.settings.center_lng);
            map_obj.map = new google.maps.Map(map_obj.element, {
                zoom: parseInt(map_obj.settings.zoom),
                center: center,
                disableDoubleClickZoom: (map_obj.settings.doubleclickzoom === false),
                scrollwheel: (map_obj.settings.scroll_wheel != 'true'),
                zoomControl: (map_obj.settings.zoom_control === true),
                fullscreenControl: (map_obj.settings.full_screen_control === true),
                fullscreenControlOptions: {
                    position: eval("google.maps.ControlPosition." + map_obj.settings.full_screen_control_position)
                },
                zoomControlOptions: {
                    style: eval("google.maps.ZoomControlStyle." + map_obj.settings.zoom_control_style),
                    position: eval("google.maps.ControlPosition." + map_obj.settings.zoom_control_position)
                },
                mapTypeControl: (map_obj.settings.map_type_control == true),
                mapTypeControlOptions: {
                    style: eval("google.maps.MapTypeControlStyle." + map_obj.settings.map_type_control_style),
                    position: eval("google.maps.ControlPosition." + map_obj.settings.map_type_control_position)
                },
                scaleControl: (map_obj.settings.scale_control == true),
                streetViewControl: (map_obj.settings.street_view_control == true),
                streetViewControlOptions: {
                    position: eval("google.maps.ControlPosition." + map_obj.settings.street_view_control_position)
                },
                overviewMapControl: (map_obj.settings.overview_map_control == true),
                overviewMapControlOptions: {
                    opened: map_obj.settings.overview_map_control
                },
                draggable: map_obj.settings.draggable,
                mapTypeId: eval("google.maps.MapTypeId." + map_obj.settings.map_type_id),
                styles: eval(map_obj.map_data.styles),
                minZoom: parseInt(map_obj.settings.min_zoom),
                maxZoom: parseInt(map_obj.settings.max_zoom),
                gestureHandling: map_obj.settings.gesture,
            });

            map_obj.map_loaded();
            map_obj.responsive_map();
            map_obj.create_markers();
            map_obj.display_markers();

            //Load google fonts
            if (typeof map_obj.settings.google_fonts !== 'undefined') {
                map_obj.load_google_fonts(map_obj.settings.google_fonts);
            }
            if (map_obj.settings.map_control == true) {
                if (typeof map_obj.settings.map_control_settings != 'undefined') {
                    var map_control_obj = new Map_Control();
                    $.each(map_obj.settings.map_control_settings, function(k, val) {
                        var centerControlDiv = document.createElement('div');
                        map_control_obj.create_element(centerControlDiv, map_obj.map, val.html);
                        centerControlDiv.index = 1;
                        map_obj.map.controls[eval("google.maps.ControlPosition." + val.position)].push(centerControlDiv);
                    });
                }
            }

            //CUSTOM CONTROLS UI
            if (map_obj.settings.locateme_control == true && map_obj.settings.locateme_control_position) {
                var map_control_obj = new Map_Control();
                var centerControlDiv = document.createElement('div');
                map_control_obj.create_element(centerControlDiv, map_obj.map, "<span title='" + wpgmp_local.locate_me + "' alt class='wpgmp_locateme_control " + map_obj.settings.locateme_control_position.toLowerCase() + "'></span>");
                map_obj.map.controls[eval("google.maps.ControlPosition." + map_obj.settings.locateme_control_position)].push(centerControlDiv);
            }

            if (map_obj.map_data.street_view) {
                map_obj.set_streetview(center);
            }

            if (map_obj.map_data.weather_layer) {
                map_obj.set_weather_layer();
            }

            if (map_obj.map_data.bicyle_layer) {
                map_obj.set_bicyle_layer();
            }

            if (map_obj.map_data.traffic_layer) {
                map_obj.set_traffic_layer();
            }

            if (map_obj.map_data.transit_layer) {
                map_obj.set_transit_layer();
            }

            if (map_obj.map_data.panoramio_layer) {
                map_obj.set_panoramic_layer();
            }

            if (map_obj.map_data.overlay_setting) {
                map_obj.set_overlay();
            }

            if (map_obj.settings.display_45_imagery == '45') {
                map_obj.set_45_imagery();
            }

            if (typeof map_obj.map_data.map_visual_refresh === true) {
                map_obj.set_visual_refresh();
            }

            if (map_obj.map_data.marker_cluster) {
                map_obj.set_marker_cluster();
            }

            if (map_obj.map_data.panning_control) {
                map_obj.set_panning_control();
            }

            if (map_obj.map_data.kml_layer) {
                map_obj.set_kml_layer();
            }

            if (map_obj.map_data.fusion_layer) {
                map_obj.set_fusion_layer();
            }

            if (map_obj.settings.search_control == true) {
                map_obj.show_search_control();
            }

            if (typeof map_obj.map_data.shapes != 'undefined') {

                if (typeof map_obj.map_data.shapes.shape != 'undefined') {
                    map_obj.opened_info = map_obj.infowindow_marker;
                    if (typeof map_obj.map_data.shapes.shape.polygons != 'undefined')
                        map_obj.create_polygon();

                    if (typeof map_obj.map_data.shapes.shape.polylines != 'undefined')
                        map_obj.create_polyline();

                    if (typeof map_obj.map_data.shapes.shape.circles != 'undefined')
                        map_obj.create_circle();

                    if (typeof map_obj.map_data.shapes.shape.rectangles != 'undefined')
                        map_obj.create_rectangle();
                }
            }

            if (map_obj.map_data.routes) {
                map_obj.create_routes();
            }

            if (map_obj.map_data.listing) {
                if (map_obj.map_data.listing.default_sorting) {
                    var data_type = '';
                    if (map_obj.map_data.listing.default_sorting.orderby == 'listorder') {
                        data_type = 'num';
                    }
                    map_obj.sorting(map_obj.map_data.listing.default_sorting.orderby, map_obj.map_data.listing.default_sorting.inorder, data_type);
                }

            } else {

                if (map_obj.map_data.map_tabs !== undefined && map_obj.map_data.map_tabs != 'undefined') {

                    if (typeof map_obj.map_data.map_tabs.category_tab !== undefined && map_obj.map_data.map_tabs.category_tab !== 'undefined' && typeof map_obj.map_data.map_tabs.category_tab.cat_tab !== undefined && map_obj.map_data.map_tabs.category_tab.cat_tab) {
                        if (map_obj.map_data.map_tabs.category_tab.cat_post_order === undefined)
                            map_obj.map_data.map_tabs.category_tab.cat_post_order = 'asc';
                        map_obj.sorting('title', map_obj.map_data.map_tabs.category_tab.cat_post_order);
                        map_obj.map_data.places_for_category_tabs = map_obj.map_data.places;
                        map_obj.map_data.places_for_category_tabs = map_obj.sorting_inside_tabs(map_obj.map_data.places_for_category_tabs,map_obj.map_data.map_tabs.category_tab.cat_post_order);

                    }
                }

            }

            if (map_obj.map_data.listing) {

                $(map_obj.container).on('click', '.categories_filter_reset_btn', function() {
                    $(map_obj.container).find('.wpgmp_filter_wrappers select').each(function() {
                        $(this).find('option:first').attr('selected', 'selected');
                        $(this).find('option:first').prop('selected', 'selected');
                    });
                    $('.wpgmp_search_input').val('');
                    map_obj.update_filters();
                    
                });


                $(map_obj.container).on('change', '[data-filter="dropdown"]', function() {
                    map_obj.update_filters();
                });

                $(map_obj.container).on('click', '[data-filter="checklist"]', function() {
                    map_obj.update_filters();
                });

                $(map_obj.container).on('click', '[data-filter="list"]', function() {

                    if ($(this).hasClass('fc_selected')) {
                        $(this).removeClass('fc_selected');
                    } else {
                        $(this).addClass('fc_selected');
                    }

                    map_obj.update_filters();
                });


                map_obj.display_filters_listing();
                map_obj.custom_filters();

                $.each(map_obj.map_data.listing.filters, function(key, filter) {

                    $(map_obj.container).find('select[name="' + filter + '"]').on('change', function() {
                        map_obj.update_filters();
                    });

                });

                $(map_obj.container).find('[data-filter="map-sorting"]').on('change', function() {

                    var order_data = $(this).val().split("__");
                    var data_type = '';
                    if (order_data[0] !== '' && order_data[1] !== '') {

                        if (typeof order_data[2] != 'undefined') {
                            data_type = order_data[2];
                        }
                        map_obj.sorting(order_data[0], order_data[1], data_type);
                        map_obj.update_places_listing();

                    }

                });

                $(map_obj.container).find('[data-name="radius"]').on('change', function() {

                    var search_box = $(map_obj.container).find('[data-input="wpgmp-search-text"]');
                    if(search_box.length === 0)
                    return;
                    
                    var search_data = search_box.val();
                    if (search_data.length >= 2 && $(this).val() != '') {
                        map_obj.geocoder.geocode({
                            "address": search_data
                        }, function(results, status) {

                            if (status == google.maps.GeocoderStatus.OK) {
                                map_obj.search_area = results[0].geometry.location;
                                map_obj.update_filters();
                            }

                        });
                    } else {
                        map_obj.search_area = '';
                        map_obj.update_filters();
                    }

                });

                $(map_obj.container).find('[data-filter="map-perpage-location-sorting"]').on('change', function() {

                    map_obj.per_page_value = $(this).val();
                    map_obj.update_filters();

                });

                $(map_obj.container).find('[data-input="wpgmp-search-text"]').on('keyup', function() {
                    var search_data = $(this).val();
                    $(map_obj.container).find('[data-filter="map-radius"]').val('');
                    map_obj.search_area = '';
                    // Apply default radius
                    if (search_data.length >= 2 && map_obj.map_data.listing.apply_default_radius == true) {
                        if (search_data.length >= 2) {
                            map_obj.geocoder.geocode({
                                "address": search_data
                            }, function(results, status) {

                                if (status == google.maps.GeocoderStatus.OK) {
                                    map_obj.search_area = results[0].geometry.location;
                                    map_obj.update_filters();
                                }

                            });
                        }

                    } else {
                        map_obj.update_filters();
                    }


                });

                if(map_obj !== 'undefined' && map_obj.map_data.default_amenities != undefined && map_obj.map_data.enableAmenitiesListing != undefined && map_obj.map_data.enableAmenitiesListing){
                    //WAIT WHILE LOAD AMENITIES
                } else{
                    $(map_obj.container).find(".location_pagination" + map_obj.map_data.map_property.map_id).pagination(map_obj.show_places.length, {
                        callback: map_obj.display_places_listing,
                        map_data: map_obj,
                        items_per_page: map_obj.map_data.listing.pagination.listing_per_page,
                        prev_text: wpgmp_local.prev,
                        next_text: wpgmp_local.next
                    });
                }

                //Print listing
                $(map_obj.container).find('[data-action="wpgmp-print"]').on('click', function() {
                    if ($('[data-container="wpgmp-listing-' + $(map_obj.element).attr("id") + '"]').length > 0) {
                        $('[data-container="wpgmp-listing-' + $(map_obj.element).attr("id") + '"]').print();
                    }

                    if ($('[data-container="wpgmp-custom-listing-' + $(map_obj.element).attr("id") + '"]').length > 0)
                        $('[data-container="wpgmp-custom-listing-' + $(map_obj.element).attr("id") + '"]').print();
                });

            }

            $('.wpgmp-shape-delete').click(function() {

                map_obj.deleteSelectedShape();
                $('.hiderow').hide();
            });

            $('select[name="shape_stroke_opacity"]').change(function() {
                map_obj.set_shapes_options(map_obj.selectedShape);
            });
            $('select[name="shape_stroke_weight"]').change(function() {
                map_obj.set_shapes_options(map_obj.selectedShape);
            });
            $('select[name="shape_stroke_color"]').change(function() {
                map_obj.set_shapes_options(map_obj.selectedShape);
            });
            $('select[name="shape_fill_opacity"]').change(function() {
                map_obj.set_shapes_options(map_obj.selectedShape);
            });
            $('select[name="shape_fill_color"]').change(function() {
                map_obj.set_shapes_options(map_obj.selectedShape);
            });
            $('input[name="shape_click_url"]').change(function() {
                map_obj.set_shapes_options(map_obj.selectedShape);
            });
            $('textarea[name="shape_click_message"]').blur(function() {
                map_obj.set_shapes_options(map_obj.selectedShape);
            });

            $("textarea[name='shape_path']").blur(function() {
                var cordinates = $(this).val().split(' ');
                if (cordinates.length == 1) {
                    cordinates = $(this).val().split("\n");
                }
                var path = [];
                $.each(cordinates, function(ind, cordinate) {
                    var latlng = cordinate.split(',');
                    path.push(new google.maps.LatLng(latlng[0], latlng[1]));
                });
                map_obj.selectedShape.setPath(path);
            });

            $("input[name='shape_radius']").blur(function() {
                var radius = parseFloat($(this).val());
                map_obj.selectedShape.setRadius(radius);
            });

            $("input[name='shape_center']").blur(function() {
                var latlng = $(this).val().split(',');
                map_obj.selectedShape.setCenter(new google.maps.LatLng(parseFloat(latlng[0]), parseFloat(latlng[1])));
            });

            $("input[name='shape_northeast']").blur(function() {
                var ea = $(this).val().split(',');
                var sw = $("input[name='shape_southwest']").val().split(',');

                map_obj.selectedShape.setBounds(new google.maps.LatLngBounds(new google.maps.LatLng(parseFloat(sw[0]), parseFloat(sw[1])), new google.maps.LatLng(parseFloat(ea[0]), parseFloat(ea[1]))));
            });

            $("input[name='shape_southwest']").blur(function() {
                var sw = $(this).val().split(',');
                var ea = $("input[name='shape_northeast']").val().split(',');

                map_obj.selectedShape.setBounds(new google.maps.LatLngBounds(new google.maps.LatLng(parseFloat(sw[0]), parseFloat(sw[1])), new google.maps.LatLng(parseFloat(ea[0]), parseFloat(ea[1]))));
            });

            $("input[name='shape_center']").blur(function() {
                var latlng = $(this).val().split(',');
                map_obj.selectedShape.setCenter(new google.maps.LatLng(parseFloat(latlng[0]), parseFloat(latlng[1])));
            });

            $('input[name="wpgmp_save_drawing"]').click(function() {

                var all_shapes_cordinate = [];

                all_shapes_cordinate.push('polylines=' + map_obj.wpgmp_save_polylines().join('::'));
                all_shapes_cordinate.push('polygons=' + map_obj.wpgmp_save_polygons().join('::'));
                all_shapes_cordinate.push('circles=' + map_obj.wpgmp_save_circles().join('::'));
                all_shapes_cordinate.push('rectangles=' + map_obj.wpgmp_save_rectangles().join('::'));

                map_obj.wpgmp_save_shapes(all_shapes_cordinate);

            });
            if (typeof map_obj.map_data.shapes != 'undefined') {
                if (map_obj.map_data.shapes.drawing_editable === true) {
                    $('.wpgmp-overview .color').wpColorPicker({
                        change: function(event, ui) {
                            map_obj.set_shapes_options(map_obj.selectedShape);
                        }
                    });
                }
            }

            $(this.container).on("click", ".wpgmp_locateme_control", function() {
            
                map_obj.get_current_location(function(user_location) {

                    map_obj.map.setCenter(user_location);

                    if (map_obj.map_center_marker) {
                        map_obj.map_center_marker.setPosition(user_location);
                    }

                    if (map_obj.set_center_circle) {
                        map_obj.set_center_circle.setCenter(user_location);
                    }
                });
            });


            if (typeof map_obj.map_data.map_tabs != 'undefined') {
                this.map_widgets();

                $(map_obj.container).find(".wpgmp_toggle_main_container").find("div[id^='wpgmp_tab_']").css("display", "none");

                if (map_obj.settings.infowindow_filter_only === undefined || map_obj.settings.infowindow_filter_only === false) {

                    $(map_obj.container).find("input[data-marker-category]").attr("checked", true);
                    $(map_obj.container).find("input[data-marker-location]").attr("checked", true);

                    $(map_obj.container).find("input[data-marker-category]").prop("checked", true);
                    $(map_obj.container).find("input[data-marker-location]").prop("checked", true);

                }

                if (this.map_data.map_tabs.category_tab && this.map_data.map_tabs.category_tab.select_all === true) {

                    $(map_obj.container).find('input[name="wpgmp_select_all"]').click(function() {
                        if ($(this).is(":checked")) {
                        	
                        	$(map_obj.container).find("input[data-marker-category]").attr( "checked", true );
                            $(map_obj.container).find('input[data-marker-location]').attr( "checked", true );
                            $(map_obj.container).find("input[data-marker-category]").prop( "checked", true );
                            $(map_obj.container).find('input[data-marker-location]').prop( "checked", true );
                        } else {
                        	
                        	$(map_obj.container).find("input[data-marker-category]").attr("checked", false);
                            $(map_obj.container).find('input[data-marker-location]').attr('checked', false);
                            $(map_obj.container).find("input[data-marker-category]").prop( "checked", false );
                            $(map_obj.container).find('input[data-marker-location]').prop( "checked", false );
                        }
                        map_obj.update_filters();
                    });
                }

                $(map_obj.container).find(".wpgmp_toggle_container").click(function() {

                    $(map_obj.container).find(".wpgmp_toggle_main_container").slideToggle("slow");

                    if ($(this).text() == wpgmp_local.hide) {
                        $(this).text(wpgmp_local.show);
                    } else {
                        $(this).text(wpgmp_local.hide);
                    }

                });

                if (map_obj.map_data.map_tabs.hide_tabs_default === true) {
                    $(map_obj.container).find(".wpgmp_toggle_container").trigger('click');
                }

                $(map_obj.container).find(".wpgmp_specific_route_item").attr("checked", true);

                $(map_obj.container).find(".wpgmp_toggle_main_container").find("div[id^='wpgmp_tab_']").first().css("display", "block");

                $(map_obj.container).on('click', "li[class^='wpgmp-tab-'] a", function() {

                    $(map_obj.container).find("li[class^='wpgmp-tab-'] a").removeClass('active');

                    $(this).addClass('active');

                    $(map_obj.container).find(".wpgmp_toggle_main_container").find("div[id^='wpgmp_tab_']").css("display", "none");

                    $(map_obj.container).find(".wpgmp_toggle_main_container").find("#wpgmp_tab_" + $(this).parent().attr('rel')).css("display", "block");

                });

                $(map_obj.container).on('change', "input[data-marker-category]", function() {
                    //uncheck all locations
                    var current_marker_id = $(this).data('marker-category');
                    var that = this;
                    if ($(that).data('child-cats')) {
                        var data_child = $(that).data('child-cats').toString();
                        if (data_child.indexOf(',') !== -1) {
                            var child_cats = data_child.split(',');
                        } else {
                            var child_cats = [];
                            child_cats.push(data_child);
                        }
                    }
                    
                    if ($(this).is(":checked") === false) {
                        map_obj.last_remove_cat_id = current_marker_id;
                        map_obj.last_category_chkbox_action = 'unchecked';
                        $(that).closest('[data-container="wpgmp-category-tab-item"]').find('input[data-marker-location]').attr('checked', false);
                        //jQuery Prop
                        $(that).closest('[data-container="wpgmp-category-tab-item"]').find('input[data-marker-location]').prop('checked', false);

                        if (child_cats) {
                            $.each(child_cats, function(i, cat) {
                                $(that).parent().parent().find('[data-marker-category="' + cat + '"]').attr('checked', false);
                                $(that).parent().parent().find('[data-marker-category="' + cat + '"]').parent().find('input[data-marker-location]').attr('checked', false);
                                //Jquery Prop
                                $(that).parent().parent().find('[data-marker-category="' + cat + '"]').prop('checked', false);
                                $(that).parent().parent().find('[data-marker-category="' + cat + '"]').parent().find('input[data-marker-location]').prop('checked', false);
                            });
                        }
                    } else {
                        map_obj.last_selected_cat_id = current_marker_id;
                        map_obj.last_category_chkbox_action = 'checked';
                        $(that).closest('[data-container="wpgmp-category-tab-item"]').find('input[data-marker-location]').attr('checked', true);
                        //jQuery Prop
                        $(that).closest('[data-container="wpgmp-category-tab-item"]').find('input[data-marker-location]').prop('checked', true);
                        if (child_cats) {
                            $.each(child_cats, function(i, cat) {
                                $(that).parent().parent().find('[data-marker-category="' + cat + '"]').attr('checked', true);
                                $(that).parent().parent().find('[data-marker-category="' + cat + '"]').parent().find('input[data-marker-location]').attr('checked', true);

                                $(that).parent().parent().find('[data-marker-category="' + cat + '"]').prop('checked', true);
                                $(that).parent().parent().find('[data-marker-category="' + cat + '"]').parent().find('input[data-marker-location]').prop('checked', true);
                            });
                        }
                    }
                    map_obj.update_filters();
                    
                });

                $(map_obj.container).find(".wpgmp_toggle_main_container").on('change', ".wpgmp_specific_route_item", function() {
                    //uncheck all locations
                    var selected_route = map_obj.route_directions[$(this).val()];
                    if ($(this).is(":checked") === false) {
                        selected_route.setMap(null);
                    } else {
                        selected_route.setMap(map_obj.map);
                    }

                });

                $(map_obj.container).on('change', "input[data-marker-location]", function() {
                    map_obj.update_filters();
                });

                //directions tabs
                if (this.map_data.map_tabs.direction_tab && this.map_data.map_tabs.direction_tab.dir_tab === true) {
                    $(this.container).find(".wpgmp_find_direction").click(function() {

                        var start = $(map_obj.container).find('.start_point');
                        var end = $(map_obj.container).find('.end_point');
                        var travel_mode = $(map_obj.container).find('select[name="travel_mode"]').val();
                        var travel_unit = $(map_obj.container).find('select[name="travel_unit"]').val();
                        var direction_panel = $(map_obj.container).find('.directions-panel');
                        var current_address = '';
                        if ($(start).val() === "") {
                            $(start).focus();
                            return false;
                        }

                        if ($(end).val() === "") {
                            $(end).focus();
                            return false;
                        }

                        var options = {
                            "start": start.val(),
                            "end": end.val(),
                            "mode": travel_mode,
                            "unit": travel_unit,
                            "direction_panel": direction_panel[0],
                            "map": map_obj.map
                        };
                        map_obj.find_direction(options);

                    });
                }

                $(this.container).find(".wpgmp_find_nearby_button").click(function() {

                        var target = $(this).parent().parent();
                        var lat = $(target).find(".wpgmp_auto_suggest").data('latitude');
                        var lon = $(target).find(".wpgmp_auto_suggest").data('longitude');

                        if (!lat || !lon) {
                            $(target).find(".wpgmp_auto_suggest").focus();
                            return;
                        }

                        var radius = $(map_obj.container).find("input[name='wpgmp_radius']").val();
                        var dim = $(map_obj.container).find("select[name='wpgmp_route_dimension']").val();
                        var amenities = $(map_obj.container).find('input[name^="wpgmp_place_types"]:checked');
                        var divide_by = 1.60934;
                        var service;
                        if (dim == 'miles') {
                            divide_by = 1.60934;
                        } else {
                            divide_by = 1;
                        }
                        var circle_radius_meters = parseInt(radius) * divide_by * 1000;
                        // Now draw a circle.
                        if (amenities.length > 0 && lat && lon) {
                            //remove all previous amenities
                            if (map_obj.current_amenities.length > 0) {
                                $.each(map_obj.current_amenities, function(am, amenity) {
                                    amenity.marker.setMap(null);
                                });
                            }
                            map_obj.amenity_infowindow = map_obj.infowindow_marker;
                            var place_types = [];
                            $.each(amenities, function(index, amenity) {
                                place_types.push($(amenity).val());
                            });

                            for (var i = 0; i < place_types.length; i++) {
                                var request = {
                                    location: new google.maps.LatLng(lat, lon),
                                    radius: circle_radius_meters,
                                    types: [place_types[i]]
                                };
                                service = new google.maps.places.PlacesService(map_obj.map);
                                service.nearbySearch(request, function(results, status, page) {
                                    if (status == google.maps.places.PlacesServiceStatus.OK) {
                                        for (var i = 0; i < results.length; i++) {
                                            map_obj.createMarker(results[i]);
                                        }
                                        if (page && page.hasNextPage) {
                                            page.nextPage();
                                        }
                                    }
                                });
                            }

                            map_obj.map.setCenter(new google.maps.LatLng(lat, lon));

                            if (map_obj.map_data.map_tabs.nearby_tab.show_nearby_circle === true) {

                                if (typeof map_obj.set_nearbycenter_circle != 'undefined') {
                                    map_obj.set_nearbycenter_circle.setMap(null);
                                }
                                //this.map_data.map_tabs.nearby_tab.near_tab
                                map_obj.set_nearbycenter_circle = new google.maps.Circle({
                                    map: map_obj.map,
                                    fillColor: map_obj.map_data.map_tabs.nearby_tab.nearby_circle_fillcolor,
                                    fillOpacity: map_obj.map_data.map_tabs.nearby_tab.nearby_circle_fillopacity,
                                    strokeColor: map_obj.map_data.map_tabs.nearby_tab.nearby_circle_strokecolor,
                                    strokeOpacity: map_obj.map_data.map_tabs.nearby_tab.nearby_circle_strokeopacity,
                                    strokeWeight: map_obj.map_data.map_tabs.nearby_tab.nearby_circle_strokeweight,
                                    center: new google.maps.LatLng(lat, lon),
                                    radius: circle_radius_meters,
                                });
                                map_obj.map.setZoom(parseInt(map_obj.map_data.map_tabs.nearby_tab.nearby_circle_zoom));
                            }
                        }


                    }),            

                $(this.container).find(".wpgmp_mcurrent_loction").click(function() {
                    var this_current = this;
                    map_obj.get_current_location(function(user_location) {

                        $(this_current).parent().find('.wpgmp_auto_suggest').data('latitude', user_location.lat());
                        $(this_current).parent().find('.wpgmp_auto_suggest').data('longitude', user_location.lng());

                        map_obj.geocoder.geocode({
                            "latLng": user_location
                        }, function(results, status) {

                            if (status == google.maps.GeocoderStatus.OK) {
                                $(this_current).parent().find('.wpgmp_auto_suggest').val(results[0]["formatted_address"]);
                            } else
                                console.log(status);

                        });

                    }, function() {


                    });

                });
            } //tabs ended



            if (typeof map_obj.map_data.geojson != 'undefined') {
                map_obj.load_json(map_obj.map_data.geojson);
            }

            $("body").on("click", ".wpgmp_marker_link", function() {
                $('html, body').animate({
                    scrollTop: $(map_obj.container).offset().top - 150
                }, 500);

                map_obj.open_infowindow($(this).data("marker"));

            });

            var on_event = map_obj.settings.infowindow_open_event;
            $(map_obj.container).on(on_event, ".wpgmp_locations a[data-marker]", function() {

                var current_marker = this;

                $('html, body').animate({
                    scrollTop: $(map_obj.container).offset().top - 150
                }, 500);

                setTimeout(function() {
                    map_obj.open_infowindow($(current_marker).data("marker"));
                }, 600);
            });

            $(map_obj.container).on("click", ".wpgmp_location_container a[data-marker]", function() {
                map_obj.open_infowindow($(this).data("marker"));
            });

            // REGISTER AUTO SUGGEST
            map_obj.wpgmp_search_form = $(".wpgmp_auto_suggest");
            map_obj.google_auto_suggest(map_obj.wpgmp_search_form);

            if (map_obj.settings.show_center_circle === true) {
                map_obj.show_center_circle();
            }

            if (map_obj.settings.show_center_marker === true) {
                map_obj.show_center_marker();
            }

            if (typeof map_obj.map_data.shapes != 'undefined') {

                if (map_obj.map_data.shapes.drawing_editable === true)
                    this.enable_drawing();

            }

            if (map_obj.settings.fit_bounds === true) {
                map_obj.fit_bounds();
            }


            //url filters
            if (map_obj.settings.url_filters === true) {
                map_obj.apply_url_filters();
            }

            $(map_obj.container).find(".wpgmp-accordion").accordion({
                speed: "slow"
            });


        },
        load_google_fonts: function(fonts) {
            if (fonts && fonts.length > 0) {
                $.each(fonts, function(k, font) {
                    if (font.indexOf(',') >= 0) {
                        font = font.split(",");
                        font = font[0];
                    }
                    if (font.indexOf('"') >= 0) {
                        font = font.replace('"', '');
                        font = font.replace('"', '');
                    }
                    WebFont.load({
                        google: {
                            families: [font]
                        }
                    });
                });
            }
        },
        load_json: function(url) {
            this.map.data.loadGeoJson(url);
        },
        createMarker: function(place) {

            var map_obj = this;
            var map = map_obj.map;
            var placeLoc = place.geometry.location;
            var image = {
                url: place.icon,
                size: new google.maps.Size(25, 25),
                scaledSize: new google.maps.Size(25, 25)
            };

            place.marker = new google.maps.Marker({
                map: map,
                position: place.geometry.location,
                icon: image
            });

            var post_info_class = 'fc-infowindow-';
            place.marker = place.marker;
            place.address = place.vicinity;
            place.title = place.name;
            place.location = {};
            place.location.onclick_action = 'marker';
            var content = '';

            var temp_listing_placeholder = '';
            var post_info_class = 'fc-infowindow-';
            if (place.source == 'post') {
                temp_listing_placeholder = map_obj.settings.infowindow_geotags_setting;
                post_info_class = 'wpgmp_infowindow_post fc-item-' + map_obj.settings.infowindow_post_skin.name;
            } else {
                temp_listing_placeholder = map_obj.settings.infowindow_setting;
                if (map_obj.map_data.page != 'edit_location' && map_obj.settings.infowindow_skin)
                    post_info_class = 'fc-infowindow-' + map_obj.settings.infowindow_skin.name;
            }

            if (typeof temp_listing_placeholder == 'undefined') {
                temp_listing_placeholder = place.content;
            }

            var marker_image = '';
            var image_url = typeof place.photos !== 'undefined' ? place.photos[0].getUrl({maxWidth: 400, maxHeight: 400}) : place.icon;
                marker_image = "<div class='fc-feature-img amenities_image'><img alt='" + place.vicinity + "' src='" + image_url + "' class='wpgmp_marker_image wpgmp_amenities_image' /></div>";

            var replaceData = {
                "{marker_id}": place.id,
                "{marker_title}": place.name,
                "{marker_address}": place.vicinity,
                "{marker_latitude}": place.geometry.location.lat(),
                "{marker_longitude}": place.geometry.location.lng(),
                "{marker_city}": '',
                "{marker_state}": '',
                "{marker_country}": '',
                "{marker_postal_code}": '',
                "{marker_zoom}": '',
                "{marker_icon}": place.icon,
                "{marker_category}": place.types[0],
                "{marker_message}": place.content,
                "{marker_image}": marker_image,
            };

            for (var prop in replaceData) {
                if (replaceData[prop] == undefined || replaceData[prop] == 'undefined')
                    replaceData[prop] = '';
            }

            if (temp_listing_placeholder) {

                temp_listing_placeholder = temp_listing_placeholder.replace(/{[^{}]+}/g, function(match) {
                    if (match in replaceData) {
                        return (replaceData[match]);
                    } else {
                        return ("");
                    }
                });

                var temp_string = temp_listing_placeholder;
                var temp_object = $('<div/>').html(temp_string);
                $(temp_object).find(".wpgmp_extra_field:contains('wpgmp_empty')").remove();
                $(temp_object).find(".wpgmp_empty").prev().remove();
                $(temp_object).find(".wpgmp_empty").remove();

                content = $(temp_object).prop('outerHTML').toString();

            }

            if (content === "") {
                if (map_obj.settings.map_infowindow_customisations === true && map_obj.settings.show_infowindow_header === true)
                    content = '<div class="wpgmp_infowindow ' + post_info_class + '"><div class="wpgmp_iw_head"><div class="wpgmp_iw_head_content">' + place.name + '</div></div><div class="wpgmp_iw_content">' + place.vicinity + '</div></div>';
                else
                    content = '<div class="wpgmp_infowindow ' + post_info_class + '"><div class="wpgmp_iw_content">' + place.content + '</div></div>';
            } else {
                if (map_obj.settings.map_infowindow_customisations === true && map_obj.settings.show_infowindow_header === true)
                    content = '<div class="wpgmp_infowindow ' + post_info_class + '"><div class="wpgmp_iw_head"><div class="wpgmp_iw_head_content">' + place.name + '</div></div><div class="wpgmp_iw_content">' + content + '</div></div>';
                else
                    content = '<div class="wpgmp_infowindow ' + post_info_class + '"><div class="wpgmp_iw_content">' + content + '</div></div>';

            }

            place.infowindow_data = content;
            place.infowindow = map_obj.infowindow_marker;

            var on_event = map_obj.settings.infowindow_open_event;

            map_obj.event_listener(place.marker, on_event, function() {
                $.each(map_obj.places, function(key, prev_place) {
                    prev_place.infowindow.close();
                    prev_place.marker.setAnimation(null);
                });
                map_obj.openInfoWindow(place);
            });

        map_obj.places.push(place);
            
            
        },
        wpgmp_image_type_overlays: function() {
            var map_obj = this;
            var imageMapType = new google.maps.ImageMapType({
                getTileUrl: function(coord, zoom) {
                    return ['http://www.gstatic.com/io2010maps/tiles/5/L2_',
                        zoom, '_', coord.x, '_', coord.y, '.png'
                    ].join('');
                },
                tileSize: new google.maps.Size(256, 256)
            });

            map_obj.map.overlayMapTypes.push(imageMapType);

        },
        wpgmp_within_radius: function(place, search_area) {
            var map_obj = this;
            var radius = $(map_obj.container).find('[data-name="radius"]').val();
            var dimension = map_obj.map_data.listing.radius_dimension;
            if (map_obj.map_data.listing.apply_default_radius == true && radius == '') {
                radius = map_obj.map_data.listing.default_radius;
                dimension = map_obj.map_data.listing.default_radius_dimension;
            }
            if (dimension == 'km') {
                radius = parseInt(radius) * 1000;
            } else {
                radius = parseInt(radius) * 1609.34;
            }
            if (google.maps.geometry.spherical.computeDistanceBetween(place.marker.getPosition(), search_area) < radius) {
                return true;
            } else {
                return false;
            }
        },
        wpgmp_get_nearby_locations: function(lat1, lon1, radius) {
            var current_rd = 'miles';
            var map_obj = this;
            var radius_km = 6371;
            var max_radius_km = 0;

            if (current_rd == "miles") {
                max_radius_km = radius * 1.61;
            } else if (current_rd == "km") {
                max_radius_km = radius;
            }

            var distances = [];
            var closest = -1;
            var pi = Math.PI;
            var marker_info = {};

            $.each(map_obj.places, function(index, place) {

                var lat2 = place.location.lat;
                var lon2 = place.location.lng;

                var chLat = lat2 - lat1;
                var chLon = lon2 - lon1;

                var dLat = chLat * (pi / 180);
                var dLon = chLon * (pi / 180);


                var rLat1 = lat1 * (pi / 180);
                var rLat2 = lat2 * (pi / 180);

                var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.sin(dLon / 2) * Math.sin(dLon / 2) * Math.cos(rLat1) * Math.cos(rLat2);
                var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                var d = radius_km * c;


                if (max_radius_km !== 0) {
                    if (d <= max_radius_km) {
                        distances[d] = place;
                        distances.sort(function(a, b) {
                            return a - b;
                        });
                        marker_info["marker" + place.id] = d;
                    }
                } else {
                    distances[d] = markers[i];
                    distances.sort(function(a, b) {
                        return a - b;
                    });
                    marker_info["marker" + place.id] = d;
                }

            });

            sortest_markers = map_obj.wpgmp_sort_distance(marker_info);

            return sortest_markers;
        },

        wpgmp_sort_distance: function(obj) {
            var arr = [];
            for (var prop in obj) {
                if (obj.hasOwnProperty(prop)) {
                    arr.push({
                        "key": prop,
                        "value": obj[prop]
                    });
                }
            }
            arr.sort(function(a, b) {
                return a.value - b.value;
            });
            return arr;
        },

        get_user_position: function() {

            var map_obj = this;

            navigator.geolocation.getCurrentPosition(function(position) {

                map_obj.user_lat_lng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

            }, function(ErrorPosition) {}, {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            });

        },

        marker_bind: function(marker) {

            var map_obj = this;

            google.maps.event.addListener(marker, 'drag', function() {

                var position = marker.getPosition();

                map_obj.geocoder.geocode({
                    latLng: position
                }, function(results, status) {

                    if (status == google.maps.GeocoderStatus.OK) {

                        $("#googlemap_address").val(results[0].formatted_address);

                        $(".google_city").val(map_obj.wpgmp_finddata(results[0], 'administrative_area_level_3') || map_obj.wpgmp_finddata(results[0], 'locality'));
                        $(".google_state").val(map_obj.wpgmp_finddata(results[0], "administrative_area_level_1"));
                        $(".google_country").val(map_obj.wpgmp_finddata(results[0], "country"));

                        if (results[0].address_components) {
                            for (var i = 0; i < results[0].address_components.length; i++) {
                                for (var j = 0; j < results[0].address_components[i].types.length; j++) {
                                    if (results[0].address_components[i].types[j] == "postal_code") {
                                        $(".google_postal_code").val(results[0].address_components[i].long_name);
                                    }
                                }
                            }
                        }
                    }
                });

                $(".google_latitude").val(position.lat());
                $(".google_longitude").val(position.lng());
            });

        },

        google_auto_suggest: function(obj) {

            var map_obj = this;
            obj.each(function() {
                var current_input = this;
                var autocomplete = new google.maps.places.Autocomplete(this);

                if (wpgmp_local.wpgmp_country_specific && wpgmp_local.wpgmp_country_specific == true) {
                    autocomplete.setComponentRestrictions({
                        'country': wpgmp_local.wpgmp_countries
                    });
                }

                autocomplete.bindTo('bounds', map_obj.map);

                if ($(this).attr("name") == 'location_address') {
                    var infowindow = map_obj.infowindow_marker;
                    var marker = new google.maps.Marker({
                        map: map_obj.map,
                        draggable: true,
                        anchorPoint: new google.maps.Point(0, -29)
                    });

                    map_obj.marker_bind(marker);

                    google.maps.event.addListener(autocomplete, 'place_changed', function() {

                        var place = autocomplete.getPlace();

                        if (!place.geometry) {
                            return;
                        }

                        // If the place has a geometry, then present it on a map.
                        if (place.geometry.viewport) {
                            map_obj.map.fitBounds(place.geometry.viewport);
                        } else {
                            map_obj.map.setCenter(place.geometry.location);
                            map_obj.map.setZoom(17);
                        }

                        $(".google_latitude").val(place.geometry.location.lat());
                        $(".google_longitude").val(place.geometry.location.lng());
                        $(".google_city").val(map_obj.wpgmp_finddata(place, 'administrative_area_level_3') || map_obj.wpgmp_finddata(place, 'locality'));
                        $(".google_state").val(map_obj.wpgmp_finddata(place, "administrative_area_level_1"));
                        $(".google_country").val(map_obj.wpgmp_finddata(place, "country"));
                        if (place.address_components) {
                            for (var i = 0; i < place.address_components.length; i++) {
                                for (var j = 0; j < place.address_components[i].types.length; j++) {
                                    if (place.address_components[i].types[j] == "postal_code") {
                                        var wpgmp_zip_code = place.address_components[i].long_name;
                                        $(".google_postal_code").val(wpgmp_zip_code);
                                    }
                                }
                            }
                        }

                        marker.setPosition(place.geometry.location);
                        marker.setVisible(true);
                    });
                }  else {

                    google.maps.event.addListener(autocomplete, 'place_changed', function() {

                        var place = autocomplete.getPlace();
                       
                        map_obj.map.setCenter(place.geometry.location);
                        $(current_input).data('longitude', place.geometry.location.lng());
                        $(current_input).data('latitude', place.geometry.location.lat());

                        if( $(current_input).attr("name") == 'wpgmp_search_input' ) {
                            if( typeof map_obj.map_data.listing !='undefined' && typeof map_obj.map_data.listing.display_radius_filter !='undefined' && map_obj.map_data.listing.display_radius_filter === true ) {
                                map_obj.search_area = place.geometry.location;
                            }
                            map_obj.update_filters();
                        }
                        

                    });
                }



            });
        },

        wpgmp_finddata: function(result, type) {
            var component_name = "";
            for (var i = 0; i < result.address_components.length; ++i) {
                var component = result.address_components[i];
                $.each(component.types, function(index, value) {
                    if (value == type) {
                        component_name = component.long_name;
                    }
                });


            }
            return component_name;
        },
        clearSelection: function() {
            var map_obj = this;
            if (map_obj.selectedShape) {
                map_obj.selectedShape.setEditable(false);
                map_obj.selectedShape = null;
            }
        },
        setSelection: function(shape) {
            var map_obj = this;
            map_obj.clearSelection();
            map_obj.selectedShape = shape;
            map_obj.selectedShape.setEditable(true);
        },
        deleteSelectedShape: function() {
            var map_obj = this;
            var key;
            if (map_obj.selectedShape) {
                for (key in map_obj.wpgmp_circles) {
                    if (map_obj.wpgmp_circles[key] == map_obj.selectedShape) {
                        map_obj.wpgmp_circles.splice(key, 1);
                    }
                }
                for (key in map_obj.wpgmp_rectangles) {
                    if (map_obj.wpgmp_rectangles[key] == map_obj.selectedShape) {
                        map_obj.wpgmp_rectangles.splice(key, 1);
                    }
                }
                for (key in map_obj.wpgmp_polygons) {
                    if (map_obj.wpgmp_polygons[key] == map_obj.selectedShape) {
                        map_obj.wpgmp_polygons.splice(key, 1);
                    }
                }
                for (key in map_obj.wpgmp_polylines) {
                    if (map_obj.wpgmp_polylines[key] == map_obj.selectedShape) {
                        map_obj.wpgmp_polylines.splice(key, 1);
                    }
                }
                map_obj.selectedShape.setMap(null);
            }
        },
        add_tab: function(title, content) {

            var tab = [];

            tab.title = title;
            tab.content = content;
            this.tabs.push(tab);
        },

        show_tabs: function() {

            if (this.tabs.length === 0 || (this.map_data.listing && this.map_data.listing.hide_map == true))
                return;

            var content = '<div class="wpgmp_tabs_container cleanslate"><ul class="wpgmp_tabs clearfix">';

            $.each(this.tabs, function(index, tab) {
                if (index == 0)
                    content += '<li class="wpgmp-tab-' + index + '" rel="' + index + '"><a class="active" href="javascript:void(0);">' + tab.title + '</a></li>';
                else
                    content += '<li class="wpgmp-tab-' + index + '" rel="' + index + '"><a href="javascript:void(0);">' + tab.title + '</a></li>';
            });

            content += '</ul>';

            content += '<div class="wpgmp_toggle_main_container">';

            $.each(this.tabs, function(index, tab) {
                content += '<div id="wpgmp_tab_' + index + '">';
                content += tab.content;
                content += '</div>';
            });

            content += '</div><div class="wpgmp_toggle_container">' + wpgmp_local.hide + '</div></div>';

            return content;
        },

        map_widgets: function() {

            var content = '';

            if (this.map_data.map_tabs.category_tab && this.map_data.map_tabs.category_tab.cat_tab === true)
                this.widget_category();

            if (this.map_data.map_tabs.direction_tab && this.map_data.map_tabs.direction_tab.dir_tab === true)
                this.widget_directions();

            if (this.map_data.map_tabs.nearby_tab && this.map_data.map_tabs.nearby_tab.near_tab === true)
                this.widget_nearby();

            if (this.map_data.map_tabs.route_tab && this.map_data.map_tabs.route_tab.display_route_tab === true)
                this.widget_route_tab();

            if (this.map_data.map_tabs.extension_tabs) {
                this.widget_extensions_tab();
            }
            content += this.show_tabs();

            if (content != 'undefined')
                $(this.container).find('.wpgmp_map_parent').append(content);


        },

        widget_extensions_tab: function() {
            var map_obj = this;
            var new_tabs = map_obj.map_data.map_tabs.extension_tabs;
            if (typeof new_tabs != 'undefined' && new_tabs.length > 0) {
                $.each(new_tabs, function(index, tab) {
                    map_obj.add_tab(tab.title, tab.content);
                });
            }
        },
        widget_route_tab: function() {

            var route_data = this.map_data.map_tabs.route_tab.route_tab_data;

            var content = '';

            if (this.map_data.map_tabs.route_tab.display_route_tab_data === true) {
                content += '<div id="wpgmp_route_tab">';
                if (route_data) {
                    $.each(route_data, function(index, route) {
                        content += '<div class="wpgmp_tab_item">';
                        content += '<input type="checkbox" class="wpgmp_specific_route_item" value="' + route.route_id + '">';
                        var box = '<span style="display:inline-block;width:10px;height:10px;background-color:' + route.route_stroke_color + ';float:right;margin-right:10px;margin-top:5px;"></span>';
                        content += '<a href="javascript:void(0);" class="wpgmp_cat_title wpgmp-accordion accordion-close">' + route.route_title + box + '</a>';

                        content += '<div class="directions-panel-route' + route.route_id + ' wpgmp-directions-panel-route-style" style="overflow-y:scroll; height:200px;"></div>';

                        content += '</div>';
                    });
                }

                content += '</div>';
            }
            this.add_tab(this.map_data.map_tabs.route_tab.route_tab_title, content);
        },

        widget_nearby: function() {
            var content = '<div class="wpgmp_nearby_container">';
            content += '<p><input  placeholder="' + wpgmp_local.start_point + '" type="text" name="start_point" class="input start_point wpgmp_auto_suggest" autocomplete="off" /><span class="wpgmp_mcurrent_loction" title="' + wpgmp_local.take_current_location + '">&nbsp;</span></p>';
            content += '<p><input name="wpgmp_radius" placeholder="' + wpgmp_local.radius + '" type="text" class="input" value="25" size="8"> <select name="wpgmp_route_dimension"><option value="miles">' + wpgmp_local.miles + '</option><option value="km">' + wpgmp_local.km + '</option></select></p>';
            if (typeof this.map_data.map_tabs.nearby_tab.nearby_amenities != 'undefined') {
                var all_amenities = this.map_data.map_tabs.nearby_tab.nearby_amenities;

                if (all_amenities) {
                    content += '<div class="choose_amenities">';
                    $.each(all_amenities, function(index, amenity) {
                        content += "<span class='amenity_type'><input type='checkbox' value='" + amenity + "' name='wpgmp_place_types[]' /><label>" + amenity + '</label></span>';
                    });
                }

            }
            content += '</div>';
            content += '<p style="clear:both;"><input type="submit" value="' + wpgmp_local.find_location + '" class="wpgmp_find_nearby_button" /></p><div class="location_panel"></div></div>';


            this.add_tab(this.map_data.map_tabs.nearby_tab.nearby_tab_title, content);

        },

        widget_directions: function() {

            var content = '';

            content = '<div class="wpgmp_direction_container">';

            if (this.map_data.map_tabs.route_start_location == "textbox") {

                var start = '';

                if (this.map_data.map_tabs.direction_tab.default_start_location == undefined) {
                    start = '';
                } else {
                    start = this.map_data.map_tabs.direction_tab.default_start_location;
                }

                content += '<p><input value="' + start + '" placeholder="' + wpgmp_local.start_location + '" type="text" name="start_point" class="input start_point wpgmp_auto_suggest" autocomplete="off" /><span class="wpgmp_mcurrent_loction" title="' + wpgmp_local.take_current_location + '">&nbsp;</span></p>';
            } else if (this.map_data.map_tabs.route_start_location == "selectbox") {
                content += '<p><select name="start_point" class="input start_point" autocomplete="off" >';

                content += '<option value="">' + wpgmp_local.start_location + '</option>';

                if (this.places !== undefined) {
                    this.places.sort(this.sortByTitle);
                    $.each(this.places, function(index, place) {

                     content += '<option value="' + place.address + '">' + place.title + '</option>';

                    });
                }

                content += '</select>';
            }

            if (this.map_data.map_tabs.route_end_location == "textbox") {

                var end_point = '';

                if (this.map_data.map_tabs.direction_tab.default_end_location == undefined) {
                    end_point = '';
                } else {
                    end_point = this.map_data.map_tabs.direction_tab.default_end_location;
                }
                content += '<p><input value="' + end_point + '" placeholder="' + wpgmp_local.end_location + '" name="end_point" type="text" class="input end_point wpgmp_auto_suggest" autocomplete="off" /></p>';
            } else if (this.map_data.map_tabs.route_end_location == "selectbox") {
                content += '<p><select name="end_point" class="input end_point" autocomplete="off" >';

                content += '<option value="">' + wpgmp_local.end_location + '</option>';
                if (this.places !== undefined) {
                    this.places.sort(this.sortByTitle);

                    $.each(this.places, function(index, place) {
                        content += '<option value="' + place.address + '">' + place.title + '</option>';

                    });
                }

                content += '</select>';
            }

            content += '<p><select name="travel_mode" id="travaling_mode"> <option value="DRIVING" selected="selected">' + wpgmp_local.driving + '</option> <option value="BICYCLING">' + wpgmp_local.bicycling + '</option> <option value="WALKING">' + wpgmp_local.walking + '</option> <option value="TRANSIT">' + wpgmp_local.transit + '</option> </select></p>';

            content += '<p><select name="travel_unit" id="travel_unit"> <option value="metric" selected="selected">' + wpgmp_local.metric + '</option> <option value="imperial">' + wpgmp_local.imperial + '</option></select></p><p><input type="submit" value="' + wpgmp_local.find_direction + '" class="wpgmp_find_direction"></p><div class="directions-panel" style="overflow-y:scroll;display:none;"></div></div>';

            this.add_tab(this.map_data.map_tabs.direction_tab.direction_tab_title, content);
        },

        widget_category: function() {

            var map_obj = this;
            if (map_obj.map_data.map_tabs.category_tab.select_all === true) {
                var content = '<div class="wpgmp-select-all"><input checked="checked" type="checkbox" value="true" name="wpgmp_select_all">&nbsp&nbsp' + wpgmp_local.select_all + '</div>';
            } else {
                var content = '';
            }

            var categories_tab_data = {};
            var child_categories_tab_data = {};

            if (typeof map_obj.map_data.places != 'undefined') {
                
                map_obj.map_data.places_for_category_tabs = map_obj.map_data.places;
                map_obj.map_data.places_for_category_tabs = map_obj.sorting_inside_tabs(map_obj.map_data.places_for_category_tabs,map_obj.map_data.map_tabs.category_tab.cat_post_order);
                
                $.each(map_obj.map_data.places_for_category_tabs, function(index, place) {
                    if (typeof place.categories != 'undefined') {
                        $.each(place.categories, function(index, categories) {
                            var show = true;
                            var parent_cat = '';
                            parent_cat = map_obj.search_category(map_obj.map_data.map_tabs.category_tab.child_cats, categories.id, [], categories_tab_data, child_categories_tab_data);

                            if (parent_cat.length > 0)
                                show = false;

                            if (typeof categories.type != "undefined" && categories.type == 'category' && categories.name && show == true) {


                                if (typeof categories_tab_data[categories.id] == "undefined") {
                                    categories_tab_data[categories.id] = {};
                                    categories_tab_data[categories.id]['data'] = [];
                                }
                                categories_tab_data[categories.id]['cat_id'] = categories.id;
                                categories_tab_data[categories.id]['cat_title'] = categories.name;
                                categories_tab_data[categories.id]['cat_marker_icon'] = categories.icon;

                                if (categories.extension_fields && categories.extension_fields.cat_order) {
                                    categories_tab_data[categories.id]['cat_order'] = categories.extension_fields.cat_order;
                                }

                                var redirect_permalink = "";
                                if (place.location.redirect_permalink)
                                    redirect_permalink = place.location.redirect_permalink;

                                var redirect_custom_link = "";
                                if (place.location.redirect_custom_link)
                                    redirect_custom_link = place.location.redirect_custom_link;

                                categories_tab_data[categories.id]['data'].push({
                                    "cat_location_id": place.id,
                                    "cat_location_title": place.title,
                                    "cat_location_address": place.address,
                                    "cat_location_zoom": place.location.zoom,
                                    "onclick_action": place.location.onclick_action,
                                    "redirect_permalink": redirect_permalink,
                                    "redirect_custom_link": redirect_custom_link,
                                });

                            } else if (typeof categories.type != "undefined" && categories.type == 'category' && categories.name && show == false) {
                                if (typeof child_categories_tab_data[categories.id] == "undefined") {
                                    child_categories_tab_data[categories.id] = {};
                                    child_categories_tab_data[categories.id]['data'] = [];
                                    child_categories_tab_data[categories.id]['parent_cat'] = parent_cat;
                                }

                                child_categories_tab_data[categories.id]['cat_id'] = categories.id;
                                child_categories_tab_data[categories.id]['cat_title'] = categories.name;
                                child_categories_tab_data[categories.id]['cat_marker_icon'] = categories.icon;
                                if (categories.extension_fields && categories.extension_fields.cat_order) {
                                    child_categories_tab_data[categories.id]['cat_order'] = categories.extension_fields.cat_order;
                                }
                                var redirect_permalink = "";
                                if (place.location.redirect_permalink)
                                    redirect_permalink = place.location.redirect_permalink;

                                var redirect_custom_link = "";
                                if (place.location.redirect_custom_link)
                                    redirect_custom_link = place.location.redirect_custom_link;

                                child_categories_tab_data[categories.id]['data'].push({
                                    "cat_location_id": place.id,
                                    "cat_location_title": place.title,
                                    "cat_location_address": place.address,
                                    "cat_location_zoom": place.location.zoom,
                                    "onclick_action": place.location.onclick_action,
                                    "redirect_permalink": redirect_permalink,
                                    "redirect_custom_link": redirect_custom_link,
                                });

                                if (categories_tab_data[parent_cat] !== undefined) {
                                    if (typeof categories_tab_data[parent_cat]['child_cats'] == 'undefined') {
                                        categories_tab_data[parent_cat]['child_cats'] = [];
                                    }
                                    categories_tab_data[parent_cat]['child_cats'][categories.id] = categories.id;
                                }
                            }
                        });
                    }

                });
            }

            var category_orders = [];
            if (typeof categories_tab_data != 'undefined') {
                $.each(categories_tab_data, function(index, categories) {
                    var loc_count = categories.data.length;

                    if (typeof child_categories_tab_data != "undefined") {
                        $.each(child_categories_tab_data, function(c, ccat) {
                            if (ccat.parent_cat == categories.cat_id) {
                                loc_count = loc_count + ccat.data.length;
                                $.each(child_categories_tab_data, function(cc, cccat) {
                                    if (cccat.parent_cat == ccat.cat_id) {
                                        loc_count = loc_count + cccat.data.length;
                                    }
                                });
                            }
                        });
                    }
                    categories.loc_count = loc_count;

                    if (map_obj.map_data.map_tabs.category_tab.cat_order_by == 'count') {
                        category_orders.push(categories.loc_count);
                    } else if (map_obj.map_data.map_tabs.category_tab.cat_order_by == 'category') {
                        if (categories.cat_order) {
                            category_orders.push(categories.cat_order);
                        } else if (!categories.cat_order && map_obj.map_data.map_tabs.category_tab.all_cats[categories.cat_id] && map_obj.map_data.map_tabs.category_tab.all_cats[categories.cat_id].extensions_fields) {
                            categories.cat_order = map_obj.map_data.map_tabs.category_tab.all_cats[categories.cat_id].extensions_fields.cat_order;
                            category_orders.push(categories.cat_order);
                        }

                    } else {
                        if (categories.cat_title) {
                            category_orders.push(categories.cat_title);
                        } else if (!categories.cat_title && map_obj.map_data.map_tabs.category_tab.all_cats[categories.cat_id]) {
                            categories.cat_title = map_obj.map_data.map_tabs.category_tab.all_cats[categories.cat_id].group_map_title;
                            category_orders.push(categories.cat_title);
                        }

                    }
                });
            }
            if (map_obj.map_data.map_tabs.category_tab.cat_order_by == 'category') {
                category_orders.sort(function(a, b) {
                    return a - b
                });
            } else if (map_obj.map_data.map_tabs.category_tab.cat_order_by == 'count') {
                category_orders.sort(function(a, b) {
                    return b - a
                });
            } else {
                category_orders.sort();
            }
            var ordered_categories = [];
            var check_cats = [];
            $.each(category_orders, function(index, cat_title) {
                $.each(categories_tab_data, function(index, categories) {
                    var compare_with;
                    if (map_obj.map_data.map_tabs.category_tab.cat_order_by == 'count') {
                        compare_with = categories.loc_count;
                    } else if (map_obj.map_data.map_tabs.category_tab.cat_order_by == 'category') {
                        compare_with = categories.cat_order;
                    } else {
                        compare_with = categories.cat_title;
                    }

                    if (cat_title == compare_with && $.inArray(categories.cat_id, check_cats) == -1) {
                        ordered_categories.push(categories);
                        check_cats.push(categories.cat_id);
                    }
                });
            });

            if (typeof ordered_categories != 'undefined') {
                $.each(ordered_categories, function(index, categories) {

                    var category_image = '';

                    if (!categories.cat_title && map_obj.map_data.map_tabs.category_tab.all_cats[categories.cat_id]) {
                        categories.cat_title = map_obj.map_data.map_tabs.category_tab.all_cats[categories.cat_id].group_map_title;
                    }

                    if (!categories.cat_marker_icon && map_obj.map_data.map_tabs.category_tab.all_cats[categories.cat_id]) {
                        categories.cat_marker_icon = map_obj.map_data.map_tabs.category_tab.all_cats[categories.cat_id].group_marker;
                    }

                    if (typeof categories.cat_marker_icon != 'undefined') {
                        category_image = '<span class="arrow"><img src="' + categories.cat_marker_icon + '"></span>';
                    }

                    content += '<div class="wpgmp_tab_item" data-container="wpgmp-category-tab-item">';

                    if (categories.child_cats !== undefined) {
                        categories.child_cats = categories.child_cats.filter(function(v) {
                            return v !== ''
                        });
                        var child_cats_str = ' data-child-cats="' + categories.child_cats.join(",") + '"';
                    } else {
                        var child_cats_str = '';
                    }

                    content += '<input type="checkbox"' + child_cats_str + ' data-marker-category="' + categories.cat_id + '" value="' + categories.cat_id + '">';

                    var loc_count = categories.loc_count;;

                    $.each(map_obj.map_data.map_tabs.category_tab.child_cats, function(k, v) {
                        if (v == categories.cat_id && loc_count == 0)
                            loc_count = "";
                    });

                    var location_count = "";

                    if (map_obj.map_data.map_tabs.category_tab.show_count === true && loc_count != "") {
                        location_count = " (" + loc_count + ")";
                    }

                    content += '<a href="javascript:void(0);" class="wpgmp_cat_title wpgmp-accordion accordion-close">' + categories.cat_title + location_count + category_image + '</a>';

                    if (map_obj.map_data.map_tabs.category_tab.hide_location !== true) {

                        content += '<div class="scroll-pane" style="max-height:300px;width:100%;">';

                        content += '<ul class="wpgmp_location_container">';

                        $.each(categories.data, function(name, location) {

                            if (location.onclick_action == "marker") {
                                content += '<li><input type="checkbox" data-marker-location="' + location.cat_location_id + '"  value="' + location.cat_location_id + '" /><a data-marker="' + location.cat_location_id + '" data-zoom="' + location.cat_location_zoom + '" href="javascript:void(0);">' + location.cat_location_title + '</a></li>';
                            } else if (location.onclick_action == "post") {
                                content += '<li><input type="checkbox" data-marker-location="' + location.cat_location_id + '"  value="' + location.cat_location_id + '" /><a href="' + location.redirect_permalink + '" target="_blank">' + location.cat_location_title + '</a></li>';
                            } else if (location.onclick_action == "custom_link") {
                                content += '<li><input type="checkbox" data-marker-location="' + location.cat_location_id + '"  value="' + location.cat_location_id + '" /><a href="' + location.redirect_custom_link + '" target="_blank">' + location.cat_location_title + '</a></li>';
                            }

                        });

                        content += '</ul>';

                        content += '</div>';
                    }

                    content += '</div>';

                    if (typeof child_categories_tab_data != "undefined") {
                        var padding = 20;
                        content += map_obj.display_sub_categories(child_categories_tab_data, categories.cat_id, '', padding);
                    }
                });
            }

            map_obj.add_tab(map_obj.map_data.map_tabs.category_tab.cat_tab_title, content);

        },

        search_category: function(array, cat_id, index, categories_tab_data, child_categories_tab_data) {
            var map_obj = this;
            var flag = true;
            $.each(array, function(k, i) {
                if (k == cat_id) {
                    index = i;
                    flag = false;
                    if (typeof child_categories_tab_data[cat_id] == "undefined") {
                        child_categories_tab_data[cat_id] = {};
                        child_categories_tab_data[cat_id]['data'] = [];
                        child_categories_tab_data[cat_id]['parent_cat'] = i;
                        child_categories_tab_data[cat_id]['cat_id'] = cat_id;
                        $.each(map_obj.categories, function(k, e) {
                            if (e.group_map_id == cat_id) {
                                child_categories_tab_data[cat_id]['cat_title'] = e.group_map_title;
                                child_categories_tab_data[cat_id]['cat_marker_icon'] = e.group_marker;
                            }
                        });
                    }
                    index = map_obj.search_category(map_obj.map_data.map_tabs.category_tab.child_cats, i, index, categories_tab_data, child_categories_tab_data);
                }
            });
            if (flag == true) {
                if (typeof categories_tab_data[cat_id] == "undefined") {
                    categories_tab_data[cat_id] = {};
                    categories_tab_data[cat_id]['data'] = [];
                    categories_tab_data[cat_id]['cat_id'] = cat_id;
                    $.each(map_obj.categories, function(k, e) {
                        if (e.group_map_id == cat_id) {
                            categories_tab_data[cat_id]['cat_title'] = e.group_map_title;
                            categories_tab_data[cat_id]['cat_marker_icon'] = e.group_marker;
                        }
                    });
                }
            }
            return index;
        },
        display_sub_categories: function(child_categories_tab_data, cat_id, content, padding) {
            var map_obj = this;

            var category_orders = [];
            if (typeof child_categories_tab_data != 'undefined') {
                $.each(child_categories_tab_data, function(index, categories) {
                    var loc_count = categories.data.length;

                    if (typeof child_categories_tab_data != "undefined") {
                        $.each(child_categories_tab_data, function(c, ccat) {
                            if (ccat.parent_cat == categories.cat_id) {
                                loc_count = loc_count + ccat.data.length;
                                $.each(child_categories_tab_data, function(cc, cccat) {
                                    if (cccat.parent_cat == ccat.cat_id) {
                                        loc_count = loc_count + cccat.data.length;
                                    }
                                });
                            }
                        });
                    }
                    categories.loc_count = loc_count;

                    if (map_obj.map_data.map_tabs.category_tab.cat_order_by == 'count') {
                        category_orders.push(categories.loc_count);
                    } else if (map_obj.map_data.map_tabs.category_tab.cat_order_by == 'category') {
                        if (categories.cat_order) {
                            category_orders.push(categories.cat_order);
                        } else if (!categories.cat_order && map_obj.map_data.map_tabs.category_tab.all_cats[categories.cat_id]) {
                            categories.cat_order = map_obj.map_data.map_tabs.category_tab.all_cats[categories.cat_id].extensions_fields.cat_order;
                            category_orders.push(categories.cat_order);
                        }

                    } else {
                        if (categories.cat_title) {
                            category_orders.push(categories.cat_title);
                        } else if (!categories.cat_title && map_obj.map_data.map_tabs.category_tab.all_cats[categories.cat_id]) {
                            categories.cat_title = map_obj.map_data.map_tabs.category_tab.all_cats[categories.cat_id].group_map_title;
                            category_orders.push(categories.cat_title);
                        }

                    }
                });
            }
            if (map_obj.map_data.map_tabs.category_tab.cat_order_by == 'category') {
                category_orders.sort(function(a, b) {
                    return a - b
                });
            } else if (map_obj.map_data.map_tabs.category_tab.cat_order_by == 'count') {
                category_orders.sort(function(a, b) {
                    return b - a
                });
            } else {
                category_orders.sort();
            }
            var ordered_categories = [];
            var check_cats = [];
            $.each(category_orders, function(index, cat_title) {
                $.each(child_categories_tab_data, function(index, categories) {
                    var compare_with;
                    if (map_obj.map_data.map_tabs.category_tab.cat_order_by == 'count') {
                        compare_with = categories.loc_count;
                    } else if (map_obj.map_data.map_tabs.category_tab.cat_order_by == 'category') {
                        compare_with = categories.cat_order;
                    } else {
                        compare_with = categories.cat_title;
                    }

                    if (cat_title == compare_with && $.inArray(categories.cat_id, check_cats) == -1) {
                        ordered_categories.push(categories);
                        check_cats.push(categories.cat_id);
                    }
                });
            });

            $.each(ordered_categories, function(index, child_cat) {
                if (child_cat.parent_cat == cat_id) {
                    var category_image = '';

                    if (!child_cat.cat_title && map_obj.map_data.map_tabs.category_tab.all_cats[child_cat.cat_id]) {
                        child_cat.cat_title = map_obj.map_data.map_tabs.category_tab.all_cats[child_cat.cat_id].group_map_title;
                    }

                    if (!child_cat.cat_marker_icon && map_obj.map_data.map_tabs.category_tab.all_cats[child_cat.cat_id]) {
                        child_cat.cat_marker_icon = map_obj.map_data.map_tabs.category_tab.all_cats[child_cat.cat_id].group_marker;
                    }
                    if (typeof child_cat.cat_marker_icon != 'undefined') {
                        category_image = '<span class="arrow"><img src="' + child_cat.cat_marker_icon + '"></span>';
                    }
                    content += '<div class="wpgmp_tab_item" data-container="wpgmp-category-tab-item" style="padding-left:' + padding + 'px;">';

                    if (map_obj.map_data.map_tabs.category_tab.parent_cats !== undefined && map_obj.map_data.map_tabs.category_tab.parent_cats[child_cat.cat_id])
                        var child_cats_str = ' data-child-cats="' + map_obj.map_data.map_tabs.category_tab.parent_cats[child_cat.cat_id].join(",") + '"';
                    else
                        var child_cats_str = '';

                    content += '<input type="checkbox"' + child_cats_str + ' data-parent-cat="' + cat_id + '" data-marker-category="' + child_cat.cat_id + '" value="' + child_cat.cat_id + '">';

                    var loc_count = child_cat.loc_count;

                    $.each(map_obj.map_data.map_tabs.category_tab.child_cats, function(k, v) {
                        if (v == child_cat.cat_id && loc_count == 0)
                            loc_count = "";
                    });

                    var location_count = "";
                    if (map_obj.map_data.map_tabs.category_tab.show_count === true && loc_count != "") {
                        location_count = " (" + loc_count + ")";
                    } else {
                        location_count = "";
                    }

                    content += '<a href="javascript:void(0);" class="wpgmp_cat_title wpgmp-accordion accordion-close">' + child_cat.cat_title + location_count + category_image + '</a>';

                    if (map_obj.map_data.map_tabs.category_tab.hide_location !== true) {

                        content += '<div class="scroll-pane" style="height: 97px; width:100%;">';
                        content += '<ul class="wpgmp_location_container">';

                        $.each(child_cat.data, function(name, location) {

                            if (location.onclick_action == "marker") {
                                content += '<li><input type="checkbox" data-marker-location="' + location.cat_location_id + '"  value="' + location.cat_location_id + '" /><a data-marker="' + location.cat_location_id + '" data-zoom="' + location.cat_location_zoom + '" href="javascript:void(0);">' + location.cat_location_title + '</a></li>';
                            } else if (location.onclick_action == "post") {
                                content += '<li><input type="checkbox" data-marker-location="' + location.cat_location_id + '"  value="' + location.cat_location_id + '" /><a href="' + location.redirect_permalink + '" target="_blank">' + location.cat_location_title + '</a></li>';
                            } else if (location.onclick_action == "custom_link") {
                                content += '<li><input type="checkbox" data-marker-location="' + location.cat_location_id + '"  value="' + location.cat_location_id + '" /><a href="' + location.redirect_custom_link + '" target="_blank">' + location.cat_location_title + '</a></li>';
                            }

                        });

                        content += '</ul>';
                        content += '</div>';
                    }
                    content += '</div>';
                    content += map_obj.display_sub_categories(child_categories_tab_data, child_cat.cat_id, '', (padding + 20));
                } else if ((index + 1) == child_categories_tab_data.length)
                    return;
            });
            return content;
        },
        sorting_inside_tabs: function(data,in_order) {
                         
             if (in_order == 'asc') {
                 data.sort();
             }
             if (in_order == 'desc') {
                 data.sort();
                 data.reverse();
             }
            return data;
        },
        sorting: function(order_by, in_order, data_type) {

            switch (order_by) {

                case 'category':
                    this.places.sort(this.sortByCategory);
                    this.show_places.sort(this.sortByCategory);
                    if (in_order == 'desc') {
                        this.places.reverse();
                        this.show_places.reverse();
                    }
                    break;

                case 'title':

                    if (this.map_data.places !== undefined) {
                        this.map_data.places.sort(this.sortByTitle);
                    }
                    if (this.show_places !== undefined) {
                        this.show_places.sort(this.sortByTitle);
                    }
                    if (in_order == 'desc') {
                        this.map_data.places.reverse();
                        this.places.reverse();
                        this.show_places.reverse();
                    }
                    break;

                case 'address':
                    this.map_data.places.sort(this.sortByAddress);
                    this.show_places.sort(this.sortByAddress);
                    if (in_order == 'desc') {
                        this.places.reverse();
                        this.show_places.reverse();
                    }
                    break;
                default:

                    var first_place = this.map_data.places[0];
                    if (typeof first_place[order_by] != 'undefined') {
                        this.map_data.places.sort(this.sortByPlace(order_by, data_type));
                        this.show_places.sort(this.sortByPlace(order_by, data_type));
                    } else if (typeof first_place.location[order_by] != 'undefined') {
                        this.map_data.places.sort(this.sortByLocation(order_by, data_type));
                        this.show_places.sort(this.sortByLocation(order_by, data_type));
                    } else if (typeof first_place.location.extra_fields[order_by] != 'undefined') {
                        this.map_data.places.sort(this.sortByExtraFields(order_by, data_type));
                        this.show_places.sort(this.sortByExtraFields(order_by, data_type));
                    }

                    if (in_order == 'desc') {
                        this.places.reverse();
                        this.show_places.reverse();
                    }
            }
        },

        sortByExtraFields: function(order_by, data_type) {

            return function(a, b) {

                if (typeof b.location.extra_fields[order_by] != 'undefined' && typeof a.location.extra_fields[order_by] != 'undefined') {

                    if (b.location.extra_fields[order_by] == null) {
                        b.location.extra_fields[order_by] = '';
                    }

                    if (a.location.extra_fields[order_by] == null) {
                        a.location.extra_fields[order_by] = '';
                    }

                    if (data_type == 'num') {
                        var a_val = parseInt(a.location.extra_fields[order_by]);
                        var b_val = parseInt(b.location.extra_fields[order_by]);
                    } else {
                        var a_val = a.location.extra_fields[order_by].toLowerCase();
                        var b_val = b.location.extra_fields[order_by].toLowerCase();
                    }

                    return ((a_val < b_val) ? -1 : ((a_val > b_val) ? 1 : 0));

                }
            }

        },
        sortByLocation: function(order_by, data_type) {
            return function(a, b) {

                if (b.location[order_by] && a.location[order_by]) {

                    if (a.location[order_by] && b.location[order_by]) {
                        var a_val = a.location[order_by].toLowerCase();
                        var b_val = b.location[order_by].toLowerCase();
                        if (data_type == 'num') {
                            a_val = parseInt(a_val);
                            b_val = parseInt(b_val);
                        }
                        return ((a_val < b_val) ? -1 : ((a_val > b_val) ? 1 : 0));
                    }

                }
            }

        },
        sortByPlace: function(order_by, data_type) {

            return function(a, b) {

                if (b[order_by] && a[order_by]) {

                    if (a[order_by] && b[order_by]) {
                        var a_val = a[order_by].toLowerCase();
                        var b_val = b[order_by].toLowerCase();
                        if (data_type == 'num') {
                            a_val = parseInt(a_val);
                            b_val = parseInt(b_val);
                        }
                        return ((a_val < b_val) ? -1 : ((a_val > b_val) ? 1 : 0));
                    }

                }
            }

        },
        sortByCategory: function(a, b) {
            if (b.categories[0] && a.categories[0]) {
                if (a.categories[0].name && b.categories[0].name) {
                    var a_val = a.categories[0].name.toLowerCase();
                    var b_val = b.categories[0].name.toLowerCase();
                    return ((a_val < b_val) ? -1 : ((a_val > b_val) ? 1 : 0));
                }

            }
        },

        sortByTitle: function(a, b) {
            var a_val = a.title.toLowerCase();
            var b_val = b.title.toLowerCase();
            return ((a_val < b_val) ? -1 : ((a_val > b_val) ? 1 : 0));
        },

        sortByValue: function(a, b) {
            var a_val = a.toLowerCase();
            var b_val = b.toLowerCase();
            return ((a_val < b_val) ? -1 : ((a_val > b_val) ? 1 : 0));
        },

        sortByAddress: function(a, b) {
            var a_val = a.address.toLowerCase();
            var b_val = b.address.toLowerCase();
            return ((a_val < b_val) ? -1 : ((a_val > b_val) ? 1 : 0));
        },

        update_filters: function() {
            var map_obj = this;
            var filters = {};

            var all_dropdowns = $(map_obj.container).find('[data-filter="dropdown"]');
            var all_checkboxes = $(map_obj.container).find('[data-filter="checklist"]:checked');
            var all_list = $(map_obj.container).find('[data-filter="list"].fc_selected');

            $.each(all_dropdowns, function(index, element) {
                if ($(this).val() != '') {

                    if (typeof filters[$(this).data('name')] == 'undefined') {
                        filters[$(this).data('name')] = [];
                    }

                    filters[$(this).data('name')].push($(this).val());
                }
            });

            $.each(all_checkboxes, function(index, element) {

                if (typeof filters[$(this).data('name')] == 'undefined') {
                    filters[$(this).data('name')] = [];
                }

                filters[$(this).data('name')].push($(this).val());

            });

            $.each(all_list, function(index, element) {

                if (typeof filters[$(this).data('name')] == 'undefined') {
                    filters[$(this).data('name')] = [];
                }

                filters[$(this).data('name')].push($(this).data('value').toString());

            });
            this.apply_filters(filters);

        },

        apply_url_filters: function() {

            var map_obj = this;
            var search = location.search.substring(1);
            var url_filters = $.parseParams(search || '');
            var filters = {};
            if (!$.isEmptyObject(url_filters)) {

                map_obj.url_filters = url_filters;

                $.each(url_filters, function(index, element) {
                    if (index == 'search') {
                        $(map_obj.container).find('[data-input="wpgmp-search-text"]').val(element);
                    }
                });

                map_obj.apply_filters(filters);
            }
        },

        apply_filters: function(filters) {

            var map_obj = this;
            var showAll = true;
            var show = true;
            map_obj.show_places = [];

            var enable_search_term = false;
            // Filter by search box.
            if ($(map_obj.container).find('[data-input="wpgmp-search-text"]').length > 0) {
                var search_term = $(map_obj.container).find('[data-input="wpgmp-search-text"]').val();
                search_term = search_term.toLowerCase();
                if (search_term.length > 0) {
                    enable_search_term = true;
                }
            }

            if (((map_obj.map_data.map_tabs && map_obj.map_data.map_tabs.category_tab && map_obj.map_data.map_tabs.category_tab.cat_tab === true) || $(map_obj.container).find('input[data-marker-category]').length > 0)) {
                var all_selected_category_sel = $(map_obj.container).find('input[data-marker-category]:checked');
                var all_selected_category = [];
                var all_not_selected_location = [];
                if (all_selected_category_sel.length > 0) {
                    $.each(all_selected_category_sel, function(index, selected_category) {
                    all_selected_category.push($(selected_category).data("marker-category"));
                        var all_not_selected_location_sel = $(selected_category).closest('[data-container="wpgmp-category-tab-item"]').find('input[data-marker-location]:not(:checked)');
                        if (all_not_selected_location_sel.length > 0) {
                            $.each(all_not_selected_location_sel, function(index, not_selected_location) {
                                all_not_selected_location.push($(not_selected_location).data("marker-location"));
                            });
                        }
                    });
                }
                var all_selected_location_sel = $(map_obj.container).find('[data-container="wpgmp-category-tab-item"]').find('input[data-marker-location]:checked');
                var all_selected_location = [];
                if (all_selected_location_sel.length > 0) {
                    $.each(all_selected_location_sel, function(index, selected_location) {
                        all_selected_location.push($(selected_location).data("marker-location"));
                    });
                }
            }



            if (typeof map_obj.map_data.places != 'undefined') {
                $.each(map_obj.map_data.places, function(place_key, place) {
                    show = true;

                    if (typeof filters != 'undefined') {
                        $.each(filters, function(filter_key, filter_values) {

                            var in_fields = false;

                            if ($.isArray(filter_values)) {

                                if (typeof place.categories != 'undefined' && filter_key == "category") {

                                    $.each(place.categories, function(cat_index, category) {
                                        if ($.inArray(category.id, filter_values) > -1) {
                                            in_fields = true;
                                        }
                                    });
                                }

                                if (typeof place.custom_filters != 'undefined') {
                                    $.each(place.custom_filters, function(k, val) {
                                        if (filter_key == k) {
                                            in_fields = false;
                                            if ($.isArray(val)) {
                                                $.each(val, function(index, value) {
                                                    if ($.inArray(value, filter_values) > -1)
                                                        in_fields = true;
                                                });
                                            } else if (val == filter_values.val)
                                                in_fields = true;
                                        }
                                    });
                                }

                                if (typeof place[filter_key] != 'undefined') {
                                    if ($.inArray(place[filter_key], filter_values) > -1) {
                                        in_fields = true;
                                    }
                                } else if (typeof place.location[filter_key] != 'undefined') {
                                    if ($.inArray(place.location[filter_key], filter_values) > -1) {
                                        in_fields = true;

                                    }
                                } else if (place.location.extra_fields && typeof place.location.extra_fields[filter_key] != 'undefined') {

                                    var dropdown_value = filter_values[0];
                                    if (place.location.extra_fields[filter_key] && place.location.extra_fields[filter_key].indexOf(dropdown_value) > -1) {
                                        in_fields = true;
                                    } else if ($.inArray(place.location.extra_fields[filter_key], filter_values) > -1) {
                                        in_fields = true;
                                    }

                                }

                                if (in_fields == false)
                                    show = false;

                            } else {
                                filter_values.val = "";
                            }
                        });
                    }

                    //Apply Search Filter.
                    if (enable_search_term === true && show === true) {

                        if (place.title != undefined && place.title.toLowerCase().indexOf(search_term) >= 0) {
                            show = true;

                        } else if (place.content != undefined && place.content.toLowerCase().indexOf(search_term) >= 0) {
                            show = true;

                        } else if (String(place.location.lat).toLowerCase().indexOf(search_term) >= 0) {
                            show = true;

                        } else if (String(place.location.lng).toLowerCase().indexOf(search_term) >= 0) {
                            show = true;

                        } else if (place.address && place.address.toString().toLowerCase().indexOf(search_term) >= 0) {
                            show = true;
                        } else if (place.location.state && place.location.state.toLowerCase().indexOf(search_term) >= 0) {
                            show = true;

                        } else if (place.location.country && place.location.country.toLowerCase().indexOf(search_term) >= 0) {
                            show = true;

                        } else if (place.location.postal_code && String(place.location.postal_code).toLowerCase().indexOf(search_term) >= 0) {
                            show = true;

                        } else if (place.location.city && place.location.city.toLowerCase().indexOf(search_term) >= 0) {
                            show = true;
                        } else if (typeof map_obj.search_area != 'undefined' && map_obj.search_area != '' && map_obj.wpgmp_within_radius(place, map_obj.search_area) === true) {
                            show = true;
                        } else {
                            show = false;
                        }

                        if (typeof place.location.extra_fields != 'undefined') {
                            $.each(place.location.extra_fields, function(field, value) {

                                if (value) {
                                    value = value.toString();
                                    if (value && value.toLowerCase().indexOf(search_term) >= 0)
                                        show = true;
                                }


                            });
                        }

                    }

                    //Exclude locations without category if location filters are choosed by user
                    if ((place.categories.length == undefined || place.categories.length == 'undefined') && all_selected_category && (all_selected_category.length > 0) && ($(map_obj.container).find('input[name="wpgmp_select_all"]').is(":checked") == false) && show) {
                        show = false;
                    }

                    // if checked category
                    if (all_selected_category && show != false && place.categories.length != undefined) {

                        var in_checked_category = false;

                        if (all_selected_category.length === 0) {
                            // means no any category selected so show those location without categories.
                            if (typeof place.categories != 'undefined') {
                                $.each(place.categories, function(cat_index, category) {
                                    if (category.id === '')
                                        in_checked_category = true;
                                });
                            }
                        } else {
                            if (typeof place.categories != 'undefined') {
                                $.each(place.categories, function(cat_index, category) {
                                    if (category.id === '')
                                        in_checked_category = true;
                                    else if ($.inArray(parseInt(category.id), all_selected_category) > -1) {
                                        in_checked_category = true;
                                        place.marker.setIcon(category.icon);
                                    }

                                });
                            }
                        }

                        //Hide unchecked  locations.
                        if (all_not_selected_location.length !== 0) {
                            if ($.inArray(parseInt(place.id), all_not_selected_location) > -1) {
                                in_checked_category = false;
                            }
                        }

                        //var checked_categories = $(map_obj.container).find('input[data-marker-category]:checked').length;
                        if (in_checked_category === false)
                            show = false;
                        else
                            show = true;



                        //Show Here checked location.
                        if (all_selected_location.length !== 0) {
                            if ($.inArray(parseInt(place.id), all_selected_location) > -1) {
                                show = true;
                            }
                        }

                    }

                    place.marker.visible = show;
                    place.marker.setVisible(show);
                    if (show == false) {
                        place.infowindow.close();
                    }
                    place.marker.setAnimation(null);
                    if (show === true)
                        map_obj.show_places.push(place);
                });
            }


            if (typeof map_obj.map_data.map_options.bound_map_after_filter !== typeof undefined &&
                map_obj.map_data.map_options.bound_map_after_filter === true) {

                var after_filter_bounds = new google.maps.LatLngBounds();

                for (var j = 0; j < map_obj.show_places.length; j++) {
                    var markerInResult = new google.maps.LatLng(map_obj.show_places[j]['location']['lat'], map_obj.show_places[j]['location']['lng']);
                    after_filter_bounds.extend(markerInResult);
                }

                map_obj.map.fitBounds(after_filter_bounds);

            }

            if (map_obj.map_data.listing) {

                if ($(map_obj.container).find('[data-filter="map-sorting"]').val()) {
                    var order_data = $(map_obj.container).find('[data-filter="map-sorting"]').val().split("__");
                    var data_type = '';
                    if (order_data[0] !== '' && order_data[1] !== '') {

                        if (typeof order_data[2] != 'undefined') {
                            data_type = order_data[2];
                        }
                        map_obj.sorting(order_data[0], order_data[1], data_type);
                    }
                } else {
                    if (map_obj.map_data.listing.default_sorting) {
                        var data_type = '';
                        if (map_obj.map_data.listing.default_sorting.orderby == 'listorder') {
                            data_type = 'num';
                        }
                        map_obj.sorting(map_obj.map_data.listing.default_sorting.orderby, map_obj.map_data.listing.default_sorting.inorder, data_type);
                    }
                }

                map_obj.update_places_listing();
            }

            if (map_obj.map_data.marker_cluster) {
                map_obj.set_marker_cluster();
            }

        },

        create_perpage_option: function() {

            var map_obj = this;
            var options = '';
            var content = '';

            content += '<select name="map_perpage_location_sorting" data-filter="map-perpage-location-sorting" class="choose_salutation">';
            content += '<option value="' + map_obj.map_data.listing.pagination.listing_per_page + '">' + wpgmp_local.show_locations + '</option>';
            content += '<option value="25">25</option>';
            content += '<option value="50">50</option>';
            content += '<option value="100">100</option>';
            content += '<option value="200">200</option>';
            content += '<option value="500">500</option>';
            content += '<option value="' + map_obj.show_places.length + '">' + wpgmp_local.all_location + '</option>';
            content += '</select>';

            return content;

        },

        create_sorting: function() {

            var options = '';

            var content = '';

            if (this.map_data.listing.display_sorting_filter === true) {
                content += '<select name="map_sorting" data-filter="map-sorting"><option value="">' + wpgmp_local.sort_by + '</option>';
                $.each(this.map_data.listing.sorting_options, function(id, name) {
                    content += "<option value='" + id + "'>" + name + "</option>";
                });
                content += '</select>';
            }

            return content;
        },

        create_radius: function() {

            var options = '';

            var content = '';
            if (this.map_data.listing.display_radius_filter === true) {

                var radius_options = this.map_data.listing.radius_options;

                if(radius_options != undefined) {
                    content += '<select data-name="radius" name="map_radius"><option value="">' + wpgmp_local.select_radius + '</option>';
                    var radius_dimension = this.map_data.listing.radius_dimension;
                    $.each(radius_options.split(','), function(id, name) {
                        if (radius_dimension == 'miles') {
                            content += "<option value='" + name + "'>" + name + ' ' + wpgmp_local.miles + "</option>";
                        } else {
                            content += "<option value='" + name + "'>" + name + ' ' + wpgmp_local.km + "</option>";
                        }
                    });
                    content += '</select>';
                }
            }

            return content;
        },
        sort_numeric_value_filters:function(filter_options){


            if(!filter_options.some(isNaN)){

                filter_options.sort(function(a, b) {
                  return a - b;
                });

            }
            return filter_options;
        },
        custom_filters: function() {
            var map_obj = this;
            var options = '';
            var places = this.map_data.places;
            var wpgmp_filters = this.map_data.filters;
            if (typeof wpgmp_filters == 'undefined' || typeof wpgmp_filters.custom_filters == 'undefined' || wpgmp_filters.custom_filters.length == 0) {
                return;
            }

            $.each(wpgmp_filters.custom_filters, function(template_shortcode, filter_options) {
                var all_filters = [];
                var content = '';
                var filters = {};
                $.each(filter_options, function(filter_type, filter_parameter) {

                    $.each(filter_parameter, function(filter_name, filter_label) {
                        $.each(places, function(index, place) {
                            if (filter_name == 'category') {
                                if (typeof place.categories == 'undefined') {
                                    place.categories = {};
                                }
                                $.each(place.categories, function(cat_index, category) {

                                    if (typeof filters[category.type] == 'undefined') {
                                        filters[category.type] = {};
                                    }
                                    if (category.name) {
                                        filters[category.type][category.name] = category.id;
                                    }

                                });

                            } else {

                                if (typeof place[filter_name] != 'undefined') {
                                    if (typeof filters[filter_name] == 'undefined') {
                                        filters[filter_name] = {};
                                    }
                                    if (place[filter_name]) {
                                        filters[filter_name][place[filter_name]] = place[filter_name];
                                    }
                                }

                                if (typeof place.location.extra_fields[filter_name] != 'undefined') {
                                    if (typeof filters[filter_name] == 'undefined') {
                                        filters[filter_name] = {};
                                    }
                                    if (place.location.extra_fields[filter_name]) {
                                        filters[filter_name][place.location.extra_fields[filter_name]] = place.location.extra_fields[filter_name];
                                    }
                                }

                                if (typeof place.location[filter_name] != 'undefined') {
                                    if (typeof filters[filter_name] == 'undefined') {
                                        filters[filter_name] = {};
                                    }
                                    if (place.location[filter_name]) {
                                        filters[filter_name][place.location[filter_name]] = place.location[filter_name];
                                    }
                                }

                                if (typeof place.custom_filters != 'undefined' && typeof place.custom_filters[filter_name] != 'undefined') {
                                    if (typeof filters[filter_name] == 'undefined') {
                                        filters[filter_name] = {};
                                    }
                                    if (place.custom_filters[filter_name]) {
                                        var options = place.custom_filters[filter_name];
                                        if ($.isArray(options)) {
                                            $.each(options, function(index, value) {
                                                filters[filter_name][value] = value;
                                            });
                                        } else {
                                            filters[filter_name][options] = options;
                                        }
                                    }
                                }

                                // It could be radius filter. 
                                if (filter_name == 'radius') {
                                    if (typeof filters[filter_name] == 'undefined') {
                                        filters[filter_name] = {};
                                    }

                                    var radius_options = wpgmp_filters.radius_options;
                                    var radius_dimension = wpgmp_filters.radius_dimension;
                                    $.each(radius_options.split(','), function(id, name) {
                                        if (radius_dimension == 'miles') {
                                            filters[filter_name][name + ' ' + wpgmp_local.miles] = name;
                                        } else {
                                            filters[filter_name][name + ' ' + wpgmp_local.km] = name;
                                        }
                                    });

                                }
                            }

                        });

                    });



                    if (filter_type == 'dropdown') {
                        if (typeof filters != 'undefined') {
                            $.each(filters, function(index, options) {
                                options = map_obj.sort_object_by_value(options);
                                options = map_obj.sort_object_by_unique_values(options);
                                options = map_obj.sort_numeric_value_filters(options);
                                content += '<select data-filter="dropdown"  name="place_' + index + '" data-name = "' + index + '">';
                                content += '<option value="">' + ((filter_parameter[index]) ? filter_parameter[index] : 'Select ' + index) + '</option>';
                                $.each(options, function(name, value) {
                                    var optionlabel = value;
                                    value = value.replace("'", "&#39;");
                                    value = value.replace('"', '&#34;');
                                    if (value != '' && value != null)
                                        content += "<option value='" + value + "'>" + optionlabel + "</option>";
                                });
                                content += '</select>';
                            });
                        }
                    }

                    if (filter_type == 'checklist') {
                        if (typeof filters != 'undefined') {
                            $.each(filters, function(index, options) {
                                content += '<div class="wpgmp_filters_checklist">';
                                content += '<label  data-filter = "place_' + index + '" >' + ((wpgmp_filters.custom_filters[index]) ? wpgmp_filters.custom_filters[index] : 'Select ' + index) + '</label>';
                                $.each(options, function(name, value) {
                                    if (value != '' && value != null)
                                        content += "<input data-filter='checklist' type='checkbox' data-name = '" + index + "' value='" + value + "'>" + name;
                                });
                                content += '</div>';
                            });
                        }
                    }

                    if (filter_type == 'list') {
                        if (typeof filters != 'undefined') {
                            $.each(filters, function(index, options) {
                                content += '<div class="wpgmp_filters_list">';
                                content += '<label  data-filter = "place_' + index + '" >' + ((wpgmp_filters.custom_filters[index]) ? wpgmp_filters.custom_filters[index] : 'Select ' + index) + '</label><ul>';
                                $.each(options, function(name, value) {
                                    if (value != '' && value != null)
                                        content += "<li data-filter='list' data-name = '" + index + "' data-value='" + value + "'>" + name + "</li>";
                                });
                                content += '</ul></div>';
                            });
                        }
                    }
                });

                //$(map_obj.container).find("."+template_shortcode).html(content);
                $('body').find(wpgmp_filters.filters_container).append(content);
            });



            // now create select boxes



        },
        sort_object_by_keyvalue: function(options, by, type, in_order) {

            var sortable = [];
            for (var key in options) {
                sortable.push(options[key]);
            }

            sortable.sort(this.sortByPlace(by, type));

            if (in_order == 'desc') {
                sortable.reverse();
            }

            return sortable;
        },
        sort_object_by_unique_values: function(options) {

            var new_options = [];
            var uniqueNames = [];
            for (var key in options) {

                if (options[key].indexOf(',') > -1) {
                    options[key].split(/\s*,\s*/).forEach(function(single_option_value) {
                        new_options.push(single_option_value.trim());
                    });
                } else {
                    new_options.push(options[key].trim());
                }

            }

            uniqueNames = new_options.filter(function(item, pos) {
                return new_options.indexOf(item) == pos;
            });

            return uniqueNames.sort();
        },
        sort_object_by_value: function(options) {

            var sortable = [];
            for (var key in options) {
                sortable.push(key);
            }

            sortable.sort(this.sortByValue);
            var new_options = {}
            for (var i = 0; i < sortable.length; i++) {
                new_options[sortable[i]] = options[sortable[i]];
            }

            return new_options;
        },
        create_filters: function() {
            var map_obj = this;
            var options = '';
            var filters = {};
            var places = this.map_data.places;
            var wpgmp_listing_filter = this.map_data.listing;
            var wpgmp_alltfilter = wpgmp_listing_filter.display_taxonomies_all_filter;

            $.each(places, function(index, place) {
                if (typeof place.categories == 'undefined') {
                    place.categories = {};
                }
                $.each(place.categories, function(cat_index, category) {

                    if (typeof filters[category.type] == 'undefined') {
                        filters[category.type] = {};
                    }

                    if (category.name) {
                        if (category.extension_fields && category.extension_fields.cat_order) {
                            filters[category.type][category.name] = {
                                'id': category.id,
                                'order': category.extension_fields.cat_order,
                                'name': category.name
                            };
                        } else {
                            filters[category.type][category.name] = {
                                'id': category.id,
                                'order': 0,
                                'name': category.name
                            };
                        }

                    }

                });
            });
            // now create select boxes

            var content = '',
                by = 'name',
                type = '',
                inorder = 'asc';

            if (map_obj.map_data.listing) {
                if (map_obj.map_data.listing.default_sorting) {
                    if (map_obj.map_data.listing.default_sorting.orderby == 'listorder') {
                        by = 'order';
                        type = 'num';
                        inorder = map_obj.map_data.listing.default_sorting.inorder;
                    }
                    inorder = map_obj.map_data.listing.default_sorting.inorder;
                }

            }

            $.each(filters, function(index, options) {
                if (wpgmp_listing_filter.display_category_filter === true && index == "category") {
                    content += '<select data-filter="dropdown" data-name="category" name="place_' + index + '">';
                    content += '<option value="">' + wpgmp_local.select_category + '</option>';
                    options = map_obj.sort_object_by_keyvalue(options, by, type, inorder);
                    $.each(options, function(name, value) {
                        content += "<option value='" + value.id + "'>" + value.name + "</option>";
                    });
                    content += '</select>';
                } else if (wpgmp_listing_filter.display_taxonomies_filter === true) {
                    if (wpgmp_alltfilter === null)
                        return false;

                    if (wpgmp_alltfilter.indexOf(index) > -1) {
                        content += '<select data-filter="dropdown" data-name="category" name="place_' + index + '">';
                        content += '<option value="">Select ' + index + '</option>';
                        $.each(options, function(name, value) {
                            content += "<option value='" + value + "'>" + name + "</option>";
                        });
                        content += '</select>';
                    }
                }

            });

            return content;
        },

        update_places_listing: function() {

            var map_obj = this;

            if (map_obj.per_page_value > 0)
                map_obj.per_page_value = map_obj.per_page_value;
            else
                map_obj.per_page_value = map_obj.map_data.listing.pagination.listing_per_page;
            $(map_obj.container).find(".location_pagination" + map_obj.map_data.map_property.map_id).pagination(map_obj.show_places.length, {
                callback: map_obj.display_places_listing,
                map_data: map_obj,
                items_per_page: map_obj.per_page_value,
                prev_text: wpgmp_local.prev,
                next_text: wpgmp_local.next
            });

        },

        display_filters_listing: function() {

            if (this.map_data.listing) {

                var hide_locations = this.map_data.listing.hide_locations;

                var wpgmpgl = this.map_data.listing.list_grid;

                if (hide_locations != true) {

                    var content = '<div class="wpgmp_listing_container">';


                    content += "<div class='wpgmp_categories wpgmp_print_listing " + wpgmpgl + "' data-container='wpgmp-listing-" + $(this.element).attr("id") + "'></div>";


                    content += "</div>";

                    $(this.map_data.listing.listing_container).html(content);

                }

                this.filter_position = this.map_data.listing.filters_position;
                this.filter_content = this.display_filters();

                if( $(this.container).find(".wpgmp_filter_wrappers").length > 0)
                $(this.container).find(".wpgmp_filter_wrappers").html(this.filter_content);

            }

        },

        display_filters: function() {

            var hide_locations = this.map_data.listing.hide_locations;
            var listing_header = this.map_data.listing.listing_header;
            var content = '';
            if(listing_header != undefined) {
                content += '<div class="wpgmp_before_listing">' + this.map_data.listing.listing_header + '</div>';
            }
            if (this.map_data.listing.display_search_form === true) {
                var autosuggest_class = '';

                if (this.map_data.listing.search_field_autosuggest === true) {
                    autosuggest_class = "wpgmp_auto_suggest";
                }
                
                content += '<div class="wpgmp_listing_header"><div class="wpgmp_search_form"><input type="text" rel="24" data-input="wpgmp-search-text" name="wpgmp_search_input" class="wpgmp_search_input ' + autosuggest_class + '" placeholder="' + wpgmp_local.search_placeholder + '"></div></div>';
                
            }

            content += '<div class="categories_filter">' + this.create_filters() + '<div data-container="wpgmp-filters-container"></div>';

            if (hide_locations != true)
                content += this.create_sorting() + '';

            if (hide_locations != true && this.map_data.listing.display_location_per_page_filter === true) {
                content += ' ' + this.create_perpage_option() + ' ';
            }

            content += ' ' + this.create_radius() + ' ';

            if (hide_locations != true && this.map_data.listing.display_print_option === true) {
                content += ' ' + wpgmp_local.img_print;
            }

            if (hide_locations != true && this.map_data.listing.display_grid_option === true) {
                content += ' ' + wpgmp_local.img_grid + wpgmp_local.img_list;
            }

            if (typeof this.map_data.map_options.display_reset_button != "undefined" &&
                this.map_data.map_options.display_reset_button === true) {
                content += '<div class="categories_filter_reset"><input type="button" class="categories_filter_reset_btn" name="categories_filter_reset_btn" value="' + this.map_data.map_options.map_reset_button_text + '"></div>';
            }

            content += '</div>';

            return content;
        },

        find_direction: function(options) {

            var map_obj = this;
            var request = {
                origin: options.start,
                destination: options.end,
                optimizeWaypoints: true,
                travelMode: eval("google.maps.TravelMode." + options.mode),
                unitSystem: eval("google.maps.UnitSystem." + options.unit)
            };
            map_obj.directionsService = new google.maps.DirectionsService();
            map_obj.directionsService.route(request, function(response, status) {

                if (status == google.maps.DirectionsStatus.OK) {

                    map_obj.directionsDisplay.setMap(map_obj.map);
                    map_obj.directionsDisplay.setDirections(response);
                    if (false == map_obj.map_data.map_tabs.direction_tab.suppress_markers || map_obj.map_data.map_tabs.direction_tab.suppress_markers == undefined) {
                        map_obj.directionsDisplay.setPanel(options.direction_panel);
                        $(options.direction_panel).css('display', 'block');
                    }

                }
            });
        },

        display_places_listing: function(page_index, jq) {

            var content = '';

            var map_obj = this;
            var category_selector_dropdown = $('select[name = "place_category"]');
            var items_per_page = 10;
            if (map_obj.items_per_page)
                items_per_page = map_obj.items_per_page;
            else
                items_per_page = map_obj.map_data.map_data.listing.pagination.listing_per_page;

            var data_source = map_obj.map_data.show_places;
            
            var listing_container = map_obj.map_data.map_data.listing.listing_container;


            var listing_placeholder = map_obj.map_data.map_data.listing.listing_placeholder;

            var max_elem = Math.min((page_index + 1) * items_per_page, data_source.length);
            var link = '';
            var onclick_action = '';
            if (max_elem > 0) {
                for (var i = page_index * items_per_page; i < max_elem; i++) {
                    var place = data_source[i];
                    var temp_listing_placeholder = listing_placeholder;
                    if (place.marker.visible === true) {
                        if (place.id) {
                            if (place.location.onclick_action == "marker") {

                                link = '<a href="javascript:void(0);" class="place_title" data-zoom="' + place.location.zoom + '"  data-marker="' + place.id + '" >' + place.title + '</a>';
                                onclick_action = 'href="javascript:void(0);" data-zoom="' + place.location.zoom + '"  data-marker="' + place.id + '"';
                            } else if (place.location.onclick_action == "post") {
                                link = '<a href="' + place.location.redirect_permalink + '" target="_blank">' + place.title + '</a>';
                                onclick_action = 'href="' + place.location.redirect_permalink + '" target="_blank"';
                            } else if (place.location.onclick_action == "custom_link") {
                                link = '<a href="' + place.location.redirect_custom_link + '" target="_blank">' + place.title + '</a>';
                                onclick_action = 'href="' + place.location.redirect_custom_link + '" target="_blank"';
                            } else {
                                link = '<a href="javascript:void(0);" class="place_title" data-zoom="' + place.location.zoom + '"  data-marker="' + place.id + '" >' + place.title + '</a>';
                                onclick_action = 'href="javascript:void(0);" data-zoom="' + place.location.zoom + '"  data-marker="' + place.id + '"';
                            }
                        }

                        var image = [];
                        var category_name = [];
                        var wpgmp_arr = {};

                        if (place.categories) {
                            for (var c = 0; c < place.categories.length; c++) {
                                if (place.categories[c].icon !== '') {
                                    image.push("<img title='" + place.categories[c].name + "' alt='" + place.categories[c].name + "' src='" + place.categories[c].icon + "' />");
                                }
                                if (place.categories[c].type == 'category' && place.categories[c].name != '') {
                                    category_name.push(place.categories[c].name);
                                }

                                if (place.categories[c].type != 'category') {
                                    if (typeof place.categories[c].name == "undefined")
                                        continue;

                                    if (place.categories[c].name)
                                        var sep = ',';

                                    if (typeof wpgmp_arr[place.categories[c].type] == "undefined")
                                        wpgmp_arr[place.categories[c].type] = '';

                                    wpgmp_arr[place.categories[c].type] += place.categories[c].name + sep;
                                }
                            }
                        }

                        var marker_image = '';

                        if (place.source == 'post') {
                            marker_image = place.location.extra_fields.post_featured_image;
                        } else {
                            marker_image = place.location.marker_image;
                        }
                        
                        var replaceData = {
                            "{marker_id}": place.id,
                            "{marker_title}": link,
                            "{marker_address}": place.address,
                            "{marker_latitude}": place.location.lat,
                            "{marker_longitude}": place.location.lng,
                            "{marker_city}": place.location.city,
                            "{marker_state}": place.location.state,
                            "{marker_country}": place.location.country,
                            "{marker_postal_code}": place.location.postal_code,
                            "{marker_zoom}": place.location.zoom,
                            "{marker_icon}": image,
                            "{marker_category}": category_name.join(", "),
                            "{marker_message}": place.content,
                            "{marker_image}": marker_image,
                            "{marker_featured_image}": marker_image,
                            "{wpgmp_listing_html}": place.listing_hook,
                            "{onclick_action}": onclick_action

                        };

                        //Add extra fields of locations
                        if (typeof place.location.extra_fields != 'undefined') {
                            for (var extra in place.location.extra_fields) {
                                if (!place.location.extra_fields[extra]) {
                                    replaceData['{' + extra + '}'] = "<div class='wpgmp_empty'>wpgmp_empty</div>";

                                } else {
                                    replaceData['{' + extra + '}'] = place.location.extra_fields[extra];
                                }
                            }
                        }

                        for (var prop in replaceData) {
                            if (replaceData[prop] == undefined || replaceData[prop] == 'undefined')
                                replaceData[prop] = '';
                        }

                        if (wpgmp_arr) {
                            for (var n in wpgmp_arr) {
                                replaceData["{" + n + "}"] = wpgmp_remove_last_comma(wpgmp_arr[n]);
                            }
                        }

                        var wpgmp_remove_last_comma = function(strng) {
                            var n = strng.lastIndexOf(",");
                            var a = strng.substring(0, n)
                            return a;
                        }

                        temp_listing_placeholder = temp_listing_placeholder.replace(/{[^{}]+}/g, function(match) {
                            if (match in replaceData) {
                                return (replaceData[match]);
                            } else {
                                return ("");
                            }
                        });

                        content += temp_listing_placeholder;
                    }
                }
            } else {
                content = "<div class='wpgmp_no_locations'>" + wpgmp_local.wpgmp_location_no_results + "</div>";
            }

            for (var j = 0; j < data_source.length; j++) {

                place = data_source[j];
                if (typeof place.categories.length !== undefined && place.categories.length > 1) {

                    if (category_selector_dropdown.val() !== '') {
                        if (place.categories) {
                            for (var c = 0; c < place.categories.length; c++) {
                                if (category_selector_dropdown.val() == place.categories[c].id) {
                                    place.marker.setIcon(place.categories[c].icon);
                                    break;
                                }
                            }
                        }
                    } else {
                        place.marker.setIcon(place.location.icon);
                        
                    }
                }


            }

            content += '<div id="wpgmp_pagination"></div>';

            content = '<div class="fc-' + map_obj.map_data.map_data.listing.list_item_skin.type + '-' + map_obj.map_data.map_data.listing.list_item_skin.name + ' fc-wait"><div data-page="2" class="fc-component-6" data-layout="' + map_obj.map_data.map_data.listing.list_item_skin.name + '" >' + content + '</div></div>';

            $(listing_container).find(".wpgmp_categories").html(content);
            $(listing_container).find(".wpgmp_extra_field:contains('wpgmp_empty')").remove();
            $(listing_container).find(".wpgmp_empty").prev().remove();
            $(listing_container).find(".wpgmp_empty").remove();

            try {
                var container = $(listing_container).find('.wpgmp_listing_grid');
                if (container) {

                    var msnry = $(container).data('masonry');
                    if (msnry) {
                        msnry.destroy();
                    }

                    var $grid = $(container).imagesLoaded(function() {
                        // init Masonry after all images have loaded
                        $grid.masonry({
                            itemSelector: '.wpgmp_listing_grid .wpgmp_locations',
                            columnWidth: '.wpgmp_listing_grid .wpgmp_locations',
                        });
                    });

                }

            } catch (err) {
                console.log(err);
            }

            return false;
        },

        open_infowindow: function(current_place) {
            var map_obj = this;
            $.each(this.map_data.places, function(key, place) {
                if (parseInt(place.id) == parseInt(current_place) && place.marker.visible === true) {
                    var on_event = map_obj.settings.infowindow_open_event;
                    google.maps.event.trigger(place.marker, on_event);
                }
            });
        },

        place_info: function(place_id) {

            var place_obj;

            $.each(this.places, function(index, place) {

                if (parseInt(place.id) == parseInt(place_id)) {
                    place_obj = place;
                }
            });

            return place_obj;
        },

        create_routes: function() {
            var map_obj = this;
            if (this.map_data.routes) {
                $.each(this.map_data.routes, function(index, routeobj) {

                    var directionsService = new google.maps.DirectionsService();

                    var route_polyline = {
                        strokeColor: routeobj.route_stroke_color,
                        strokeOpacity: routeobj.route_stroke_opacity,
                        strokeWeight: routeobj.route_stroke_weight,
                        clickable: routeobj.route_marker_draggable
                    };

                    var renderer_options = {
                        draggable: routeobj.route_marker_draggable,
                        suppressMarkers: true,
                        suppressInfoWindows: true,
                        preserveViewport: true,
                        polylineOptions: route_polyline,

                    };


                    var start = routeobj.start_location_data;
                    var end = routeobj.end_location_data;
                    var waypts = [];
                    if (typeof routeobj.way_points != 'undefined') {
                        $.each(routeobj.way_points, function(point_index, place) {
                            waypts.push({
                                location: place,
                                stopover: true
                            });
                        });
                    }

                    var request = {
                        origin: start,
                        destination: end,

                        waypoints: waypts,
                        optimizeWaypoints: routeobj.route_optimize_waypoints,

                        travelMode: eval("google.maps.TravelMode." + routeobj.route_travel_mode),
                        unitSystem: eval("google.maps.UnitSystem." + routeobj.route_unit_system),
                    };

                    directionsService.route(request, function(response, status) {

                        if (status == google.maps.DirectionsStatus.OK) {
                            var directionsDisplay = new google.maps.DirectionsRenderer(renderer_options);
                            directionsDisplay.setMap(map_obj.map);
                            directionsDisplay.setDirections(response);
                            directionsDisplay.setPanel($(".directions-panel-route" + routeobj.route_id + "").get(0));

                            if (typeof routeobj.route_id != 'undefined') {
                                map_obj.route_directions[routeobj.route_id] = directionsDisplay;
                            }
                        } else {
                            console.log("" + wpgmp_local.wpgmp_route_not_avilable + "");
                        }
                    });
                });
            }

        },

        enable_drawing: function() {
            var map_obj = this;
            map_obj.drawingmanager = new google.maps.drawing.DrawingManager({
                drawingMode: null,
                drawingControl: true,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: [

                        google.maps.drawing.OverlayType.CIRCLE, google.maps.drawing.OverlayType.POLYGON, google.maps.drawing.OverlayType.POLYLINE, google.maps.drawing.OverlayType.RECTANGLE

                    ]
                },

                circleOptions: {
                    fillColor: '#003dce',
                    strokeColor: '#003dce',
                    strokeWeight: '1',
                    strokeOpacity: '0.5',
                    zindex: 1,
                    fillOpacity: '0.5',
                    editable: false,
                    draggable: false
                },
                polygonOptions: {
                    fillColor: '#003dce',
                    strokeColor: '#003dce',
                    strokeWeight: '1',
                    strokeOpacity: '0.5',
                    zindex: 1,
                    fillOpacity: '0.5',
                    editable: false,
                    draggable: false
                },
                polylineOptions: {
                    fillColor: '#003dce',
                    strokeColor: '#003dce',
                    strokeWeight: '1',
                    strokeOpacity: '0.5',
                    zindex: 1,
                    fillOpacity: '0.5',
                    editable: false,
                    draggable: false
                },
                rectangleOptions: {
                    fillColor: '#003dce',
                    strokeColor: '#003dce',
                    strokeWeight: '1',
                    strokeOpacity: '0.5',
                    zindex: 1,
                    fillOpacity: '0.5',
                    editable: false,
                    draggable: false
                }
            });
            map_obj.drawingmanager.setMap(map_obj.map);
            map_obj.event_listener(map_obj.drawingmanager, 'circlecomplete', function(circle) {
                map_obj.wpgmp_circles.push(circle);
                map_obj.wpgmp_shape_complete(circle, 'circle');
            });
            map_obj.event_listener(map_obj.drawingmanager, 'polygoncomplete', function(polygon) {
                map_obj.wpgmp_polygons.push(polygon);
                map_obj.wpgmp_shape_complete(polygon, 'polygon');
            });
            map_obj.event_listener(map_obj.drawingmanager, 'polylinecomplete', function(polyline) {
                map_obj.wpgmp_polylines.push(polyline);
                map_obj.wpgmp_shape_complete(polyline, 'polyline');
            });
            map_obj.event_listener(map_obj.drawingmanager, 'rectanglecomplete', function(rectangle) {
                map_obj.wpgmp_rectangles.push(rectangle);
                map_obj.wpgmp_shape_complete(rectangle, 'rectangle');
            });
        },

        create_polygon: function() {

            var map_obj = this;

            $.each(this.map_data.shapes.shape.polygons, function(index, polygon) {
                var path = [];
                $.each(polygon.cordinates, function(ind, cordinate) {
                    var latlng = cordinate.split(',');
                    path.push(new google.maps.LatLng(latlng[0], latlng[1]));
                });

                polygon.reference = new google.maps.Polygon({
                    paths: path,
                    strokeColor: polygon.settings.stroke_color,
                    strokeOpacity: polygon.settings.stroke_opacity,
                    strokeWeight: polygon.settings.stroke_weight,
                    fillColor: polygon.settings.fill_color,
                    fillOpacity: polygon.settings.fill_opacity
                });
                if (typeof map_obj.map_data.shapes != 'undefined') {

                    if (map_obj.map_data.shapes.drawing_editable === true) {
                        map_obj.event_listener(polygon.reference, "click", function() {
                            map_obj.setSelection(polygon.reference);
                            map_obj.get_shapes_options(polygon.reference, 'polygon');
                        });


                    } else if (polygon.events !== undefined && (polygon.events.url !== '' || polygon.events.message !== '')) {

                        map_obj.event_listener(polygon.reference, "click", function() {

                            if ((polygon.events.url === '' || polygon.events.url === undefined) && polygon.events.message !== '') {
                                var bounds = new google.maps.LatLngBounds();
                                polygon.reference.getPath().forEach(function(element, index) {
                                    bounds.extend(element);
                                });
                                $.each(map_obj.places, function(key, place) {
                                    place.infowindow.close();
                                });
                                map_obj.opened_info.setPosition(bounds.getCenter());
                                if (map_obj.settings.map_infowindow_customisations === true)
                                    map_obj.opened_info.setContent('<div class="wpgmp_infowindow"><div class="wpgmp_iw_content">' + polygon.events.message + '</div></div>');
                                else
                                    map_obj.opened_info.setContent(polygon.events.message);
                                map_obj.opened_info.open(map_obj.map, this);

                            } else {
                                if (polygon.events.url !== 'undefined' && polygon.events.url !== undefined)
                                    window.location = polygon.events.url;
                            }

                        });

                    }
                }
                polygon.reference.setMap(map_obj.map);

                map_obj.wpgmp_polygons.push(polygon.reference);

                if (polygon.events == undefined) {
                    polygon.events = {};
                    polygon.events.url = '';
                    polygon.events.message = '';
                }

                map_obj.wpgmp_shape_events.push({
                    'shape': polygon.reference,
                    'url': polygon.events.url,
                    'message': polygon.events.message
                });
            });
        },

        create_polyline: function() {

            var map_obj = this;


            $.each(this.map_data.shapes.shape.polylines, function(index, polyline) {
                var path = [];

                if (typeof polyline.cordinates != 'undefined') {
                    $.each(polyline.cordinates, function(ind, cordinate) {
                        var latlng = cordinate.split(',');
                        path.push(new google.maps.LatLng(latlng[0], latlng[1]));
                    });

                    polyline.reference = new google.maps.Polyline({
                        path: path,
                        strokeColor: polyline.settings.stroke_color,
                        strokeOpacity: polyline.settings.stroke_opacity,
                        strokeWeight: polyline.settings.stroke_weight
                    });

                    if (typeof map_obj.map_data.shapes != 'undefined') {

                        if (map_obj.map_data.shapes.drawing_editable === true) {

                            map_obj.event_listener(polyline.reference, "click", function() {
                                map_obj.setSelection(polyline.reference);
                                map_obj.get_shapes_options(polyline.reference, 'polyline');
                            });


                        } else if (polyline.events !== undefined && (polyline.events.url !== '' || polyline.events.message !== '')) {

                            map_obj.event_listener(polyline.reference, "click", function() {

                                if ((polyline.events.url === '' || polyline.events.url === undefined) && polyline.events.message !== '') {
                                    var bounds = new google.maps.LatLngBounds();
                                    polyline.reference.getPath().forEach(function(element, index) {
                                        bounds.extend(element);
                                    });
                                    $.each(map_obj.places, function(key, place) {
                                        place.infowindow.close();
                                    });
                                    map_obj.opened_info.setPosition(bounds.getCenter());
                                    if (map_obj.settings.map_infowindow_customisations === true)
                                        map_obj.opened_info.setContent('<div class="wpgmp_infowindow"><div class="wpgmp_iw_content">' + polyline.events.message + '</div></div>');
                                    else
                                        map_obj.opened_info.setContent(polyline.events.message);
                                    map_obj.opened_info.open(map_obj.map, this);

                                } else if (polyline.events.url !== '') {
                                    if (polyline.events.url !== 'undefined' && polyline.events.url !== undefined)
                                        window.location = polyline.events.url;
                                }

                            });


                        }
                    }
                }
                if (typeof polyline.reference != 'undefined') {
                    polyline.reference.setMap(map_obj.map);
                    map_obj.wpgmp_polylines.push(polyline.reference);

                    if (polyline.events == undefined) {
                        polyline.events = {};
                        polyline.events.url = '';
                        polyline.events.message = '';
                    }

                    map_obj.wpgmp_shape_events.push({
                        'shape': polyline.reference,
                        'url': polyline.events.url,
                        'message': polyline.events.message
                    });
                }
            });
        },
        event_listener: function(obj, type, func) {
            google.maps.event.addListener(obj, type, func);
        },
        create_circle: function() {

            var map_obj = this;
            $.each(this.map_data.shapes.shape.circles, function(index, circle) {
                var path;
                $.each(circle.cordinates, function(ind, cordinate) {
                    var latlng = cordinate.split(',');
                    path = new google.maps.LatLng(latlng[0], latlng[1]);
                });

                circle.reference = new google.maps.Circle({
                    fillColor: circle.settings.fill_color,
                    fillOpacity: circle.settings.fill_opacity,
                    strokeColor: circle.settings.stroke_color,
                    strokeOpacity: circle.settings.stroke_opacity,
                    strokeWeight: circle.settings.stroke_weight,
                    center: path,
                    radius: parseInt(circle.settings.radius)
                });

                if (typeof map_obj.map_data.shapes != 'undefined') {

                    if (map_obj.map_data.shapes.drawing_editable === true) {
                        map_obj.event_listener(circle.reference, "click", function() {
                            map_obj.setSelection(circle.reference);
                            map_obj.get_shapes_options(circle.reference, 'circle');
                        });

                    } else if (circle.events !== undefined && (circle.events.url !== '' || circle.events.message !== '')) {
                        map_obj.event_listener(circle.reference, "click", function() {

                            if ((circle.events.url === '' || circle.events.url === undefined) && circle.events.message !== '') {
                                $.each(map_obj.places, function(key, place) {
                                    place.infowindow.close();
                                });
                                map_obj.opened_info.setPosition(circle.reference.getCenter());
                                if (map_obj.settings.map_infowindow_customisations === true)
                                    map_obj.opened_info.setContent('<div class="wpgmp_infowindow"><div class="wpgmp_iw_content">' + circle.events.message + '</div></div>');
                                else
                                    map_obj.opened_info.setContent(circle.events.message);
                                map_obj.opened_info.open(map_obj.map, this);

                            } else if (circle.events.url !== '') {
                                if (circle.events.url !== 'undefined' && circle.events.url !== undefined)
                                    window.location = circle.events.url;
                            }


                        });

                    }
                }


                circle.reference.setMap(map_obj.map);
                map_obj.wpgmp_circles.push(circle.reference);

                if (circle.events == undefined) {
                    circle.events = {};
                    circle.events.url = '';
                    circle.events.message = '';
                }

                map_obj.wpgmp_shape_events.push({
                    'shape': circle.reference,
                    'url': circle.events.url,
                    'message': circle.events.message
                });
            });
        },

        create_rectangle: function() {

            var map_obj = this;
            $.each(this.map_data.shapes.shape.rectangles, function(index, rectangle) {
                var left_latlng = rectangle.cordinates[0].split(',');
                var right_latlng = rectangle.cordinates[1].split(',');

                var path = new google.maps.LatLngBounds(new google.maps.LatLng(left_latlng[0], left_latlng[1]), new google.maps.LatLng(right_latlng[0], right_latlng[1]));

                rectangle.reference = new google.maps.Rectangle({
                    bounds: path,
                    fillColor: rectangle.settings.fill_color,
                    fillOpacity: rectangle.settings.fill_opacity,
                    strokeColor: rectangle.settings.stroke_color,
                    strokeOpacity: rectangle.settings.stroke_opacity,
                    strokeWeight: rectangle.settings.stroke_weight
                });

                if (typeof map_obj.map_data.shapes != 'undefined') {

                    if (map_obj.map_data.shapes.drawing_editable === true) {

                        map_obj.event_listener(rectangle.reference, "click", function() {

                            map_obj.setSelection(rectangle.reference);
                            map_obj.get_shapes_options(rectangle.reference, 'rectangle');

                        });

                    } else if (rectangle.events !== undefined && (rectangle.events.url !== '' || rectangle.events.message !== '')) {
                        map_obj.event_listener(rectangle.reference, "click", function() {

                            if ((rectangle.events.url === '' || rectangle.events.url === undefined) && rectangle.events.message !== '') {
                                $.each(map_obj.places, function(key, place) {
                                    place.infowindow.close();
                                });
                                map_obj.opened_info.setPosition(rectangle.reference.getBounds().getCenter());
                                if (map_obj.settings.map_infowindow_customisations === true)
                                    map_obj.opened_info.setContent('<div class="wpgmp_infowindow"><div class="wpgmp_iw_content">' + rectangle.events.message + '</div></div>');
                                else
                                    map_obj.opened_info.setContent(rectangle.events.message);
                                map_obj.opened_info.open(map_obj.map, this);
                            } else if (rectangle.events.url !== '') {
                                if (rectangle.events.url !== 'undefined' && rectangle.events.url !== undefined)
                                    window.location = rectangle.events.url;
                            }

                        });

                    }
                }

                rectangle.reference.setMap(map_obj.map);
                map_obj.wpgmp_rectangles.push(rectangle.reference);

                if (rectangle.events == undefined) {
                    rectangle.events = {};
                    rectangle.events.url = '';
                    rectangle.events.message = '';
                }

                map_obj.wpgmp_shape_events.push({
                    'shape': rectangle.reference,
                    'url': rectangle.events.url,
                    'message': rectangle.events.message,
                });
            });
        },

        get_shapes_options: function(shape, type) {
            $(".hiderow").show();
            $("input[name='shape_fill_color']").val(shape.fillColor);
            $("input[name='shape_fill_color']").parent().parent().find('.wp-color-result').css('background-color', shape.fillColor);
            $("select[name='shape_fill_opacity']").val(shape.fillOpacity);
            $("input[name='shape_stroke_color']").val(shape.strokeColor);
            $("input[name='shape_stroke_color']").parent().parent().find('.wp-color-result').css('background-color', shape.strokeColor);
            $("select[name='shape_stroke_opacity']").val(shape.strokeOpacity);
            $("select[name='shape_stroke_weight']").val(shape.strokeWeight);
            $("textarea[name='shape_path']").parent().hide();
            $("input[name='shape_radius']").parent().hide();
            $("input[name='shape_center']").parent().hide();
            $("input[name='shape_northeast']").parent().hide();
            $("input[name='shape_southwest']").parent().hide();

            var all_shape_events = this.wpgmp_shape_events;
            $.each(all_shape_events, function(i, shape_event) {

                if (shape_event.shape == shape) {
                    $("input[name='shape_click_url']").val(shape_event.url);
                    $("textarea[name='shape_click_message']").val(shape_event.message);
                }
            });
            if (type == 'circle') {
                $("input[name='shape_radius']").parent().show();
                $("input[name='shape_radius']").val(shape.getRadius());
                $("input[name='shape_center']").parent().show();
                $("input[name='shape_center']").val(shape.getCenter().lat() + ',' + shape.getCenter().lng());

            } else if (type == 'rectangle') {
                $("input[name='shape_northeast']").parent().show();
                $("input[name='shape_northeast']").val(shape.getBounds().getNorthEast().lat() + ',' + shape.getBounds().getNorthEast().lng());
                $("input[name='shape_southwest']").parent().show();
                $("input[name='shape_southwest']").val(shape.getBounds().getSouthWest().lat() + ',' + shape.getBounds().getSouthWest().lng());

            } else {
                var polygon_cordinate = [];

                var cordinates = shape.getPath();

                cordinates.forEach(function(latlng, index) {

                    var latlngin = [latlng.lat(), latlng.lng()];

                    if (latlng.lat() !== "" && latlng.lng() !== "")
                        polygon_cordinate.push(latlngin);

                });
                $("textarea[name='shape_path']").parent().show();
                $("textarea[name='shape_path']").val(polygon_cordinate.join(' '));
            }
        },

        set_shapes_options: function(shape) {
            var polyOptions2 = {
                fillColor: $("input[name='shape_fill_color']").val(),
                fillOpacity: $("select[name='shape_fill_opacity']").val(),
                strokeColor: $("input[name='shape_stroke_color']").val(),
                strokeOpacity: $("select[name='shape_stroke_opacity']").val(),
                strokeWeight: $("select[name='shape_stroke_weight']").val(),
            };
            shape.setOptions(polyOptions2);
            var all_shape_events = this.wpgmp_shape_events;
            $.each(all_shape_events, function(i, shape_event) {
                if (shape_event.shape == shape) {
                    shape_event.url = $("input[name='shape_click_url']").val();
                    shape_event.message = $("textarea[name='shape_click_message']").val();
                }
            });

        },

        wpgmp_save_shapes: function(allcordinate) {
            $("input[name='shapes_values']").val(allcordinate.join("|"));
        },

        wpgmp_shape_complete: function(shape, type) {
            var map_obj = this;
            map_obj.setSelection(shape);
            map_obj.drawingmanager.setDrawingMode(null);
            if (typeof map_obj.map_data.shapes != 'undefined') {

                if (map_obj.map_data.shapes.drawing_editable === true) {

                    map_obj.event_listener(shape, 'click', function() {
                        map_obj.setSelection(shape);
                        map_obj.get_shapes_options(shape, type);
                    });

                    map_obj.wpgmp_shape_events.push({
                        'shape': shape,
                        'url': '',
                        'message': ''
                    });
                }

            }

        },

        wpgmp_save_polylines: function() {

            var all_polylines = [];
            var map_obj = this;
            var wpgmp_polylines = map_obj.wpgmp_polylines;
            var all_shape_events = map_obj.wpgmp_shape_events;

            for (var i = 0; i < wpgmp_polylines.length; i++) {

                var polyline_cordinate = [];

                var cordinates = wpgmp_polylines[i].getPath();

                var settings = wpgmp_polylines[i].strokeWeight + "," + wpgmp_polylines[i].strokeOpacity + "," + wpgmp_polylines[i].strokeColor;
                var events = "";
                $.each(all_shape_events, function(j, shape_event) {
                    if (shape_event.shape == wpgmp_polylines[i]) {
                        events = shape_event.url + "***" + shape_event.message;
                    }
                });

                cordinates.forEach(function(latlng, index) {

                        var latlngin = [latlng.lat(), latlng.lng()];
                        polyline_cordinate.push(latlngin);

                    }

                );

                all_polylines.push(polyline_cordinate.join("----") + "..." + settings + "..." + events);

            }
            return all_polylines;


        },


        wpgmp_save_polygons: function() {

            var all_polygons = [];
            var map_obj = this;
            var wpgmp_polygons = map_obj.wpgmp_polygons;
            var all_shape_events = map_obj.wpgmp_shape_events;

            for (var i = 0; i < wpgmp_polygons.length; i++) {

                var polygon_cordinate = [];

                var cordinates = wpgmp_polygons[i].getPath();

                var settings = wpgmp_polygons[i].strokeWeight + "," + wpgmp_polygons[i].strokeOpacity + "," + wpgmp_polygons[i].strokeColor + "," + wpgmp_polygons[i].fillColor + "," + wpgmp_polygons[i].fillOpacity;

                var events = "";
                $.each(all_shape_events, function(j, shape_event) {
                    if (shape_event.shape == wpgmp_polygons[i]) {
                        events = shape_event.url + "***" + shape_event.message;
                    }
                });

                cordinates.forEach(function(latlng, index) {

                        var latlngin = [latlng.lat(), latlng.lng()];

                        if (latlng.lat() !== "" && latlng.lng() !== "")
                            polygon_cordinate.push(latlngin);

                    }

                );

                all_polygons.push(polygon_cordinate.join("----") + "..." + settings + "..." + events);

            }

            return all_polygons;

        },


        wpgmp_save_circles: function() {

            var all_circles = [];
            var map_obj = this;
            var wpgmp_circles = map_obj.wpgmp_circles;
            var all_shape_events = map_obj.wpgmp_shape_events;

            for (var i = 0; i < wpgmp_circles.length; i++) {

                var circle_cordinate = [];

                var latlng = wpgmp_circles[i].getCenter();

                var settings = wpgmp_circles[i].strokeWeight + "," + wpgmp_circles[i].strokeOpacity + "," + wpgmp_circles[i].strokeColor + "," + wpgmp_circles[i].fillColor + "," + wpgmp_circles[i].fillOpacity + "," + wpgmp_circles[i].getRadius();

                var events = "";
                $.each(all_shape_events, function(j, shape_event) {
                    if (shape_event.shape == wpgmp_circles[i]) {
                        events = shape_event.url + "***" + shape_event.message;
                    }
                });


                var latlngin = [latlng.lat(), latlng.lng()];

                if (latlng.lat() !== "" && latlng.lng() !== "")
                    circle_cordinate.push(latlngin);

                all_circles.push(circle_cordinate.join("----") + "..." + settings + "..." + events);

            }

            return all_circles;

        },

        wpgmp_save_rectangles: function() {

            var all_rectangles = [];
            var map_obj = this;
            var wpgmp_rectangles = map_obj.wpgmp_rectangles;
            var all_shape_events = map_obj.wpgmp_shape_events;
            for (var i = 0; i < wpgmp_rectangles.length; i++) {

                var rectangle_cordinate = [];



                var settings = wpgmp_rectangles[i].strokeWeight + "," + wpgmp_rectangles[i].strokeOpacity + "," + wpgmp_rectangles[i].strokeColor + "," + wpgmp_rectangles[i].fillColor + "," + wpgmp_rectangles[i].fillOpacity;

                var events = "";
                $.each(all_shape_events, function(j, shape_event) {
                    if (shape_event.shape == wpgmp_rectangles[i]) {
                        events = shape_event.url + "***" + shape_event.message;
                    }
                });

                var latlng = wpgmp_rectangles[i].getBounds().getSouthWest();



                var latlngin = [latlng.lat(), latlng.lng()];

                if (latlng.lat() !== "" && latlng.lng() !== "")
                    rectangle_cordinate.push(latlngin);



                latlng = wpgmp_rectangles[i].getBounds().getNorthEast();


                var latlngin = [latlng.lat(), latlng.lng()];

                if (latlng.lat() !== "" && latlng.lng() !== "")
                    rectangle_cordinate.push(latlngin);

                all_rectangles.push(rectangle_cordinate.join("----") + "..." + settings + "..." + events);

            }

            return all_rectangles;

        },

        set_kml_layer: function() {

            var map_obj = this.map;

            $.each(this.map_data.kml_layer.kml_layers_links, function(index, link) {

                var kmlLayerOptions = {
                    url: link,
                    map: map_obj,
                    preserveViewport: true

                };

                new google.maps.KmlLayer(kmlLayerOptions);
            });
        },

        set_fusion_layer: function() {

            var fusionlayer = new google.maps.FusionTablesLayer({
                query: {
                    select: this.map_data.fusion_layer.fusion_table_select,
                    from: this.map_data.fusion_layer.fusion_table_from
                },
                heatmap: {
                    enabled: this.map_data.fusion_layer.fusion_heat_map
                },
                styles: [{
                    markerOptions: {
                        iconName: this.map_data.fusion_layer.fusion_icon_name
                    }
                }]
            });

            fusionlayer.setMap(this.map);
        },

        set_marker_cluster: function() {
            var map_obj = this;
            var markers = [];
            var clusterStyles = [{
                textColor: 'black',
                url: map_obj.map_data.marker_cluster.icon,
                height: 32,
                width: 33
            }];
            $.each(this.places, function(index, place) {
                if (place.marker.visible == true) {
                    markers.push(place.marker);
                }

            });

            if (map_obj.map_data.marker_cluster.apply_style === true) {
                if (!map_obj.markerClusterer) {
                    map_obj.markerClusterer = new MarkerClusterer(map_obj.map, {}, {
                        gridSize: parseInt(map_obj.map_data.marker_cluster.grid),
                        maxZoom: parseInt(map_obj.map_data.marker_cluster.max_zoom),
                        styles: clusterStyles

                    });
                }

                map_obj.markerClusterer.clearMarkers();
                map_obj.markerClusterer.addMarkers(markers);

                google.maps.event.addListener(map_obj.markerClusterer, 'mouseover', function(c) {

                    c.clusterIcon_.div_.firstChild.src = map_obj.map_data.marker_cluster.hover_icon
                });

                google.maps.event.addListener(map_obj.markerClusterer, 'mouseout', function(c) {
                    c.clusterIcon_.div_.firstChild.src = map_obj.map_data.marker_cluster.icon
                });

            } else {
                if (!map_obj.markerClusterer) {
                    map_obj.markerClusterer = new MarkerClusterer(map_obj.map, {}, {
                        gridSize: parseInt(map_obj.map_data.marker_cluster.grid),
                        maxZoom: parseInt(map_obj.map_data.marker_cluster.max_zoom),
                        imagePath: map_obj.map_data.marker_cluster.image_path,
                    });
                }

                map_obj.markerClusterer.clearMarkers();
                map_obj.markerClusterer.addMarkers(markers);


            }

        },

        set_panning_control: function() {

            var panning_data = this.map_data.panning_control;
            var panning_map_obj = this.map;
            var map_obj = this;

            var strictBounds = new google.maps.LatLngBounds(
                new google.maps.LatLng(panning_data.from_latitude, panning_data.from_longitude),
                new google.maps.LatLng(panning_data.to_latitude, panning_data.to_longitude)
            );

            google.maps.event.addListener(panning_map_obj, "dragend", function() {

                if (strictBounds.contains(panning_map_obj.getCenter())) return;

                var c = panning_map_obj.getCenter(),
                    x = c.lng(),
                    y = c.lat(),
                    maxX = strictBounds.getNorthEast().lng(),
                    maxY = strictBounds.getNorthEast().lat(),
                    minX = strictBounds.getSouthWest().lng(),
                    minY = strictBounds.getSouthWest().lat();

                if (x < minX) x = minX;
                if (x > maxX) x = maxX;
                if (y < minY) y = minY;
                if (y > maxY) y = maxY;

                panning_map_obj.setCenter(new google.maps.LatLng(y, x));
            });

            google.maps.event.addListener(panning_map_obj, "zoom_changed", function() {
                if (panning_map_obj.getZoom() < panning_data.zoom_level) {
                    panning_map_obj.setZoom(parseInt(map_obj.settings.zoom));
                }
            });
        },

        set_visual_refresh: function() {

            google.maps.visualRefresh = true;
        },

        set_45_imagery: function() {
            //this.map.setTilt(45);
        },

        set_overlay: function() {

            this.map.overlayMapTypes.insertAt(0, new overlay_generator(new google.maps.Size(this.map_data.overlay_setting.width, this.map_data.overlay_setting.height), this.map_data.overlay_setting));
        },

        set_bicyle_layer: function() {

            var bikeLayer = new google.maps.BicyclingLayer();
            bikeLayer.setMap(this.map);
        },

        set_traffic_layer: function() {

            var traffic_layer = new google.maps.TrafficLayer();
            traffic_layer.setMap(this.map);
        },

        set_panoramic_layer: function() {

            var panoramic_layer = new google.maps.panoramio.PanoramioLayer();
            panoramic_layer.setMap(this.map);
        },

        set_transit_layer: function() {

            var transit_layer = new google.maps.TransitLayer();
            transit_layer.setMap(this.map);
        },


        set_weather_layer: function() {

            var weatherLayer = new google.maps.weather.WeatherLayer({
                windSpeedUnit: eval('google.maps.weather.WindSpeedUnit.' + this.map_data.weather_layer.wind_unit),
                temperatureUnits: eval('google.maps.weather.TemperatureUnit.' + this.map_data.weather_layer.temperature_unit)
            });

            weatherLayer.setMap(this.map);
            var cloudLayer = new google.maps.weather.CloudLayer();
            cloudLayer.setMap(this.map);
        },

        set_streetview: function(latlng) {

            var panoOptions = {
                position: latlng,
                addressControlOptions: {
                    position: google.maps.ControlPosition.BOTTOM_CENTER
                },
                linksControl: this.map_data.street_view.links_control,
                panControl: this.map_data.street_view.street_view_pan_control,
                zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.SMALL
                },
                enableCloseButton: this.map_data.street_view.street_view_close_button
            };
            if (this.map_data.street_view.pov_heading && this.map_data.street_view.pov_pitch) {
                panoOptions['pov'] = {
                    heading: parseInt(this.map_data.street_view.pov_heading),
                    pitch: parseInt(this.map_data.street_view.pov_pitch)
                };
            }
            var panorama = new google.maps.StreetViewPanorama(this.element, panoOptions);
        },

        create_amenities_markers: function(args) {

            var map_obj = this;
            if (map_obj.map_data.default_amenities) {


                var gm_dim = args.dimension;
                var gm_radius = args.radius;
                var default_amenities = map_obj.map_data.default_amenities.amenities;

                var divide_by = 1.60934;
                map_obj.amenity_infowindow = map_obj.infowindow_marker;
                var service;
                if (gm_dim == 'miles') {
                    divide_by = 1.60934;
                } else {
                    divide_by = 1;
                }

                if(gm_radius != undefined){


                    var circle_radius_meters = parseInt(gm_radius) * divide_by * 1000;
                    // Now draw a circle.
                    if (default_amenities) {

                        var place_types = [];
                        $.each(default_amenities, function(index, amenity) {
                            place_types.push(amenity);
                        });

                        for (var i = 0; i < place_types.length; i++) {
                            if(map_obj !== 'undefined' && map_obj.map_data.enableAmenitiesListing != undefined && map_obj.map_data.display_amenities_by != undefined && map_obj.map_data.display_amenities_by == 'by_nearest') {

                                var request = {
                                    location: args.location,
                                    types: [place_types[i]],
                                    rankBy: google.maps.places.RankBy.DISTANCE,
                                };
                                
                            }else {

                                var request = {
                                    location: args.location,
                                    radius: circle_radius_meters,
                                    types: [place_types[i]],
                                };
                            }
                            service = new google.maps.places.PlacesService(map_obj.map);
                            
                            service.nearbySearch(request, function(results, status, page) {
                                if (status == google.maps.places.PlacesServiceStatus.OK) {
                                    for (var i = 0; i < results.length; i++) {
                                        map_obj.createMarker(results[i]);
                                    }
                                    if(map_obj !== 'undefined' && map_obj.map_data.enableAmenitiesListing != undefined && map_obj.map_data.display_amenities_by != undefined && map_obj.map_data.display_amenities_by == 'by_radius'){
                                        if (page && page.hasNextPage) {
                                            page.nextPage();
                                        }
                                    }
                                }
                            });
                        }

                    }
                }

                /** End **/
            }

        },

        map_loaded: function() {

            var map_obj = this;

            var gmap = map_obj.map;

            google.maps.event.addListenerOnce(gmap, 'idle', function() {

                var center = gmap.getCenter();
                google.maps.event.trigger(gmap, 'resize');
                gmap.setCenter(center);

            });

            if (map_obj.settings.center_by_nearest === true) {
                map_obj.center_by_nearest();
            }
            if (map_obj.settings.close_infowindow_on_map_click === true) {
                google.maps.event.addListener(gmap, "click", function(event) {
                    $.each(map_obj.places, function(key, place) {
                        place.infowindow.close();
                        place.marker.setAnimation(null);
                    });
                });
            }

            if (map_obj.map_data.default_amenities) {

                var args = {'dimension': map_obj.map_data.default_amenities.dimension, 'radius': map_obj.map_data.default_amenities.radius, 'location': map_obj.map.getCenter() };
                map_obj.create_amenities_markers(args);
            }

            //for infowindow skins
            google.maps.event.addListener(map_obj.infobox, 'domready', function() {
                var wpgmp_iwOuter = $(map_obj.container).find('.infoBox');

                if (wpgmp_iwOuter.find('.fc-infowindow-default').length == 0 && wpgmp_iwOuter.find('.fc-item-default').length == 0 && wpgmp_iwOuter.find('.wpgmp_infowindow').length > 0) {
                   
                    wpgmp_iwOuter.find('.wpgmp_infowindow').prepend('<div class="infowindow-close"></div>');
                    $('.infowindow-close').click(function(e) {
                        e.preventDefault();
                        $.each(map_obj.places, function(key, place) {
                            place.infowindow.close();
                            place.marker.setAnimation(null);
                        });
                    });

                    //accordian
                    $(wpgmp_iwOuter).on('click', ".fc-accordion-tab", function() {
                        if ($(this).hasClass('active')) {
                            $(this).removeClass('active');
                            var acc_child = $(this).next().removeClass('active');
                        } else {
                            $(".fc-accordion-tab").removeClass('active');
                            $(".fc-accordion dd").removeClass('active');
                            $(this).addClass('active');
                            var acc_child = $(this).next().addClass('active');
                        }
                    });
                    if (wpgmp_iwOuter.find('.fc-infowindow-fano').length == 0 && wpgmp_iwOuter.find('.fc-item-fano').length == 0) {
                        wpgmp_iwOuter.addClass('infoBoxTail');
                    } else {
                        wpgmp_iwOuter.removeClass('infoBoxTail');
                    }

                }
            });


            if (map_obj.settings.map_infowindow_customisations === true) {
                google.maps.event.addListener(map_obj.infowindow_marker, 'domready', function() {

                    var wpgmp_iwOuter = $(map_obj.container).find('.gm-style-iw');
                    var infowindow_width = map_obj.settings.infowindow_width;
                    var wpgmp_iwCloseBtn = wpgmp_iwOuter.next();
                    wpgmp_iwCloseBtn.css('display', 'none');

                    var wpgmp_iwBackground = wpgmp_iwOuter.prev();

                    wpgmp_iwBackground.children(':nth-child(2)').css({
                        'display': 'none'
                    });

                    wpgmp_iwBackground.children(':nth-child(3)').css({
                        'background-color': '#000;',
                    });

                    wpgmp_iwBackground.children(':nth-child(4)').css({
                        'display': 'none'
                    });
                    var height = wpgmp_iwOuter.outerHeight();
                    wpgmp_iwBackground.children(':nth-child(3)').css({
                        'top': (height + 14) + 'px'
                    });
                    wpgmp_iwBackground.children(':nth-child(1)').css({
                        'top': (height + 6) + 'px'
                    });
                    wpgmp_iwBackground.children(':nth-child(3)').find('div').children().css({
                        'box-shadow': map_obj.settings.infowindow_border_color + ' 0px 1px 6px',
                        'border': '1px solid ' + map_obj.settings.infowindow_border_color,
                        'border-top': '',
                        'z-index': '1',
                        'background-color': map_obj.settings.infowindow_bg_color
                    });
                    wpgmp_iwOuter.on('click', '.infowindow-close', function(event) {
                        $.each(map_obj.places, function(key, place) {
                            place.infowindow.close();
                            place.marker.setAnimation(null);
                        });
                    });
                });
            }

        },
        resize_map: function() {
            var map_obj = this;
            var gmap = map_obj.map;
            var zoom = gmap.getZoom();
            var center = gmap.getCenter();
            google.maps.event.trigger(this.map, 'resize');
            gmap.setZoom(zoom);
            gmap.setCenter(center);
        },
        responsive_map: function() {

            var map_obj = this;

            var gmap = map_obj.map;

            google.maps.event.addDomListener(window, "resize", function() {

                var zoom = gmap.getZoom();
                var center = gmap.getCenter();

                google.maps.event.trigger(gmap, "resize");
                gmap.setZoom(zoom);
                gmap.setCenter(center);
                gmap.getBounds();

                if (map_obj.map_data.marker_cluster) {
                    map_obj.set_marker_cluster();
                }

            });

        },
        show_search_control: function() {
            var map_obj = this;
            var input = $(map_obj.container).find('[data-input="map-search-control"]')[0];

            if (input !== undefined) {
                var searchBox = new google.maps.places.Autocomplete(input);

                var marker = new google.maps.Marker({
                        icon:  map_obj.settings.marker_default_icon,
                        draggable: false,
                        map: map_obj.map,
                    });
                    map_obj.marker_bind(marker);

                if (wpgmp_local.wpgmp_country_specific && wpgmp_local.wpgmp_country_specific == true) {
                    searchBox.setComponentRestrictions({
                        'country': wpgmp_local.wpgmp_countries
                    });
                }

                map_obj.map.controls[eval("google.maps.ControlPosition." + map_obj.settings.search_control_position)].push(input);

                // Bias the SearchBox results towards current map's viewport.
                searchBox.bindTo('bounds', map_obj.map);

                google.maps.event.addListener(searchBox, 'place_changed', function() {
                    var place = searchBox.getPlace();
                    if(map_obj.settings.zoom_level_after_search != undefined) {
                        map_obj.map.setZoom(parseInt(map_obj.settings.zoom_level_after_search));
                    } else {
                        map_obj.map.setZoom(parseInt(10));
                    }
                    map_obj.map.setCenter(place.geometry.location);
                    marker.setPosition(place.geometry.location);
                    marker.setVisible(true);
                });
            }
        },
        fit_bounds: function() {
            var map_obj = this;
            var places = map_obj.map_data.places;
            var bounds = new google.maps.LatLngBounds();

            if (places !== undefined) {
                places.forEach(function(place) {

                    if (place.location.lat && place.location.lng) {
                        bounds.extend(new google.maps.LatLng(
                            parseFloat(place.location.lat),
                            parseFloat(place.location.lng)
                        ));
                    }

                });
            }
            map_obj.map.fitBounds(bounds);
        },
        create_markers: function() {

            var map_obj = this;
            var places = map_obj.map_data.places;
            var temp_listing_placeholder;
            var replaceData;
            var remove_keys = [];

            $.each(places, function(key, place) {

                if (place.location.lat && place.location.lng) {
                    if (typeof place.categories == 'undefined') {
                        place.categories = {};
                    }

                    if (typeof place.location.icon == 'undefined') {
                        place.location.icon = map_obj.settings.marker_default_icon;
                    }

                    place.marker = new google.maps.Marker({
                        position: new google.maps.LatLng(
                            parseFloat(place.location.lat),
                            parseFloat(place.location.lng)
                        ),
                        icon: place.location.icon,
                        url: place.url,
                        draggable: place.location.draggable,
                        map: map_obj.map,
                        clickable: place.location.infowindow_disable,
                    });

                    if (map_obj.settings.infowindow_drop_animation === true) {
                        place.marker.setAnimation(google.maps.Animation.DROP);
                    }

                    if (place.location.animation == "DROP") {
                        place.marker.setAnimation(google.maps.Animation.DROP);
                    }
                    if (place.location.animation == "BOUNCE1") {
                        place.marker.setAnimation(google.maps.Animation.BOUNCE);
                    }

                    if (map_obj.settings.infowindow_filter_only === true) {
                        place.marker.visible = false;
                        place.marker.setVisible(false);
                    }


                    // bind event to marker
                    if (map_obj.map_data.page == 'edit_location')
                        map_obj.marker_bind(place.marker);
                    var location_categories = [];
                    if (typeof place.categories != 'undefined') {
                        for (var cat in place.categories) {
                            location_categories.push(place.categories[cat].name);
                        }
                    }
                    var content = '';
                    // replace infowindow content.
                    var marker_image = '';

                    if (place.source == 'post') {
                        marker_image = place.location.extra_fields.post_featured_image;
                    } else {
                        marker_image = place.location.marker_image;
                    }

                    var temp_listing_placeholder = '';
                    var post_info_class = 'fc-infowindow-';
                    if (place.source == 'post') {
                        temp_listing_placeholder = map_obj.settings.infowindow_geotags_setting;
                        post_info_class = 'wpgmp_infowindow_post fc-item-' + map_obj.settings.infowindow_post_skin.name;
                    } else {
                        temp_listing_placeholder = map_obj.settings.infowindow_setting;
                        if (map_obj.map_data.page != 'edit_location' && map_obj.settings.infowindow_skin)
                            post_info_class = 'fc-infowindow-' + map_obj.settings.infowindow_skin.name;
                    }

                    if (typeof temp_listing_placeholder == 'undefined') {
                        temp_listing_placeholder = place.content;
                    }
                    
                    var replaceData = {
                        "{marker_id}": place.id,
                        "{marker_title}": place.title,
                        "{marker_address}": place.address,
                        "{marker_latitude}": place.location.lat,
                        "{marker_longitude}": place.location.lng,
                        "{marker_city}": place.location.city,
                        "{marker_state}": place.location.state,
                        "{marker_country}": place.location.country,
                        "{marker_postal_code}": place.location.postal_code,
                        "{marker_zoom}": place.location.zoom,
                        "{marker_icon}": place.location.icon,
                        "{marker_category}": location_categories.join(', '),
                        "{marker_message}": place.content,
                        "{marker_image}": marker_image,
                        "{get_directions_link}": 'http://www.google.com/maps/place/'+parseFloat(place.location.lat)+','+parseFloat(place.location.lng),
                    };

                    //Add extra fields of locations
                    if (typeof place.location.extra_fields != 'undefined') {
                        for (var extra in place.location.extra_fields) {
                            if (!place.location.extra_fields[extra]) {
                                replaceData['{' + extra + '}'] = "<div class='wpgmp_empty'>wpgmp_empty</div>";
                            } else {
                                replaceData['{' + extra + '}'] = place.location.extra_fields[extra];
                            }
                        }
                    }

                    for (var prop in replaceData) {
                        if (replaceData[prop] == undefined || replaceData[prop] == 'undefined')
                            replaceData[prop] = '';
                    }

                    if (temp_listing_placeholder) {

                        temp_listing_placeholder = temp_listing_placeholder.replace(/{[^{}]+}/g, function(match) {
                            if (match in replaceData) {
                                return (replaceData[match]);
                            } else {
                                return ("");
                            }
                        });

                        var temp_string = temp_listing_placeholder;
                        var temp_object = $('<div/>').html(temp_string);
                        $(temp_object).find(".wpgmp_extra_field:contains('wpgmp_empty')").remove();
                        $(temp_object).find(".wpgmp_empty").prev().remove();
                        $(temp_object).find(".wpgmp_empty").remove();

                        content = $(temp_object).prop('outerHTML').toString();

                    }



                    if (content === "") {
                        if (map_obj.settings.map_infowindow_customisations === true && map_obj.settings.show_infowindow_header === true)
                            content = '<div class="wpgmp_infowindow ' + post_info_class + '"><div class="wpgmp_iw_head"><div class="wpgmp_iw_head_content">' + place.title + '</div></div><div class="wpgmp_iw_content">' + place.content + '</div></div>';
                        else
                            content = '<div class="wpgmp_infowindow ' + post_info_class + '"><div class="wpgmp_iw_content">' + place.content + '</div></div>';
                    } else {
                        if (map_obj.settings.map_infowindow_customisations === true && map_obj.settings.show_infowindow_header === true)
                            content = '<div class="wpgmp_infowindow ' + post_info_class + '"><div class="wpgmp_iw_head"><div class="wpgmp_iw_head_content">' + place.title + '</div></div><div class="wpgmp_iw_content">' + content + '</div></div>';
                        else
                            content = '<div class="wpgmp_infowindow ' + post_info_class + '"><div class="wpgmp_iw_content">' + content + '</div></div>';

                    }
                    place.infowindow_data = content;
                    place.infowindow = map_obj.infowindow_marker;

                    if (place.location.infowindow_default_open === true) {
                        map_obj.openInfoWindow(place);
                    } else if (map_obj.settings.default_infowindow_open === true) {
                        map_obj.openInfoWindow(place);
                    }
                    var on_event = map_obj.settings.infowindow_open_event;
                    var bounce_on_event = map_obj.settings.infowindow_bounce_animation;
                    map_obj.event_listener(place.marker, on_event, function() {
                        $.each(map_obj.places, function(key, prev_place) {
                            prev_place.infowindow.close();
                            prev_place.marker.setAnimation(null);
                        });
                        map_obj.openInfoWindow(place);
                        if (bounce_on_event == 'click') {
                            map_obj.toggle_bounce(place.marker);
                        }
                    });


                    if (on_event == 'mouseover') {

                        map_obj.event_listener(place.marker, 'click', function() {

                            if (typeof map_obj.settings.infowindow_click_change_zoom != 'undefined' && map_obj.settings.infowindow_click_change_zoom > 0) {
                                map_obj.map.setZoom(map_obj.settings.infowindow_click_change_zoom);
                            }

                            if (typeof map_obj.settings.infowindow_click_change_center != 'undefined' && map_obj.settings.infowindow_click_change_center == true) {
                                map_obj.map.setCenter(place.marker.getPosition());
                            }




                        });



                    }





                    if (bounce_on_event == 'mouseover' && on_event != 'mouseover') {
                        map_obj.event_listener(place.marker, 'mouseover', function() {
                            place.marker.setAnimation(google.maps.Animation.BOUNCE);
                        });

                        map_obj.event_listener(place.marker, 'mouseout', function() {
                            place.marker.setAnimation(null);
                        });
                    }

                    if (bounce_on_event != '') {
                        google.maps.event.addListener(place.infowindow, 'closeclick', function() {
                            place.marker.setAnimation(null);
                        });
                    }

                    map_obj.places.push(place);
                } else {
                    remove_keys.push(key);
                }
            });
            $.each(remove_keys, function(index, value) {
                places.splice(value, 1);
            });

        },
        toggle_bounce: function(marker) {
            if (marker.getAnimation() !== null) {
                marker.setAnimation(null);
            } else {
                marker.setAnimation(google.maps.Animation.BOUNCE);
            }
        },
        display_markers: function() {

            var map_obj = this;
            map_obj.show_places = [];
            map_obj.categories = [];
            var categories = {};
            for (var i = 0; i < map_obj.places.length; i++) {
                map_obj.places[i].marker.setMap(map_obj.map);
                if (map_obj.places[i].marker.visible === true) {
                    map_obj.show_places.push(this.places[i]);
                }

                if (typeof map_obj.places[i].categories != 'undefined') {
                    $.each(map_obj.places[i].categories, function(index, category) {

                        if (typeof categories[category.name] == 'undefined') {
                            categories[category.name] = category;
                        }
                    });
                }
            }

            this.categories = categories;
        },
        show_center_circle: function() {
            var map_obj = this;
            if (map_obj.settings.center_circle_radius == '') {
                map_obj.settings.center_circle_radius = 5;
            }

            map_obj.set_center_circle = new google.maps.Circle({
                map: map_obj.map,
                center: map_obj.map.getCenter(),
                fillColor: map_obj.settings.center_circle_fillcolor,
                fillOpacity: map_obj.settings.center_circle_fillopacity,
                strokeColor: map_obj.settings.center_circle_strokecolor,
                strokeOpacity: map_obj.settings.center_circle_strokeopacity,
                strokeWeight: map_obj.settings.center_circle_strokeweight,
                radius: parseInt(map_obj.settings.center_circle_radius) * 1000
            });

        },
        show_center_marker: function() {

            var map_obj = this;
            var clickable = false;
            if (map_obj.settings.center_marker_infowindow != '') {
                clickable = true;
            }

            map_obj.map_center_marker = new google.maps.Marker({
                position: map_obj.map.getCenter(),
                title: map_obj.settings.center_marker_infowindow,
                map: map_obj.map,
                icon: map_obj.settings.center_marker_icon,
                clickable: clickable
            });
            if (typeof map_obj.map_center_info == 'undefined') {
                map_obj.map_center_info = map_obj.infowindow_marker;
            }
            if (map_obj.settings.center_marker_infowindow != '') {
                google.maps.event.addListener(map_obj.map_center_marker, 'click', function() {
                    map_obj.map_center_info.setPosition(map_obj.map.getCenter());
                    if (map_obj.settings.map_infowindow_customisations === true)
                        map_obj.map_center_info.setContent('<div class="wpgmp_infowindow"><div class="wpgmp_iw_content">' + map_obj.settings.center_marker_infowindow + '</div></div>');
                    else
                        map_obj.map_center_info.setContent(map_obj.settings.center_marker_infowindow);
                    map_obj.map_center_info.open(map_obj.map, this);

                });
            }
        },
        center_by_nearest: function() {
            var map_obj = this;
            this.get_current_location(function(user_position) {

                if (!map_obj.user_location_marker) {
                    map_obj.user_location_marker = new google.maps.Marker({
                        position: user_position,
                        title: wpgmp_local.center_location_message,
                        map: map_obj.map,
                        icon: map_obj.settings.marker_default_icon
                    });

                }
                map_obj.user_location_marker.setVisible(true);
                if (typeof map_obj.map_center_info == 'undefined') {
                    map_obj.map_center_info = map_obj.infowindow_marker;
                }
                if (map_obj.settings.center_marker_infowindow != '') {
                    google.maps.event.addListener(map_obj.user_location_marker, 'click', function() {
                        map_obj.map_center_info.setPosition(user_position);
                        if (map_obj.settings.map_infowindow_customisations === true)
                            map_obj.map_center_info.setContent('<div class="wpgmp_infowindow"><div class="wpgmp_iw_content">' + map_obj.settings.center_marker_infowindow + '</div></div>');
                        else
                            map_obj.map_center_info.setContent(map_obj.settings.center_marker_infowindow);
                        map_obj.map_center_info.open(map_obj.map, this);

                    });
                }
                map_obj.map.setCenter(user_position);
                if (map_obj.settings.show_center_circle === true) {
                    map_obj.show_center_circle();
                }

                if (map_obj.map_data.listing && map_obj.map_data.listing.apply_default_radius == true) {

                    map_obj.search_area = user_position;
                }



            });
        },

        get_current_location: function(success_func, error_func) {

            var map = this;

            if (typeof map.user_location == 'undefined') {

                navigator.geolocation.getCurrentPosition(function(position) {

                    map.user_location = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

                    if (success_func)
                        success_func(map.user_location);

                }, function(ErrorPosition) {
                    console.log(ErrorPosition);
                    if (error_func)
                        error_func(ErrorPosition);

                }, {
                    enableHighAccuracy: true,
                    timeout: 50000,
                    maximumAge: 0
                });
            } else {
                if (success_func)
                    success_func(map.user_location);
            }
        },

        openInfoWindow: function(place) {
            var map_obj = this;
            var skin = 'default';
            if (place.source == 'post') {
                skin = map_obj.settings.infowindow_post_skin.name;
            } else if (map_obj.map_data.page != 'edit_location' && map_obj.settings.infowindow_skin) {
                skin = map_obj.settings.infowindow_skin.name;
            }
            if (skin != 'default') {
                var infoboxText = document.createElement("div");
                infoboxText.className = 'wpgmp_infobox'
                var infoboxOptions = {
                    content: infoboxText,
                    disableAutoPan: false,
                    alignBottom: true,
                    maxWidth: 0,
                    pixelOffset: (skin == 'fano') ? new google.maps.Size(-150, -40) : new google.maps.Size(-150, -55),
                    zIndex: null,
                    boxStyle: {
                        width: "300px"
                    },
                    closeBoxMargin: "0",
                    closeBoxURL: "",
                    infoBoxClearance: new google.maps.Size(25, 25),
                    isHidden: false,
                    pane: "floatPane",
                    enableEventPropagation: false,
                };
                
                place.infowindow = map_obj.infobox;
                place.infowindow.setOptions(infoboxOptions);
                infoboxText.innerHTML = place.infowindow_data;
            } else {
                place.infowindow = map_obj.infowindow_marker;
                place.infowindow.setContent(place.infowindow_data);
            }

            if (place.location.onclick_action == "post") {
                if (place.location.open_new_tab == 'yes')
                    window.open(place.location.redirect_permalink, '_blank');
                else
                    window.open(place.location.redirect_permalink, '_self');
            } else if (place.location.onclick_action == "custom_link") {
                if (place.location.open_new_tab == 'yes')
                    window.open(place.location.redirect_custom_link, '_blank');
                else
                    window.open(place.location.redirect_custom_link, '_self');
            } else {

                place.infowindow.open(this.map, place.marker);

                if (typeof map_obj.settings.infowindow_open_event != 'undefined' && map_obj.settings.infowindow_open_event == 'click' && typeof map_obj.settings.infowindow_click_change_center != 'undefined' && map_obj.settings.infowindow_click_change_center == true) {
                    map_obj.map.setCenter(place.marker.getPosition());
                }
                if (typeof map_obj.settings.infowindow_open_event != 'undefined' && map_obj.settings.infowindow_open_event == 'click' && typeof map_obj.settings.infowindow_click_change_zoom != 'undefined' && map_obj.settings.infowindow_click_change_zoom > 0) {
                    map_obj.map.setZoom(map_obj.settings.infowindow_click_change_zoom);
                }
                if (this.map_data.map_tabs && this.map_data.map_tabs.direction_tab && this.map_data.map_tabs.direction_tab.dir_tab === true) {
                    $(this.container).find('.start_point').val(place.address);
                }
            }
            $(map_obj.container).find(".wpgmp_extra_field:contains('wpgmp_empty')").remove();
            $(map_obj.container).find(".wpgmp_empty").prev().remove();
            $(map_obj.container).find(".wpgmp_empty").remove();

        },
    };

    $.fn.maps = function(options, places) {

        this.each(function() {

            if (!$.data(this, "wpgmp_maps")) {
                $.data(this, "wpgmp_maps", new GoogleMaps(this, options, places));
            }

        });
        // chain jQuery functions
        return this;
    };

}(jQuery, window, document));
