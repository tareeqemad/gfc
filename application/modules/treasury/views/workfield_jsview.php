<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 9/9/2021
 * Time: 11:01 AM
 */
?>

//<script>
    $("#dp_emp_no").select2();
    $("#dp_device").select2();
    $("#dp_bank_doc").select2();
    $("#dp_rep_id").select2();
    $('#dp_user').select2();
    $('#dp_user_type').select2();
    $('#dp_company').select2();
    $('#dp_device_modal').select2();
    $('#dp_device_no_modal').select2();
    $('#dp_user_modal').select2();
    $('#dp_deactive_device_modal').select2();
    $('table[ data-table="true"]').dataTable({});

    function print_report(id, user_session, p_teller, p_date, p_type){
        if (p_type == '1'){
            var repUrl = '<?= base_url("JsperReport/showreport?sys=financial/field_collection")?>&report_type=pdf&report=field_collection&p_date_from='+p_date+'&p_date_to='+p_date+'&p_teller_serial='+p_teller+'&p_user_id='+id+'&p_user_session='+user_session;
        } else {
            var repUrl = '<?= base_url("JsperReport/showreport?sys=financial/field_collection")?>&report_type=pdf&report=field_collection&p_date='+p_date+'&p_teller_serial='+p_teller+'&p_user_id='+id+'&p_user_session='+user_session;
        }
        _showReport(repUrl);
    }

    $('#dp_user_type').on('change', function() {
        if (this.value == '1')
        {
            $('#div_date').removeClass("hidden");
            $('#div_gedco').removeClass("hidden");
            $('#div_company').addClass("hidden");
            $('#div_date_company').addClass("hidden");
        } else if (this.value == '2') {
            $('#div_date').addClass("hidden");
            $('#div_gedco').addClass("hidden");
            $('#div_company').removeClass("hidden");
            $('#div_date_company').removeClass("hidden");
        } else {
            $('#div_date').addClass("hidden");
            $('#div_gedco').addClass("hidden");
            $('#div_company').addClass("hidden");
            $('#div_date_company').addClass("hidden");
        }
        $('#container').html('');
    });



    function print_canceled_report(user_session) { //
        var id = $("#dp_user").val();
        var p_from_time = $("#txt_from_hour").val();
        var p_to_time = $("#txt_to_hour").val();
        var p_from_date = $("#txt_from_date").val().replaceAll('/', '');
        var p_to_date = $("#txt_to_date").val().replaceAll('/', '');
        if(p_from_date != '' && p_to_date != '' ){
            var repUrl = '<?= base_url("JsperReport/showreport?sys=financial/field_collection")?>&report_type=pdf&report=cancel_field_collection&p_date_from='+p_from_date+'&p_date_to='+p_to_date+'&p_time_from='+p_from_time+'&p_time_to='+p_to_time+'&p_user_id='+id+'&p_user_session='+user_session;
            _showReport(repUrl);
        }else {
            danger_msg('تحذير','يجب إدخال من تاريخ و إلى تاريخ');
        }
    }

    function print_all_report(user_session) {
        var id = $("#dp_user").val();
        var p_from_time = $("#txt_from_hour").val();
        var p_to_time = $("#txt_to_hour").val();
        var p_from_date = $("#txt_from_date").val().replaceAll('/', '');
        var p_to_date = $("#txt_to_date").val().replaceAll('/', '');
        if(p_from_date != '' && p_to_date != '' ){
            var repUrl = '<?= base_url("JsperReport/showreport?sys=financial/field_collection")?>&report_type=pdf&report=field_collection&p_date_from='+p_from_date+'&p_date_to='+p_to_date+'&p_time_from='+p_from_time+'&p_time_to='+p_to_time+'&p_user_id='+id+'&p_user_session='+user_session;
            _showReport(repUrl);
        }else {
            danger_msg('تحذير','يجب إدخال من تاريخ و إلى تاريخ');
        }
    }

    function print_total_rep(user_session) {
        var id = $("#dp_user").val();
        var p_from_time = $("#txt_from_hour").val();
        var p_to_time = $("#txt_to_hour").val();
        var p_from_date = $("#txt_from_date").val().replaceAll('/', '');
        var p_to_date = $("#txt_to_date").val().replaceAll('/', '');
        if(p_from_date != '' && p_to_date != '' ){
            var repUrl = '<?= base_url("JsperReport/showreport?sys=financial/field_collection")?>&report_type=pdf&report=field_collection_totals&p_date_from='+p_from_date+'&p_date_to='+p_to_date+'&p_time_from='+p_from_time+'&p_time_to='+p_to_time+'&p_user_session='+user_session;
            _showReport(repUrl);
        }else {
            danger_msg('تحذير','يجب إدخال من تاريخ و إلى تاريخ');
        }
    }

    function print_receipt(id, user_session, p_teller, p_date){
        var repUrl = '<?= base_url("JsperReport/showreport?sys=financial/field_collection")?>&report_type=pdf&report=receipt_field_collection&p_date='+p_date+'&p_teller_serial='+p_teller+'&p_user_id='+id+'&p_user_session='+user_session;
        _showReport(repUrl);
    }

    function back_stage(teller_serial, type) {
        showLoading();
        ajax_fun('<?= base_url("treasury/workfield/back_stage")?>', {
            teller_serial: teller_serial,
            type: type
        }, function (data) {
            HideLoading();
            errorMessage(data,1)
        });
    }

    function update_document(teller_serial) {
        showLoading();
        ajax_fun('<?= base_url("treasury/workfield/update_document")?>', {
            teller_serial: teller_serial,
            record_no: $("#dp_bank_doc").val()
        }, function (data) {
            HideLoading();
            errorMessage(data,0)
        });
    }


    function update_user_account(a, id) {
        var account = $(a).closest('tr').find('input[data-id="account"]').val();
        var income_account = $(a).closest('tr').find('input[data-id="income-account"]').val();

        if (account == null || account == '' || income_account == null || income_account == '') {
            //return
        }
        showLoading();
        ajax_fun('<?= base_url("treasury/workfield/updateaccount")?>', {
            income_account: income_account,
            id: id
        }, function (data) {
            HideLoading();
            errorMessage(data,0)
        });
    }

    function update_password(a, id) {
        showLoading();
        ajax_fun('<?= base_url("treasury/workfield/updatePassword")?>', {
            id: id
        }, function (data) {
            HideLoading();
            errorMessage(data,0)
        });
    }

    function close_selected_bills(pk, id, date, teller_serial) {
        /*var selectedIds = [];
        $('#Tbl input.checkboxes:checked').each(function () {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length <= 0) {
            alert('يجب تحديد السندات المراد اغلاقها و ترحيلها')
            return
        }*/
        $('#btn_close_selected_bills').addClass('hidden');
        var total = $("#txt_total_"+pk).val();
        Swal.fire({
            title:  'سيتم تشكيل القيد بالمبلغ ' + total + ' هل تريد الاستمرار؟ ',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'نعم',
            cancelButtonText: 'الغاء',
            showLoaderOnConfirm: true
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                ajax_fun('<?= base_url("treasury/workfield/transfer")?>', {'id': id , 'date': date, 'teller_serial': teller_serial, 'type': 1}, function (data) {
                    errorMessage(data,1)
                    HideLoading();
                });
            }
        })

    }


    function close_selected_bills_comp(pk, id, date, teller_serial) {
        /*var selectedIds = [];
        $('#Tbl input.checkboxes:checked').each(function () {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length <= 0) {
            alert('يجب تحديد السندات المراد اغلاقها و ترحيلها')
            return
        }*/
        $('#btn_close_selected_bills').addClass('hidden');
        var total = $("#txt_total_"+pk).val();
        Swal.fire({
            title:  'سيتم تشكيل القيد بالمبلغ ' + total + ' هل تريد الاستمرار؟ ',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'نعم',
            cancelButtonText: 'الغاء',
            showLoaderOnConfirm: true
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                ajax_fun('<?= base_url("treasury/workfield/transfer")?>', {'id': id , 'date': date, 'teller_serial': teller_serial, 'type': 2}, function (data) {
                    errorMessage(data,1)
                    HideLoading();
                });
            }
        })

    }


    function updateSaveBill(e) {
        e.preventDefault();
        if ($(e.target).valid()) {

            const data = new FormData(e.target);
            const value = Object.fromEntries(data.entries());
            ajax_fun('<?= base_url("treasury/workfield/updatebill")?>', value, function (data) {
                errorMessage(data,0)
            });
        }
        return false;
    }

    function updateUserPassword(e) {
        e.preventDefault();
        if ($(e.target).valid()) {

            const data = new FormData(e.target);
            const value = Object.fromEntries(data.entries());
            ajax_fun('<?= base_url("treasury/workfield/updatePassword")?>', value, function (data) {
                errorMessage(data,0)
            });
        }
        return false;
    }

    function updateUserData(e) {
        e.preventDefault();
        if ($(e.target).valid()) {

            let mob = $('#user_mobile').val();
            str = mob.substring(0, 3);
            var check_num_mob = isNaN($('#user_mobile').val());
            if($('#user_mobile').val().length == 10 && (str == '059' || str =='056') && check_num_mob == false ){
                const data = new FormData(e.target);
                const value = Object.fromEntries(data.entries());
                ajax_fun('<?= base_url("treasury/workfield/updateData")?>', value, function (data) {
                    errorMessage(data,0)
                });
            } else {
                danger_msg('تحذير..','يرجى التاكد من رقم المحمول');
            }

        }
        return false;
    }

    function cancel_workfield_bill(a,id) {
        Swal.fire({
            title: 'الرجاء ادخال سبب الالغاء',
            input: 'text',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'حفظ',
            cancelButtonText: 'الغاء',
            showLoaderOnConfirm: true,
            inputValidator: (value) => {
                if (!value) {
                    return 'يجب ادخال السبب'
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {

                ajax_fun('<?= base_url("treasury/workfield/cancelbill")?>', {
                    'id': id,
                    text: result.value
                }, function (data) {
                    errorMessage(data,0)
                });
            }
        })
    }


    function cancel_workfield_bill_adopt(a,id,adopt) {
        Swal.fire({
            title: 'الرجاء ادخال سبب الالغاء',
            input: 'text',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'حفظ',
            cancelButtonText: 'الغاء',
            showLoaderOnConfirm: true,
            inputValidator: (value) => {
                if (!value) {
                    return 'يجب ادخال السبب'
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {

                ajax_fun('<?= base_url("treasury/workfield/CancelBillAdopt")?>', {
                    'id': id,
                    'adopt':adopt,
                    text: result.value
                }, function (data) {
                    errorMessage(data,0)
                });
            }
        })
    }

    function addNote(a,id) {
        Swal.fire({
            title: 'الرجاء ادخال ملاحظة',
            input: 'text',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'حفظ',
            cancelButtonText: 'الغاء',
            showLoaderOnConfirm: true,
            inputValidator: (value) => {
                if (!value) {
                    return 'يجب ادخال الملاحظة'
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {

                ajax_fun('<?= base_url("treasury/workfield/AddNOTE")?>', {
                    'id': id,
                    text: result.value
                }, function (data) {
                    errorMessage(data,0)
                });
            }
        })
    }

    function cancel_workfield_adopt_bill(a,id) {
        Swal.fire({
            title: 'هل أنت متأكد من الغاء اعتماد السند؟',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'نعم',
            cancelButtonText: 'الغاء',
            showLoaderOnConfirm: true
        }).then((result) => {
            if (result.isConfirmed) {

                ajax_fun('<?= base_url("treasury/workfield/canceladoptbill")?>', {
                    'id': id,
                    text: result.value
                }, function (data) {
                    errorMessage(data,0)
                });
            }
        })
    }

    function adopt_cancel_workfield_bill(a,id) {
        Swal.fire({
            title: 'هل أنت متأكد من الغاء السند؟',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'نعم',
            cancelButtonText: 'الغاء',
            showLoaderOnConfirm: true
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                ajax_fun('<?= base_url("treasury/workfield/adoptcancelbill")?>', {
                    'id': id,
                    text: result.value
                }, function (data) {
                    HideLoading();
                    errorMessage(data,0)
                });
            }
        })
    }

    function errorMessage(data, reload) {
        try {
            var obj = data.status === undefined ?  jQuery.parseJSON(data) : data;

            if (obj.STATUS === 1 || obj.status == 1 ) {
                showAlert('رسالة', 'تم حفظ البيانات بنجاح', 'success');
                if(reload == 0){
                    reload_Page()
                } else {
                    do_search()
                }
            } else {
                showAlert('تحذير', data.error, 'error');
            }
        } catch (e) {
            showAlert('تحذير', data, 'error');
        }
    }

    $('#Tbl input.checkboxes , input[data-set="#Tbl .checkboxes"]').change(function () {

        var sum = 0;
        $('#Tbl input.checkboxes:checked').each(function () {
            sum += parseFloat($(this).attr('data-value'));
        });
        $('#total-selected').text(sum);
    });
    function Adopt_selected_bills(user_id, date) {
        var selectedIds = [];
        $('#Tbl input.checkboxes:checked').each(function () {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length <= 0) {
            alert('يجب تحديد السندات المراد اعتمادها ')
            return
        }

        Swal.fire({
            title: 'هل أنت متأكد من العملية؟',
            showCancelButton: true,
            confirmButtonText: 'تأكيد',
            cancelButtonText: 'الغاء',
            showLoaderOnConfirm: true
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                ajax_fun('<?= base_url("treasury/workfield/Adopt")?>', {
                    'ids[]': selectedIds,
                    'bank_doc': $("#dp_bank_doc").val()
                }, function (data) {
                    HideLoading();
                    if (data >= 1) {
                        showAlert('رسالة', 'تم حفظ البيانات بنجاح', 'success');
                        get_to_link('<?= base_url("treasury/workfield/first_satge_adopt")?>/' +user_id +'/2/'+date+'/'+ data);
                    } else if (data == -1) {
                        showAlert('تحذير', 'يرجى تحديث بيانات رقم المستند', 'error');
                    } else if (data == -2) {
                        showAlert('تحذير', 'يرجى تحديث رقم العملية', 'error');
                    } else {
                        showAlert('تحذير', data, 'error');
                    }
                });
            }
        })
    }

    function Adopt_selected_bills_comp(user_id, date, comp_date) {
        var selectedIds = [];
        $('#Tbl input.checkboxes:checked').each(function () {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length <= 0) {
            alert('يجب تحديد السندات المراد اعتمادها ')
            return
        }

        Swal.fire({
            title: 'هل أنت متأكد من العملية؟',
            showCancelButton: true,
            confirmButtonText: 'تأكيد',
            cancelButtonText: 'الغاء',
            showLoaderOnConfirm: true
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                ajax_fun('<?= base_url("treasury/workfield/Adopt")?>', {
                    'ids[]': selectedIds,
                    'bank_doc': $("#dp_bank_doc").val()
                }, function (data) {
                    HideLoading();
                    if (data >= 1) {
                        showAlert('رسالة', 'تم حفظ البيانات بنجاح', 'success');
                        get_to_link('<?= base_url("treasury/workfield/first_satge_comp")?>/' +user_id +'/2/'+date+'/'+ data +'/'+comp_date);
                    } else if (data == -1) {
                        showAlert('تحذير', 'يرجى تحديث بيانات رقم المستند', 'error');
                    } else if (data == -2) {
                        showAlert('تحذير', 'يرجى تحديث رقم العملية', 'error');
                    } else {
                        showAlert('تحذير', data, 'error');
                    }
                });
            }
        })
    }


    function RecNo (){
        //   var modal=document.getElementById("wide");
        // modal.style.display = "block";
        Swal.fire({
            title: 'الرجاء ادخال رقم المستند',
            input: 'text',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'حفظ',
            cancelButtonText: 'الغاء',
            showLoaderOnConfirm: true,
            inputValidator: (value) => {
                if (!value) {
                    return 'يجب ادخال رقم المستند'
                }
            }

        }).then((result) => {
            if (result.isConfirmed) {

                ajax_fun('<?= base_url("treasury/workfield/cancelbill")?>', {
                    'id': id,
                    text: result.value
                }, function (data) {
                    errorMessage(data,0)
                });
            }
        })

    }
    function adopt_bill_record(a, id, teller_serial) {
        $('#btn_adopt_bill_record').addClass('hidden');
        var total_val = $(a).closest('tr').find('input[data-id="total_val"]').val();
        var income_val = $(a).closest('tr').find('input[data-id="income_val"]').val();
        var differance_val = income_val -  total_val;

        if (total_val == null || total_val == '' || income_val == null || income_val == '') {
            return
        }
        Swal.fire({
            title: 'هل أنت متأكد من العملية؟',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'نعم',
            cancelButtonText: 'الغاء',
            showLoaderOnConfirm: true
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                ajax_fun('<?= base_url("treasury/workfield/update_daily_financial")?>', {
                    total_val: total_val,
                    income_val: income_val,
                    teller_serial: teller_serial,
                    id: id
                }, function (data) {
                    HideLoading();
                    errorMessage(data,1)
                });
            }
        })

    }

    function transfer_to_bills(id, date, type) {
        Swal.fire({
            title: 'هل أنت متأكد من العملية؟',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'نعم',
            cancelButtonText: 'الغاء',
            showLoaderOnConfirm: true
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                ajax_fun('<?= base_url("treasury/workfield/supervision_adopt")?>', {'ids': id , 'date': date, 'type': type}, function (data) {
                    errorMessage(data,1)
                    HideLoading();
                });
            }
        })
    }




    $('#dp_emp_no').change(function(){
        ajax_fun('<?= base_url("treasury/workfield/get_operation_no")?>', {'emp_no': $('#dp_emp_no').val() }, function (data) {
            $('#txt_operation_no').val(data);
        });
    });

    function updateOperationNo() {
        //var selectedIds = [];
        Swal.fire({
            title: 'هل أنت متأكد من العملية؟',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'نعم',
            cancelButtonText: 'الغاء',
            showLoaderOnConfirm: true
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                ajax_fun('<?= base_url("treasury/workfield/update_operation_no")?>', {'emp_no': $('#dp_emp_no').val() , 'operation_no': $('#txt_operation_no').val()}, function (data) {
                    errorMessage(data,0);
                    HideLoading();
                });
            }
        })

    }

    function change_status_device(id, status) {

        Swal.fire({
            title:  ' هل تريد الاستمرار؟ ',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'نعم',
            cancelButtonText: 'الغاء',
            showLoaderOnConfirm: true
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                ajax_fun('<?= base_url("treasury/workfield/change_status_device")?>', {'id': id, 'status': status }, function (data) {
                    errorMessage(data,1)
                    HideLoading();
                });
            }
        })

    }

    function receive_device(id) {
        Swal.fire({
            title:  ' هل تريد الاستمرار؟ ',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'نعم',
            cancelButtonText: 'الغاء',
            showLoaderOnConfirm: true
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                ajax_fun('<?= base_url("treasury/workfield/receive_device")?>', {'id': id }, function (data) {
                    errorMessage(data,1)
                    HideLoading();
                });
            }
        })
    }

    function device_delivery(e) {

        e.preventDefault();
        if ($(e.target).valid()) {
            if ($('#dp_user_modal').val() == null) {
                showAlert('تحذير', 'يرجى اختيار اسم المحصل ', 'error');
                return
            }

            if ($('#dp_device_modal').val() == null) {
                showAlert('تحذير', 'يرجى اختيار رقم الجهاز  ', 'error');
                return
            }

            const data = new FormData(e.target);
            const value = Object.fromEntries(data.entries());
            ajax_fun('<?= base_url("treasury/workfield/deviceDelivery")?>', value, function (data) {
                $('#DeviceModal').modal('hide');
                clearForm_any($('#DeviceModal'));
                errorMessage(data,1)
            });

        }
        return false;
    }

    function device_create(e) {
        e.preventDefault();
        if ($(e.target).valid()) {
            if ($('#dp_device_no_modal').val() == null) {
                showAlert('تحذير', 'يرجى كتابة رقم الجهاز  ', 'error');
                return
            }

            const data = new FormData(e.target);
            const value = Object.fromEntries(data.entries());
            ajax_fun('<?= base_url("treasury/workfield/create_device")?>', value, function (data) {
                $('#CreateDeviceModal').modal('hide');
                errorMessage(data,0)
            });

        }
        return false;
    }

    function device_deactive(e) {
        e.preventDefault();
        if ($(e.target).valid()) {
            if ($('#dp_deactive_device_modal').val() == null) {
                showAlert('تحذير', 'يرجى اختيار رقم الجهاز  ', 'error');
                return
            }
            change_status_device($('#dp_deactive_device_modal').val(), 2);
            $('#DeactiviateDeviceModal').modal('hide');

        }
        return false;
    }


    //</script>
