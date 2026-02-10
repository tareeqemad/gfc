<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 26/08/2019
 * Time: 11:37 ص
 */
?>

//<script>


    function insertReceipe(){
        var ser =  $('input[name="ser"]').val();
        var receipt_user =  $('input[name="receipt_user"]').val();
        var receipt_date =  $('input[name="receipt_date"]').val();
        var receipt_note =  $('input[name="receipt_note"]').val();
        get_data('<?= base_url('maintenance/maintenance/insert_Receipt') ?>',{ser: ser, receipt_user: receipt_user,receipt_date: receipt_date,receipt_note:receipt_note}, function (data) {
            //console.log(data);
            var len = data.length;
            if (len > 0) {
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                get_to_link(window.location.href);
            } else {
                danger_msg('تحذير..', data);
            }
        }, 'html');

    }
    ///////////////////////////////////////////////

    function addProcedure(obj){
        var tr = obj.closest('tr');
        var ser_class_id =  $('input[name="ser_class_id"]',tr).val();
        var class_id =  $('input[name="class_id"]',tr).val();
        var comp_name =  $('input[name="comp_name"]',tr).val();
        var cost_main =  $('input[name="cost_main"]',tr).val();
        var solve_problem =  $('input[name="solve_problem"]',tr).val();
        var status_class_id =  $('select[name="status_class_id[]"]',tr).val();
        if ($.trim(solve_problem) == '' && status_class_id == 4){
            danger_msg('يجب ادخال وصف حل المشكلة');
            return -1;
        }
        if ($.trim(comp_name) == '' && status_class_id == 2){
            danger_msg('يجب ادخال اسم الشركة والتكلفة');
            return -1;
        }
        get_data('<?= base_url('maintenance/maintenance/addProcedure') ?>',{ser_class_id: ser_class_id,class_id: class_id,comp_name:comp_name,cost_main:cost_main,solve_problem:solve_problem,status_class_id:status_class_id}, function (data) {
            //console.log(data);
            var len = data.length;
            if (len > 0) {
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            } else {
                danger_msg('تحذير..', data);
                return -1;
            }
        }, 'html');
    }

    function deleteProcedure_(obj){
        if(confirm('هل تريد حذف  ؟!!!')){
            var tr = obj.closest('tr');
            var ser_class_id =  $('input[name="ser_class_id"]',tr).val();
            get_data('<?= base_url('maintenance/maintenance/delete_class_id') ?>',{ser_class_id: ser_class_id}, function (data) {
                //console.log(data);
                var len = data.length;

                if (len > 0) {
                    success_msg('رسالة','تم الحذف بنجاح  ..');
                   // get_to_link(window.location.href);
                } else {
                    danger_msg('تحذير..', data);
                }
            }, 'html');
        }
    }

//</script>
