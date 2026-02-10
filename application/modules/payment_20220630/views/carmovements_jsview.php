<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 4/25/2019
 * Time: 10:35 AM
 */
$MODULE_NAME = 'payment';
$TB_NAME = 'carMovements';
$status_url = base_url("$MODULE_NAME/$TB_NAME/status");
?>

//<script>

    var map;


    $('#modal_movment_det').click(function (e) {
        e.preventDefault();
        $('#myModal').modal();
    });

    $('#update_form_tracking').click(function (e) {
        e.preventDefault();
        restTrackingData();

        $('#dp_driver_name').val(-1);
        $('#txt_movment_date').val('');
        $('#showTrackingBtn').prop('disabled', false);
    });

    $('#txt_car_id_name').click(function (e) {
        _showReport('<?= base_url('payment/cars/public_select_car') ?>/' + $(this).attr('id') + '/');
    });


    $('#txt_driver_name').click(function (e) {
        _showReport('<?= base_url('payment/carMovements/public_select_driver') ?>/' + $(this).attr('id') + '/');
    });


    $('button[data-action="submit"]').click(function (e) {
        e.preventDefault();

        if (confirm('هل تريد الحفظ  ؟!')) {

            $(this).attr('disabled', 'disabled');

            var form = $(this).closest('form');
            var isCreateDetails = ~form.attr('action').indexOf('create_details');

            if (isCreateDetails && ($('#txt_predefine_start_gps').val() == '' || $('#txt_predefine_finished_gps').val() == '')) {
                alert('فضلا , قم بادخال احداثيات الحركة من خلال الخريطة');
                return;
            }

            ajax_insert_update(form, function (data) {


                if (parseInt(data) >= 1) {
                    success_msg('رسالة', 'تم حفظ البيانات بنجاح ..');

                    if (isCreateDetails) {

                        $('#myModal').modal('hide');
                        get_data('<?= base_url('payment/carMovements/public_list_car_movements_det/')?>', {id: $('#txt_car_mov_id').val()}, function (data) {
                            $('#movements_details_container').html(data);
                            initMap();
                        }, 'html');

                    } else {

                        if (~form.attr('action').indexOf('edit')) {

                            get_to_link(parseInt(data));
                        } else {
                            get_to_link('get/' + parseInt(data));

                        }
                        //this for master insert or update
                    }

                } else {
                    danger_msg('تحذير..', data);
                }
            }, 'html');
        }
        setTimeout(function () {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
    });


    $('#modal_movment_det').click(function (e) {
        e.preventDefault();

        $('#myModal').modal();
        $('#txt_purpose_type').val('');
        $('#txt_expected_leave_time').val('');
        $('#txt_expected_arrival_time').val('');
        $('#txt_notes_').val('');
        $('#txt_predefine_start_gps').val('');
        $('#txt_predefine_finished_gps').val('');
        $('#txt_to_address').val('');
        $('#txt_from_address').val('');
        //clearForm('#myModal');

    });


    function get_car_movement_det(id) {
        get_data('<?= base_url('payment/carMovements/public_get_car_movements_det') ?>', {id: id}, function (data) {
            if (data != null || data != '') {
                var json = jQuery.parseJSON(data);
                $('#myModal').modal();
                $('#txt_car_mov_id').val(json.CAR_MOV_ID);
                $('#txt_purpose_type').val(json.PURPOSE_TYPE);
                $('#txt_expected_leave_time').val(json.EXPECTED_LEAVE_TIME);
                $('#txt_expected_arrival_time').val(json.EXPECTED_ARRIVAL_TIME);
                $('#txt_predefine_start_gps').val(json.PREDEFINE_START_GPS);
                $('#txt_predefine_finished_gps').val(json.PREDEFINE_FINISHED_GPS);
                $('#txt_notes').val(json.NOTES);
                $('#txt_start_gps_location').val(json.START_GPS_LOCATION);
                $('#txt_finished_gps_location').val(json.FINISHED_GPS_LOCATION);
            }

        }, 'html');
    }

    initMap();

    function initMap() {

        if (typeof L == "undefined") {
            return
        }

        var gps = '';
        var lat = 0, lng = 0;

        if (gps != null && gps != '') {

            lat = gps.split("|")[0];
            lng = gps.split("|")[1];

        }

        map = L.map('movements_map').setView([lat > 0 ? lat : 31.378056329684597, lng > 0 ? lng : 34.337843605224634], 13);
        map.addControl(new L.Control.Fullscreen());
        /*L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
            maxZoom: 18,
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
            '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
            'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            id: 'mapbox.streets'
        }).addTo(map);*/

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        if (typeof   preDefinedGPS != "undefined") {
            var prefDefineGPSJsonObj = jQuery.parseJSON(preDefinedGPS);

            $.each(prefDefineGPSJsonObj, function (i) {

                var gpsSplit = this.split('~');
                var start = gpsSplit[0];
                var end = gpsSplit[1];
                var sTitle = gpsSplit[2];
                var eTitle = gpsSplit[3];

                if (start != null && start != '') {

                    addMarker(map, start, 'green', sTitle);
                }

                if (end != null && end != '') {
                    addMarker(map, end, 'red', eTitle);
                }

                drawRouting(map, start, end);

            });
        }

    }

    function drawRouting(map, from, to) {

        var lat = 0, lng = 0, tLat = 0, tLng = 0;

        if (from != null && from != '') {

            lat = from.split("|")[0];
            lng = from.split("|")[1];

        }

        if (to != null && to != '') {

            tLat = to.split("|")[0];
            tLng = to.split("|")[1];

        }

        if (lat == 0 || lng == 0 || tLat == 0 || tLng == 0) return;

        L.Routing.control({
            waypoints: [
                L.latLng(lat, lng),
                L.latLng(tLat, tLng)
            ],
            router: L.Routing.google(),
            createMarker: function() { return null; }
        }).addTo(map);
    }

    function addMarker(map, location, color = 'red', title = '') {

        var lat = 0, lng = 0;

        if (location != null && location != '') {

            lat = location.split("|")[0];
            lng = location.split("|")[1];

        }

        if (lat == 0 || lng == 0) return;

        var LeafIcon = L.Icon.extend({
            options: {
                shadowUrl: '<?= base_url('assets/leaflet/leaflet/dist/images/marker-shadow.png') ?>',

            }
        });

        var redIcon = new LeafIcon({iconUrl: '<?= base_url('/assets/leaflet/leaflet/dist/images/marker-icon-red.png') ?>'});

        if (color == 'red') {
            L.marker([lat, lng], {icon: redIcon})
                .addTo(map)
                .bindPopup(title)
                .openPopup();
        } else {
            L.marker([lat, lng])
                .addTo(map)
                .bindPopup(title)
                .openPopup();
        }
    }


    function values_search(add_page) {
        var values =

            {
                page: 1, car_id: $('#txt_car_id').val(),
                driver_id: $('#txt_driver_id').val(), the_date: $('#txt_the_date').val(),
                movment_type: $('#txt_movment_type').val()
            };
        if (add_page == 0)
            delete values.page;
        return values;
    }

    function do_search() {
        var values = values_search(1);
        get_data('<?= base_url('payment/carMovements/get_page')?>', values, function (data) {
            $('#container').html(data);
        }, 'html');
    }

    function randomColor() {
        var randomColor = '#' + ('000000' + Math.floor(Math.random() * 16777215).toString(16)).slice(-6);
        return randomColor;
    }


    function changeStatus_(type, val) {

        get_data('<?= base_url('payment/CarMovements/changeStatus')?>', {id: val}, function (data) {

            if (data != '0') {
                success_msg('رسالة', 'تم الالغاء بنجاح');
                reload_Page();
            }

        });


    }

    //</script>
