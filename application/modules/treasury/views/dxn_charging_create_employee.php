<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 5/9/2020
 * Time: 9:50 AM
 */
$rs = isset($employee) && count($employee)> 0 ? $employee[0] : null;
$rsHasValue = $rs != null;
?>
<form class="form-horizontal"
      method="post"
      action="<?= base_url('/treasury/DxnCharging/').($rsHasValue ? 'get_employee' : 'create_employee') ?>"
      reload-parent="true"
      insert-fetech-form="True">


    <div class="form-group">
        <label class="control-label col-sm-2">م.</label>
        <div class="col-sm-2">
            <input type="text"
                   readonly
                   name="ser"
                   id="ser"
                   value="<?= $rsHasValue ? $rs['SER'] :'' ?>"
                   class="form-control ">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2">رقم الموظف</label>
        <div class="col-sm-3">
            <input type="text"
                   data-val="true"
                   data-val-required="حقل مطلوب"
                   name="emp_no"
                   id="emp_no"
                   value="<?= $rsHasValue ? $rs['EMP_NO'] :'' ?>"
                   class="form-control ">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2">اسم الموظف الموظف</label>
        <div class="col-sm-5">
            <input type="text"
                   data-val="true"
                   data-val-required="حقل مطلوب"
                   name="emp_name"
                   id="emp_name"
                   value="<?= $rsHasValue ? $rs['EMP_NAME'] :'' ?>"
                   class="form-control ">
        </div>
    </div>


    <div class="form-group">
        <label class="control-label col-sm-2">رقم الاشتراك</label>
        <div class="col-sm-3">
            <input type="text"
                   data-val="true"
                   data-val-required="حقل مطلوب"
                   name="sub_no"
                   id="sub_no"
                   value="<?= $rsHasValue ? $rs['SUB_NO'] :'' ?>"
                   class="form-control ">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2">رقم الجوال</label>
        <div class="col-sm-3">
            <input type="text"
                   data-val="true"
                   data-val-required="حقل مطلوب"
                   name="jwal_no"
                   id="jwal_no"
                   value="<?= $rsHasValue ? $rs['JWAL_NO'] :'' ?>"
                   class="form-control ">
        </div>
    </div>

    <hr>
    <div class="form-group">
        <div class="col-md-offset-2 col-md-10"><input type="submit" class="btn btn-success" value="حفظ "></div>
    </div>
</form>