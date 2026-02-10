<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 27/01/20
 * Time: 01:22 م
 */


$MODULE_NAME= 'training';
$TB_NAME= 'Paidtraining';
?>

//<script>

    $('.sel2').select2();

    function clear_form(){
        clearForm($('#paidTraining_form'));
        $('.sel2').select2('val','');
        $('#txt_for_month').val($('#h_for_month').val());
    }

    function values_search(add_page){
        var values=
        {page:1, name:$('#txt_name').val(),
            manage:$('#dp_manage2').val(),
            field:$('#txt_field').val(),
            entry_date:$('#txt_entry_date').val(),
            id:$('#txt_id').val(),
            start_date:$('#txt_start_date').val(),
            end_date:$('#txt_end_date').val()
        };
        if(add_page==0)
            delete values.page;
        return values;
    }

    function qualifications(ser) {
        $('#ModalQual').modal();
        get_data('<?= base_url('training/traineeRequest/public_get_ac_qualifications') ?>', {id: ser}, function (data) {
            $('#div_qual').html(data);
        }, 'html');
    }

    function practExper(ser) {
        $('#ModalExper').modal();
        get_data('<?= base_url('training/traineeRequest/public_get_pract_exper') ?>', {id: ser}, function (data) {
            $('#div_exper').html(data);
        }, 'html');
    }


    function courses(ser) {
        $('#ModalCourses').modal();
        get_data('<?= base_url('training/traineeRequest/public_get_trainee_course') ?>', {id: ser}, function (data) {
            $('#div_courses').html(data);
        }, 'html');
    }


    function search(){
            var values= values_search(1);
            get_data('<?= base_url('training/paidTraining/get_page')?>',values ,function(data){
                $('#container').html(data);
            },'html');
    }

    function values_search_accept(add_page){
        var values=
        {page:1, name:$('#txt_name').val(),
            manage:$('#dp_manage').val(),
            branch:$('#dp_branch').val(),
            id:$('#txt_id').val(),
            start_date:$('#txt_start_date').val(),
            end_date:$('#txt_end_date').val()
        };
        if(add_page==0)
            delete values.page;
        return values;
    }

    function search_accept(){
        var values= values_search_accept(1);
        get_data('<?= base_url('training/paidTraining/accept_get_page')?>',values ,function(data){
            $('#container').html(data);
        },'html');

    }

    function values_search_candidate(add_page){
        var values=
        {page:1, name:$('#txt_name').val(),
            manage:$('#dp_manage').val(),
            field: $("#txt_field").val(),
            branch:$('#dp_branch').val(),
            id:$('#txt_id').val(),
            start_date:$('#txt_start_date').val(),
            end_date:$('#txt_end_date').val()
        };
        if(add_page==0)
            delete values.page;
        return values;
    }

    function search_candidate(){
        var values= values_search_candidate(1);
        get_data('<?= base_url('training/paidTraining/public_candidate_get_page')?>',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function adopt_trainee(ser,id) {
        $('#txt_id_nu').val(id);
		$.ajax({
                url:"https://im-server.gedco.ps:8001/apis/GetData/"+$('#txt_id_nu').val(),
                type: "GET",
                data:{},
                dataType:'json',
                success: (function (data) {
                    $('#txt_id_name').val(data.DATA[0].FNAME_ARB + " "+ data.DATA[0].SNAME_ARB+ " "+ data.DATA[0].TNAME_ARB+ " "+ data.DATA[0].LNAME_ARB);
                }),
                error: (function (e) {
                    alert('ER');

                })
        });
        $('#txt_ser').val(ser);
        $('#myModal').modal();
    }

    $("#save_trainee").click(function(e){
        e.preventDefault();
        if(confirm('هل تريد الحفظ  ؟!')){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>=1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    $('#myModal').modal('hide');
                    $('#txt_incentive_value').val('');
                    $('#txt_training_period').val('');
                    $('#txt_start_date').val('');
                    $('#txt_notes').val('');
                    $('#dp_responsible_emp').select2('val','');
                    $('#dp_branch').select2('val','');
                    $('#dp_manage').select2('val','');
                    search_candidate();
                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
    });


    $("#save_interview").click(function(e){
        e.preventDefault();
        if(confirm('هل تريد الحفظ  ؟!')){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
               // console.log(data);
                if(parseInt(data)>=1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    $('#myModal').modal('hide');
                    $('#txt_interview_place').val('');
                    $('#txt_interview_date').val('');
                    $('#txt_interview_time').val('');
                    $('#txt_notes').val('');
                    $('#dp_branch').select2('val','');

                    search();

                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
    });

    function renew_trainee(ser) {
        get_data('<?= base_url('training/paidTraining/public_get_train_period') ?>', {id: ser}, function (data) {
         if (data != null || data != '') {

         var json = jQuery.parseJSON(data);
             if(json.X <= 11){
                 //alert(json.X);
                //alert("مجموع الأشهر المتبقية للمتدرب .." +(11 - json.X));
                 $('#renew_div').show();
             }
             else
                 alert("لقد تجاوز الحد المسموح به للمتدرب ..");
         }
         }, 'html');
    }

    function values_search_salary(add_page){
        var values=
        {page:1, for_month:$('#txt_for_month').val(),
            manage:$('#dp_manage').val(),
            branch:$('#dp_branch').val()
        };
        if(add_page==0)
            delete values.page;
        return values;
    }

    function search_salary(){
        var values= values_search_salary(1);
        get_data('<?= base_url('training/paidTraining/public_salary_get_page')?>',values ,function(data){
            $('#container').html(data);
            //select all checkboxes
            $("#select_all").change(function(){  //"select all" change
                $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
            });
            $('#btn_adopt_sal').removeClass("hidden");

            //".checkbox" change
            $('.checkbox').change(function(){
                //uncheck "select all", if one of the listed checkbox item is unchecked
                if(false == $(this).prop("checked")){ //if this item is unchecked
                    $("#select_all").prop('checked', false); //change "select all" checked status to false
                }
                //check "select all" if all checkbox items are checked
                if ($('.checkbox:checked').length == $('.checkbox').length ){
                    $("#select_all").prop('checked', true);
                }
            });

            $("#btn_add_value").click(function(e){


            });

        },'html');
    }

    $('#btn_adopt_sal').click(function(e){
        e.preventDefault();
        var x='0/0';
        var count = 0;
        var tbl = '#page_tb';
        var container = $('#' + $(tbl).attr('data-container'));
        var val = [];

        $(tbl + ' .checkbox:checked').each(function (i) {
            x = x+','+$(this).val();
            count++;
        });

        if(x == '0/0'){
            warning_msg('يرجى تحديد السجلات المراد اعتمادها');

        }else{
            $('#h_txt_param_no').val(x);

            if(confirm('هل تريد الحفظ  ؟!')){
                $(this).attr('disabled','disabled');
                var form = $('#Paidtraining_form');
                ajax_insert_update(form,function(data){
                    if(parseInt(data)>=1){
                        success_msg('رسالة','تم الاعتماد بنجاح');
                        search_salary();

                    }else{
                        danger_msg('تحذير..',data);
                    }
                },'html');
            }
            setTimeout(function() {
                $('#btn_adopt_sal').removeAttr('disabled');
            }, 3000);
        }
    });


    function search_salary_supervision(){
        var values= values_search_salary(1);
        get_data('<?= base_url('training/paidTraining/public_salary_supervision_get_page')?>',values ,function(data){
            $('#container').html(data);
            //select all checkboxes
            $("#select_all").change(function(){  //"select all" change
                $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
            });
            $('#btn_adopt_sal_supervision').removeClass("hidden");

            //".checkbox" change
            $('.checkbox').change(function(){
                //uncheck "select all", if one of the listed checkbox item is unchecked
                if(false == $(this).prop("checked")){ //if this item is unchecked
                    $("#select_all").prop('checked', false); //change "select all" checked status to false
                }
                //check "select all" if all checkbox items are checked
                if ($('.checkbox:checked').length == $('.checkbox').length ){
                    $("#select_all").prop('checked', true);
                }
            });
        },'html');
    }

    $('#btn_adopt_sal_supervision').click(function(e){
        e.preventDefault();
        var x='0';
        var count = 0;
        var tbl = '#page_tb';
        var container = $('#' + $(tbl).attr('data-container'));
        var val = [];
        $(tbl + ' .checkbox:checked').each(function (i) {
            x = x+','+$(this).val();
            count++;
        });

        if(x == '0'){
            warning_msg('يرجى تحديد السجلات المراد اعتمادها');
        }else{
            $('#h_txt_param_no').val(x);

            if(confirm('هل تريد الحفظ  ؟!')){
                $(this).attr('disabled','disabled');
                var form = $('#Paidtraining_form');
                ajax_insert_update(form,function(data){
                    if(parseInt(data)>=1){
                        success_msg('رسالة','تم الاعتماد بنجاح');
                        info_msg('سيتم ترحيل ما تم اعتماده لكشف رواتب البنك');
                        search_salary_supervision();
                    }else{
                        danger_msg('تحذير..',data);
                    }
                },'html');
            }
            setTimeout(function() {
                $('#btn_adopt_sal_supervision').removeAttr('disabled');
            }, 3000);
        }
    });

    function values_search_bank(add_page){
        var values=
        {page:1,
            bank:$('#dp_bank').val(),
            branch:$('#dp_branch').val(),
            for_month:$('#txt_for_month').val()
        };
        if(add_page==0)
            delete values.page;
        return values;
    }

    function search_salary_bank(){
        var values= values_search_bank(1);
        get_data('<?= base_url('training/paidTraining/public_bank_salary_get_page')?>',values ,function(data){
            $('#container').html(data);
        },'html');

    }

    function addedDiscounts(obj) {
        var ser_trainee = $(obj).attr('data-id');
        var ser_con = $(obj).attr('data-con');
        var for_month = $('#txt_for_month').val();
        $('#myModal').modal();
        $('#txt_trainee_ser_').val(ser_trainee);
        $('#txt_contract_ser_').val(ser_con);
        $('#txt_for_month_').val(for_month);
        get_data('<?= base_url('training/paidTraining/public_get_added_minus') ?>',
            {id: ser_trainee, con_no: ser_con, for_month: for_month }, function (data) {
            $('#div_added_discounts').html(data);
            reBindAfterInsert();
        }, 'html');
    }

    function reBindAfterInsert(tr){
        //$("select[name='add_minus[]']",tr).html('');
        $("select[name='type[]']").change(function(){
            var tr = $(this).closest('tr');
           // $(tr).find("select[name='add_minus']").html('');
            var x = $(tr).find("select[name='add_minus[]']");
            var selectDp = $(this).val();
            var tb_no = 0;
             if(selectDp == 1) {
                 tb_no = 360;
             }else if(selectDp == 2) {
                 tb_no = 361;
             }else{
                 tb_no = 0;
             }
            $.ajax({
                url: 'public_get_twoDet/'  + tb_no,
                dataType: "json"
            }).success(function(data) {
                if(data.length ==0){
                    $(tr).find("select[name='add_minus[]']").html('');
                    $(tr).find("select[name='add_minus[]']").append($('<option/>').attr("value", '').text(''));
                    $(tr).find("select[name='add_minus[]']").prop("selected",true);
                    $(tr).find("select[name='add_minus[]']").change();
                }else{
                    $(tr).find("select[name='add_minus[]']").html('');
                    $.each(data, function(i){
                        $(tr).find("select[name='add_minus[]']").append($('<option/>').attr("value", data[i].CON_NO).text( data[i].CON_NAME));
                    });
                    $(tr).find("select[name='add_minus[]']").change();
                }
            });
        });
    }

    $('#btn_save_added').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد الحفظ  ؟!')){
            $(this).attr('disabled','disabled');
            var form = $('#paidTraining_added_form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>=1){
                    success_msg('رسالة','تم الاعتماد بنجاح');
                    addedDiscounts_(parseInt(data),$('#txt_contract_ser_').val(),$('#txt_for_month_').val());
                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('#btn_save_added').removeAttr('disabled');
        }, 3000);
    });


    function addedDiscounts_(ser_trainee,ser_con, for_month ) {
        $('#myModal').modal();
        $('#txt_trainee_ser_').val(ser_trainee);
        $('#txt_contract_ser_').val(ser_con);
        $('#txt_for_month_').val(for_month);
        get_data('<?= base_url('training/paidTraining/public_get_added_minus') ?>',
            {id: ser_trainee,con_no: ser_con, for_month: for_month }, function (data) {
            $('#div_added_discounts').html(data);
            reBindAfterInsert();
        }, 'html');
    }

    function adoptAdded(ser){
        var values=  { id:ser };
        get_data('<?= base_url('training/paidTraining/adoptAdded') ?>',values, function (data) {
            if (parseInt(data) >= 1) {
                success_msg('رسالة', 'تم الاعتماد بنجاح');
                search_salary();
                addedDiscounts_(parseInt(data),$('#txt_contract_ser_').val(),$('#txt_for_month_').val());
            }
        }, 'html');
    }


    //</script>
