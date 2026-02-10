<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 12/02/20
 * Time: 08:37 ص
 */



$MODULE_NAME= 'training';
$TB_NAME= 'administrativeMovement';
?>

//<script>

    $('.sel2').select2();

    function clear_form(){
        clearForm($('#search_form'));
        $('.sel2').select2('val','');
    }


    function values_search(add_page){
        var values=
        {page:1, name:$('#txt_name').val(),
            manage:$('#dp_manage').val(),
            branch:$('#dp_branch').val(),
            contract_status:$('#dp_contract_status').val(),
            id:$('#txt_id').val()
        };
        if(add_page==0)
            delete values.page;
        return values;
    }

    function search(){
        var values= values_search(1);
        get_data('<?= base_url('training/administrativeMovement/get_page')?>',values ,function(data){
            $('#container').html(data);
            $('select[name="contract"]' ).change(function(){
                var x = $(this).val();
                var tr = $(this).closest('tr');
                $(tr).find("a[name='manageAttendance_fin_btn']").attr('data-id', x);
                $(tr).find("a[name='manageAttendance_fin_btn']").removeClass('hidden');
                $(tr).find("a[name='manageAttendance_btn']").attr('data-id', x);
                $(tr).find("a[name='manageAttendance_btn']").removeClass('hidden');

                  //  $('a[name="manageAttendance_fin_btn"]' , tr).attr('data-id', x);
            });
        },'html');
    }




    function manageAttendance(obj) {
        var ser = $(obj).attr('data-id');
        $('#myModal').modal();
        $('#txt_trainee_ser').val(ser);
        get_data('<?= base_url('training/administrativeMovement/get_attendance') ?>', {id: ser}, function (data) {
            $('#div_attendence').html(data);
        }, 'html');
    }

    function manageAttendance_fin(obj) {
        var ser = $(obj).attr('data-id');
        $('#myModal_fin').modal();
        $('#txt_trainee_ser_fin').val(ser);
        get_data('<?= base_url('training/administrativeMovement/get_attendance_fin') ?>', {id: ser}, function (data) {
            $('#div_attendence_fin').html(data);
        }, 'html');
    }

    function manageAttendance_(ser) {
        $('#myModal').modal();
        $('#txt_trainee_ser').val(ser);
        get_data('<?= base_url('training/administrativeMovement/get_attendance') ?>', {id: ser}, function (data) {
            $('#div_attendence').html(data);
        }, 'html');
    }

    function manageAttendance_fin_(ser) {
        $('#myModal_fin').modal();
        $('#txt_trainee_ser_fin').val(ser);
        get_data('<?= base_url('training/administrativeMovement/get_attendance_fin') ?>', {id: ser}, function (data) {
            $('#div_attendence_fin').html(data);
        }, 'html');
    }

        $('button[data-action="submit"]').click(function(e){
            e.preventDefault();
            if(confirm('هل تريد الحفظ  ؟!')){
                $(this).attr('disabled','disabled');
                var form = $(this).closest('form');
                ajax_insert_update(form,function(data){
                    if(parseInt(data)>=1){
                        success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                        manageAttendance_(parseInt(data));
                    }else{
                        danger_msg('تحذير..',data);
                    }
                },'html');
            }
            setTimeout(function() {
                $('button[data-action="submit"]').removeAttr('disabled');
            }, 3000);
        });

        function saveFinAttendance(){
            if(confirm('هل تريد الحفظ  ؟!')){
                $(this).attr('disabled','disabled');
                var form = $('#fin_form');
                ajax_insert_update(form,function(data){

                    if(parseInt(data)>=1){
                        success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                        manageAttendance_fin_(parseInt(data));
                    }else{
                        danger_msg('تحذير..',data);
                    }
                },'html');
            }
            setTimeout(function() {
                $('#saveFinAttendance').removeAttr('disabled');
            }, 3000);
        }


    function deleteAttendance(id){
        var values=
        {page:1,  id: id };
        get_data('<?= base_url('training/administrativeMovement/deleteAttendance') ?>',values, function (data) {
            if (parseInt(data) >= 1) {
                success_msg('رسالة', 'تم الحذف بنجاح');
                manageAttendance_(parseInt(data));
            }
        }, 'html');
    }

    /**********************deleteAttendance************************/

    function adoptAttendance(id){
        var values=
        {page:1,  id: id };
        get_data('<?= base_url('training/administrativeMovement/adoptAttendance') ?>',values, function (data) {
            if (parseInt(data) >= 1) {
                warning_msg('تنبيه', 'سيتم احتساب راتب المتدرب للشهر وفقاً لما تم اعتماده')
                success_msg('رسالة', 'تم اعتماد الحضور والانصراف للشهر');
                manageAttendance_fin_(parseInt(data));
            }
        }, 'html');
    }

    /*******************************************************/


    function adoptAttendance_admin(id){
        var values=
        {page:1,  id: id , adopt: 3 };
        get_data('<?= base_url('training/administrativeMovement/adoptAttendance') ?>',values, function (data) {
            if (parseInt(data) >= 1) {
                success_msg('رسالة', 'تم اعتماد الحضور والانصراف للشهر');
                manageAttendance_fin_(parseInt(data));
            }
        }, 'html');
    }













    //</script>
