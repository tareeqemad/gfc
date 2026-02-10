<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 5/2/2020
 * Time: 9:56 AM
 */
?>


//<script>

    function create_dxn_employee() {

        _showNewModal('<?= base_url('/treasury/DxnCharging/create_employee') ?>','بيانات الموظف');
    }

    function charge_dxn() {
        _showNewModal('<?= base_url('/treasury/DxnCharging/Charge') ?>','بيانات الشحنة');
    }

    function edit_dxn_employee(id) {

        _showNewModal('<?= base_url('/treasury/DxnCharging/get_employee/') ?>'+id,'بيانات الموظف');
    }


    function update_employee_status(a,id,status) {

        if(confirm('هل تريد تنفيذ هذا الاجراء ؟')) {
            ajax_fun("<?= base_url('/treasury/DxnCharging/update_employee_status') ?>", {
                ser: id,
                status: status
            }, function (rs) {

                reload_Page();
            },'html',true);
        }
    }
//</script>
