<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 4/25/2019
 * Time: 10:35 AM
 */
$MODULE_NAME = 'payment';
$TB_NAME = 'Carmovements';
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

    $('#select_car_id').click(function (e) {
        _showReport('<?= base_url('payment/cars/public_select_car') ?>/' + $('#txt_car_id_name').attr('id') + '/');
    });


    $('#select_driver_name').click(function (e) {
        _showReport('<?= base_url('payment/Carmovements/public_select_driver') ?>/' + $('#txt_driver_name').attr('id') + '/');
    });

    function Send_sms(){

        var leave_time = $('#txt_expected_leave_time').val();
        var arrival_time = $('#txt_expected_arrival_time').val();
        var from_address = $('#txt_from_address').val();
        var to_address = $('#txt_to_address').val();
        var date = $('#txt_the_date').val();
        var emp_name = $('#h_text_emp_name').val();
        var mobile_no_company = $('#h_mobile_no_company').val();
        var mobile_no_rented = $('#h_mobile_no_rented').val();
        var car_type = $('#h_car_type').val();
        var mobile = '0592409847';
        var message = 'الرجاء التوجه من '+ from_address +' الى '+ to_address + ' من الساعة ' + leave_time + ' الى ' + arrival_time + ' بتاريخ ' + date + ' مع الموظف ' + emp_name  ;



        if ( car_type == 1 ){

            $.ajax({
                url: 'https://im-server.gedco.ps:8005/apis/v1/SendSms?mobile='+mobile_no_company+'&message='+message+'&user=GEDCO-Public-services&password=3242967&sender=GEDCO-PUB-S',
                type: "post",
            });

        }

        else if( car_type == 2 ){
            $.ajax({
                url: 'https://im-server.gedco.ps:8005/apis/v1/SendSms?mobile='+mobile_no_rented+'&message='+message+'&user=GEDCO-Public-services&password=3242967&sender=GEDCO-PUB-S',
                type: "post",
            });

        }


    }

    $('#update_mov_det').click(function (e) {
        e.preventDefault();

        if (confirm('هل تريد الحفظ  ؟!')) {



            var form = $(this).closest('form');
            var isCreateDetails = ~form.attr('action').indexOf('create_details');

            if (isCreateDetails && ($('#txt_predefine_start_gps').val() == '' || $('#txt_predefine_finished_gps').val() == '')) {
                alert('فضلا , قم بادخال احداثيات الحركة من خلال الخريطة');
                return;
            }

            if (isCreateDetails && ($('#txt_expected_leave_time').val() > $('#txt_expected_arrival_time').val())) {
                alert('يجب ان تكون ساعة الوصول بعد ساعة المغادرة');
                return;
            }

            ajax_insert_update(form, function (data) {

                if (parseInt(data) >= 1) {

                    success_msg('رسالة', 'تم حفظ البيانات بنجاح ..');
                    if (isCreateDetails) {

                        $('#myModal').modal('hide');
                        setTimeout(function(){
                        get_to_link(window.location.href);
                        },1000);

                    } else {

                        if (~form.attr('action').indexOf('edit')) {

                            get_to_link(parseInt(data));
                        } else {
                            get_to_link('get/' + parseInt(data));

                        }

                    }

                } else {
                    danger_msg('تحذير..', data);
                }
            }, 'html');
        }
        setTimeout(function () {
            $('#update_mov_det').removeAttr('disabled');
        }, 3000);
    });


    $('#add_mov_det').click(function (e) {
        e.preventDefault();

        if (confirm('هل تريد الحفظ  ؟!')) {



            var form = $(this).closest('form');
            var isCreateDetails = ~form.attr('action').indexOf('create_details');

            if (isCreateDetails && ($('#txt_predefine_start_gps').val() == '' || $('#txt_predefine_finished_gps').val() == '')) {
                alert('فضلا , قم بادخال احداثيات الحركة من خلال الخريطة');
                return;
            }

            if (isCreateDetails && ($('#txt_expected_leave_time').val() > $('#txt_expected_arrival_time').val())) {
                alert('يجب ان تكون ساعة الوصول بعد ساعة المغادرة');
                return;
            }

            ajax_insert_update(form, function (data) {


                if (parseInt(data) >= 1) {

                    Send_sms();
                    success_msg('رسالة', 'تم حفظ البيانات بنجاح ..');
                    if (isCreateDetails) {

                        $('#myModal').modal('hide');
                        setTimeout(function(){
                            get_to_link(window.location.href);
                        },1000);
/*
                        $('#myModal').modal('hide');
                            get_data('<?//= base_url('payment/Carmovements/public_list_car_movements_det/')?>', {id: $('#txt_car_mov_id').val()}, function (data) {
                                $('#movements_details_container').html(data);
                                initMap();
                        }, 'html');
*/
                    } else {

                        if (~form.attr('action').indexOf('edit')) {

                            get_to_link(parseInt(data));
                        } else {
                            get_to_link('get/' + parseInt(data));

                        }
                    }

                } else {
                    danger_msg('تحذير..', data);
                }
            }, 'html');
        }
        setTimeout(function () {
            $('#add_mov_det').removeAttr('disabled');
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

    });



    $('#move_location').click(function (e) {
        e.preventDefault();

        var tr= $(this).closest("tr");
        var leave_time = tr.attr("data-leave-time");
        var arrival_time = tr.attr("data-arrival-time");
        var from_address = tr.attr("data-from-address");
        var address_1 = tr.attr("data-address-1");
        var start_gps = tr.attr("data-start-gps");
        var GPS_1 = tr.attr("data-GPS-1");

        $('#myModal').modal();
        $('#txt_purpose_type').val('');
        $('#txt_expected_leave_time').val(leave_time);
        $('#txt_expected_arrival_time').val(arrival_time);
        $('#txt_notes_').val('');
        $('#txt_predefine_start_gps').val(start_gps);
        $('#txt_predefine_finished_gps').val(GPS_1);
        $('#txt_from_address').val(from_address);
        $('#txt_to_address').val(address_1);

    });


    $('#move_location_1').click(function (e) {
        e.preventDefault();

        var tr= $(this).closest("tr");
        var address_1 = tr.attr("data-address-1");
        var address_2 = tr.attr("data-address-2");
        var GPS_1 = tr.attr("data-GPS-1");
        var GPS_2 = tr.attr("data-GPS-2");


        $('#myModal').modal();
        $('#txt_purpose_type').val('');
        $('#txt_expected_leave_time').val('');
        $('#txt_expected_arrival_time').val('');
        $('#txt_notes_').val('');
        $('#txt_predefine_start_gps').val(GPS_1);
        $('#txt_predefine_finished_gps').val(GPS_2);
        $('#txt_from_address').val(address_1);
        $('#txt_to_address').val(address_2);

    });


    $('#move_location_2').click(function (e) {
        e.preventDefault();

        var tr= $(this).closest("tr");
        var address_2 = tr.attr("data-address-2");
        var address_3 = tr.attr("data-address-3");
        var GPS_2 = tr.attr("data-GPS-2");
        var GPS_3 = tr.attr("data-GPS-3");

        $('#myModal').modal();
        $('#txt_purpose_type').val('');
        $('#txt_expected_leave_time').val('');
        $('#txt_expected_arrival_time').val('');
        $('#txt_notes_').val('');
        $('#txt_predefine_start_gps').val(GPS_2);
        $('#txt_predefine_finished_gps').val(GPS_3);
        $('#txt_from_address').val(address_2);
        $('#txt_to_address').val(address_3);

    });


    $('#move_location_3').click(function (e) {
        e.preventDefault();

        var tr= $(this).closest("tr");
        var address_3 = tr.attr("data-address-3");
        var address_4 = tr.attr("data-address-4");
        var GPS_3 = tr.attr("data-GPS-3");
        var GPS_4 = tr.attr("data-GPS-4");

        $('#myModal').modal();
        $('#txt_purpose_type').val('');
        $('#txt_expected_leave_time').val('');
        $('#txt_expected_arrival_time').val('');
        $('#txt_notes_').val('');
        $('#txt_predefine_start_gps').val(GPS_3);
        $('#txt_predefine_finished_gps').val(GPS_4);
        $('#txt_from_address').val(address_3);
        $('#txt_to_address').val(address_4);

    });


    $('#move_location_4').click(function (e) {
        e.preventDefault();

        var tr= $(this).closest("tr");
        var address_4 = tr.attr("data-address-4");
        var address_5 = tr.attr("data-address-5");
        var GPS_4 = tr.attr("data-GPS-4");
        var GPS_5 = tr.attr("data-GPS-5");

        $('#myModal').modal();
        $('#txt_purpose_type').val('');
        $('#txt_expected_leave_time').val('');
        $('#txt_expected_arrival_time').val('');
        $('#txt_notes_').val('');
        $('#txt_predefine_start_gps').val(GPS_4);
        $('#txt_predefine_finished_gps').val(GPS_5);
        $('#txt_from_address').val(address_4);
        $('#txt_to_address').val(address_5);

    });

    function distance_calculator(){

        var tr= $(this).closest("tr");
        var start_location = tr.attr("data-start-location");
        var finished_location = tr.attr("data-finished-location");


        var string_start = start_location;
        var string_finish = finished_location;

        var start_gps  = string_start.split("|");
        var finish_gps = string_finish.split("|");

        var latLngA = new google.maps.LatLng(start_gps[0],start_gps[1]);
        var latLngB = new google.maps.LatLng(finish_gps[0], finish_gps[1]);
        var distance = google.maps.geometry.spherical.computeDistanceBetween(latLngA, latLngB);
        var result = (distance.toFixed(0)/1000);

        alert(result);
    }



    function get_car_movement_det(id) {
        get_data('<?= base_url('payment/Carmovements/public_get_car_movements_det') ?>', {id: id}, function (data) {
            if (data != null || data != '') {
                var json = jQuery.parseJSON(data);

                if (json.STATUS != 0 && json.STATUS < 4 ){

                $('#myModal').modal();
                $('#txt_car_mov_id').val(json.CAR_MOV_ID);
                $('#txt_purpose_type').val(json.PURPOSE_TYPE);
                $('#txt_expected_leave_time').val(json.EXPECTED_LEAVE_TIME_T);
                $('#txt_expected_arrival_time').val(json.EXPECTED_ARRIVAL_TIME_T);
                $('#txt_predefine_start_gps').val(json.PREDEFINE_START_GPS);
                $('#txt_predefine_finished_gps').val(json.PREDEFINE_FINISHED_GPS);
                $('#txt_notes').val(json.NOTES);
                $('#txt_start_gps_location').val(json.START_GPS_LOCATION);
                $('#txt_finished_gps_location').val(json.FINISHED_GPS_LOCATION);
                $('#txt_from_address').val(json.FROM_ADDRESS);
                $('#txt_to_address').val(json.TO_ADDRESS);

                }
                 else {
                    info_msg('ملاحظة','العملية منتهية');
                }
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
                driver_id: $('#txt_driver_id').val(),
                the_date: $('#txt_the_date').val(),
                to_the_date: $('#to_txt_the_date').val(),
                movment_type: $('#txt_movment_type').val(),
                order_no: $('#txt_order_no').val(),
                emp_name: $('#txt_emp_name').val(),
                movment_status: $('#txt_movment_status').val(),
                car_type: $('#txt_car_type').val(),
                emp_no: $('#dp_emp_no').val(),
                branches: $('#dp_branches').val()
            };
        if (add_page == 0)
            delete values.page;
        return values;
    }

    function do_search() {
        var values = values_search(1);
        get_data('<?= base_url('payment/Carmovements/get_page')?>', values, function (data) {
            $('#container').html(data);
        }, 'html');
    }

    function randomColor() {
        var randomColor = '#' + ('000000' + Math.floor(Math.random() * 16777215).toString(16)).slice(-6);
        return randomColor;
    }


    function changeStatus_(type, val) {

        get_data('<?= base_url('payment/Carmovements/changeStatus')?>', {id: val}, function (data) {

            if (data != '0') {
                success_msg('رسالة', 'تم الالغاء بنجاح');
                reload_Page();
            }

        });


    }

    function changeStatus_move_det_(type, val) {

        get_data('<?= base_url('payment/Carmovements/changeStatus_move_det')?>', {id: val}, function (data) {

            if (data != '0') {
                success_msg('رسالة', 'تم الالغاء بنجاح');
                reload_Page();
            }

        });


    }

    function show_row_details(id){
        get_to_link('<?= base_url('payment/Carmovements/get_track/')?>'+id);
    }

    //</script>
