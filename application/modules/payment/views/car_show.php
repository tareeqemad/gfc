<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 29/01/15
 * Time: 12:14 م
 */

$create_url = base_url('payment/cars/create');
$get_url = base_url('payment/cars/get_id/');
$notes_url = base_url('settings/notes/public_create');
$post_url= base_url("payment/cars/".($action == 'index'?'create':$action));
$select_url= base_url('payment/cars/public_select_car_num');

if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;
$rs = $HaveRs ? $result[0] : $result;


?>

<div class="row">
<div class="toolbar">

    <div class="caption"><?= $title ?></div>

    <ul>
        <?php if (HaveAccess($create_url)): ?>
            <li><a href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
        <li><a onclick="<?= $help ?>" href="javascript:" class="help"><i class="icon icon-question-circle"></i></a>
        </li>

    </ul>

</div>

<div class="form-body">

<div id="msg_container"></div>


<div id="container">
<form class="form-horizontal" id="car_form" method="post"
      action="<?=$post_url?>" role="form"
      novalidate="novalidate">


<div class="tabbable-line" id="actions" style="margin-top: 40px">
<ul class="nav nav-tabs ">
    <li class="active">
        <a href="#tab_1" data-toggle="tab"> البيانات الاساسية </a>
    </li>
    <li>
        <a href="#tab_2" data-toggle="tab"> بيانات الترخيص </a>
    </li>

    <li>
        <a href="#tab_3" data-toggle="tab"> بيانات التأمين </a>
    </li>

    <li>
        <a href="#tab_4" data-toggle="tab"> التغير بالوقود </a>
    </li>
    <li>
        <a href="#tab_5" data-toggle="tab">الاهلاكات</a>
    </li>

</ul>
<div class="tab-content">
<div class="tab-pane active " id="tab_1">
<div class="modal-body inline_form">

<div class="form-group">
    <label class="col-sm-1 control-label"> رقم الملف </label>

    <div class="col-sm-1">
        <input type="text" readonly name="car_file_id"
               value="<?= $HaveRs ? $rs['CAR_FILE_ID'] : '' ?>"
               id="txt_car_file_id" class="form-control">
    </div>


    <label class="col-sm-1 control-label">رقم الآلية الجديد</label>

    <div class="col-sm-1">
        <input type="text"  name="car_num"
               value="<?= $HaveRs ? $rs['CAR_NUM'] : '' ?>"
               id="txt_car_num" class="form-control" required>
    </div>

    <label class="col-sm-1 control-label">رقم الآلية القديم</label>

    <div class="col-sm-1">
        <input type="text"  name="car_num_old"
               value="<?= $HaveRs ? $rs['CAR_NUM_OLD'] : '' ?>"
               id="txt_car_num_old" class="form-control" readonly>
    </div>

    <label class="col-sm-1 control-label">صاحب العهدة </label>
    <div class="col-sm-5">

        <input type="hidden"
               value="<?= $HaveRs ? $rs['CAR_OWNER_NO'] : "" ?>"
               name="car_owner_no" id="h_txt_car_owner"
               class="form-control">

        <input type="text"
               data-val-required="حقل مطلوب"
               data-val="true"
               readonly
               name="car_owner"
               value="<?= $HaveRs ? $rs['CAR_NUM_NAME'] : "" ?>"
               id="txt_car_owner"
               class="form-control">

    </div>
</div>
<div class="form-group">
    <label class="col-sm-1 control-label"> الفرع</label>

    <div class="col-sm-1">
        <select type="text" name="branch_id" id="dp_branch_id" class="form-control">
            <?php foreach ($branches as $row) : ?>
                <option <?= $HaveRs && $rs['BRANCH_ID'] == $row['NO'] ? 'selected' : '' ?>
                    value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
            <?php endforeach; ?>
        </select>

    </div>
	
	<label class="col-sm-1 control-label">متابعة العداد</label>

    <div class="col-sm-1">
        <select type="text" name="car_case" id="dp_car_case_id" class="form-control" required>

            <option></option>
            <?php foreach ($car_case as $row) : ?>
                <option <?= $HaveRs && $rs['CAR_CASE'] == $row['CON_NO'] ? 'selected' : '' ?> value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
            <?php endforeach; ?>
        </select>

    </div>

    <label class="col-sm-1 control-label">حالة الآلية</label>
    <div class="col-sm-1">
        <select type="text" name="machine_case" id="dp_machine_case_id" class="form-control" required>

            <option></option>
            <?php foreach ($machine_case as $row) : ?>
                <option <?= $HaveRs && $rs['MACHINE_CASE'] == $row['CON_NO'] ? 'selected' : '' ?>  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
            <?php endforeach; ?>
        </select>

    </div>
	
</div>
<hr>

<div class="form-group">
    <label class="col-sm-1 control-label">التكلفة المالية لساعة العمل</label>

    <div class="col-sm-2">
        <input type="text"  name="car_cost_houre"
               value="<?= $HaveRs ? $rs['CAR_COST_HOURE'] : '' ?>"
               id="txt_car_cost_houre" class="form-control">
    </div>

    <label class="col-sm-2 control-label">نوع العهدة</label>

    <div class="col-sm-2">
        <select name="custody_type" id="dp_custody_type" class="form-control">

            <?php foreach($custody_type_list as $row) :?>
                <option  value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==((count($rs)>0)?$rs['CUSTODY_TYPE']:0)){ echo " selected"; } ?> ><?php echo $row['CON_NAME']  ?></option>
            <?php endforeach; ?>
        </select>
    </div>



    <label class="col-sm-1 control-label">ملكية السيارة</label>

    <div class="col-sm-2">
        <select  name="car_ownership" id="dp_car_ownership" class="form-control">

            <?php foreach($car_ownership_list as $row) :?>
                <option  value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==((count($rs)>0)?$rs['CAR_OWNERSHIP']:0)){ echo " selected"; } ?> ><?php echo $row['CON_NAME']  ?></option>
            <?php endforeach; ?>
        </select>


    </div>



</div>
<hr>


<div class="form-group">


    <label class="col-sm-1 control-label">تاريخ انتاج السيارة</label>

    <div class="col-sm-2">
        <input type="text" data-type="date" data-date-format="DD/MM/YYYY"
               name="production_date"
               value="<?= $HaveRs ? $rs['PRODUCTION_DATE'] : '' ?>"
               id="txt_production_date"
               class="form-control">

    </div>

    <label class="col-sm-2 control-label">تاريخ دخول الخدمة</label>

    <div class="col-sm-2">

        <input type="text" data-type="date" data-date-format="DD/MM/YYYY"
               name="service_date"
               value="<?= $HaveRs ? $rs['SERVICE_DATE'] : '' ?>"
               id="txt_service_date"
               class="form-control">
    </div>



    <label class="col-sm-1 control-label">تاريخ التخصيص الجديد</label>

    <div class="col-sm-2">
        <input type="text" data-type="date" data-date-format="DD/MM/YYYY"
               name="monthly_allocated_date"
               value="<?= $HaveRs ? $rs['MONTHLY_ALLOCATED_DATE'] : '' ?>"
               id="txt_monthly_allocated_date"
               class="form-control">

    </div>



</div>
<hr>

<div class="form-group">

    <label class="col-sm-1 control-label"> سعر السيارة</label>

    <div class="col-sm-2">
        <input type="text" name="price"
               value="<?= $HaveRs ? $rs['PRICE'] : '' ?>"
               data-val="true" data-val-required="حقل مطلوب" id="txt_price"
               class="form-control">

    </div>


    <label class="col-sm-2 control-label">نسبة الاهلاك</label>

    <div class="col-sm-2">
        <input type="text"  name="depreciation_rate"
               value="<?= $HaveRs ? $rs['DEPRECIATION_RATE'] : '' ?>"
               id="txt_depreciation_rate" class="form-control">
    </div>


    <label class="col-sm-1 control-label">عدد اللترات  في كم</label>

    <div class="col-sm-2">
        <input type="text"  name="letter_per_kilometer"
               value="<?= $HaveRs ? $rs['LETTER_PER_KILOMETER'] : '' ?>"
               id="txt_letter_per_kilometer" class="form-control">
    </div>




</div>

<hr>

<div class="form-group">
    <label class="col-sm-1 control-label">الوزن الذاتي</label>

    <div class="col-sm-2">
        <input type="text"  name="self_weight"
               value="<?= $HaveRs ? $rs['SELF_WEIGHT'] : '' ?>"
               id="txt_self_weight" class="form-control">
    </div>

    <label class="col-sm-2 control-label">الوزن الاجمالي</label>

    <div class="col-sm-2">
        <input type="text"  name="total_weight"
               value="<?= $HaveRs ? $rs['TOTAL_WEIGHT'] : '' ?>"
               id="txt_total_weight" class="form-control">
    </div>

    <label class="col-sm-1 control-label">نوع الرخصة</label>

    <div class="col-sm-2">
        <select name="license_type" id="dp_clicense_type" class="form-control">

            <?php foreach($license_type_list as $row) :?>
                <option  value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==((count($rs)>0)?$rs['LICENSE_TYPE']:0)){ echo " selected"; } ?> ><?php echo $row['CON_NAME']  ?></option>
            <?php endforeach; ?>
        </select>
    </div>



</div>
<hr>

<div class="form-group">
    <label class="col-sm-1 control-label"> نوع الألية</label>

    <div class="col-sm-2">
        <select type="text" name="car_class" id="dp_car_class" class="form-control sel2">
            <option>   </option>
            <?php foreach ($car_class as $row) : ?>
                <option <?= $HaveRs && $rs['CAR_CLASS'] == $row['CON_NO'] ? 'selected' : '' ?>
                    value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <label class="col-sm-2 control-label"> موديل السيارة </label>

    <div class="col-sm-2">


        <input type="text" name="car_model"
               value="<?= $HaveRs ? $rs['CAR_MODEL'] : '' ?>"
               id="txt_car_model" class="form-control">

    </div>

    <label class="col-sm-1 control-label"> نوع الوقود </label>

    <div class="col-sm-2">
        <select type="text" name="fuel_type" id="dp_fuel_type" class="form-control">
            <?php foreach ($fuel_type as $row) : ?>
                <option <?= $HaveRs && $rs['FUEL_TYPE'] == $row['CON_NO'] ? 'selected' : '' ?>
                    value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-1 control-label"> رقم الشصي </label>

    <div class="col-sm-2">
        <input type="text" data-val="true" data-val-required="حقل مطلوب"
               name="definition_code"
               value="<?= $HaveRs ? $rs['DEFINITION_CODE'] : '' ?>"
               id="txt_definition_code"
               class="form-control">
                                        <span class="field-validation-valid" data-valmsg-for="definition_code"
                                              data-valmsg-replace="true"></span>
    </div>


    <label class="col-sm-2 control-label"> سنة الانتاج </label>

    <div class="col-sm-2">
        <input type="text" name="year_produce"
               value="<?= $HaveRs ? $rs['YEAR_PRODUCE'] : '' ?>"
               id="txt_year_produce" class="form-control">

    </div>

    <label class="col-sm-1 control-label"> قوة المحرك </label>

    <div class="col-sm-2">
        <input type="text" name="engine_power"
               value="<?= $HaveRs ? $rs['ENGINE_POWER'] : '' ?>"
               id="txt_engine_power" class="form-control">

    </div>
</div>

<hr>
<div class="form-group">
    <label class="col-sm-1 control-label"> المخصص الشهري </label>

    <div class="col-sm-1">
        <input type="text" name="monthly_allocated"
               value="<?= $HaveRs ? $rs['MONTHLY_ALLOCATED'] : '' ?>"
               id="txt_monthly_allocated"
               class="form-control">

    </div>


    <label class="col-sm-1 control-label"> تاريخ التخصيص </label>

    <div class="col-sm-1">
        <input type="text" data-type="date" data-date-format="DD/MM/YYYY"
               name="monthly_allocated_date"
               value="<?= $HaveRs ? $rs['MONTHLY_ALLOCATED_DATE'] : '' ?>"
               id="txt_monthly_allocated_date" class="form-control">

    </div>


    <label class="col-sm-1 control-label"> وظيفة صاحب العهدة </label>

    <div class="col-sm-2">
        <select name="user_job" id="dp_user_job" class="form-control">
            <?php foreach ($user_job as $row) : ?>
                <option <?= $HaveRs && $rs['USER_JOB'] == $row['CON_NO'] ? 'selected' : '' ?>
                    value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
            <?php endforeach; ?>
        </select>

    </div>

    <label class="col-sm-1 control-label"> الغاية من الاستخدام </label>

    <div class="col-sm-2">
        <select name="used_purpose" id="dp_used_purpose" class="form-control">
            <?php foreach ($used_purpose as $row) : ?>
                <option <?= $HaveRs && $rs['USED_PURPOSE'] == $row['CON_NO'] ? 'selected' : '' ?>
                    value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<hr>


<div class="form-group">
    <label class="col-sm-1 control-label">الملاحظات</label>

    <div class="col-sm-9">
        <textarea type="text" name="hints" id="txt_hints"
                  class="form-control"><?= $HaveRs ? $rs['HINTS'] : '' ?></textarea>

    </div>
</div>

</div>
</div>

<div class="tab-pane" id="tab_2">
    <div class="modal-body inline_form">

        <label class="col-sm-1 control-label">قيمة الترخيص</label>
        <div class="col-sm-2">
            <input type="text" name="car_license_value"
                   value="<?= $HaveRs ? $rs['CAR_LICENSE_VALUE'] : '' ?>"
                   id="txt_CAR_LICENSE_VALUE"
                   class="form-control">
        </div>

        <label class="col-sm-1 control-label">رقم الترخيص </label>
        <div class="col-sm-2">
            <input type="text" name="car_license_number"
                   value="<?= $HaveRs ? $rs['CAR_LICENSE_NUMBER'] : '' ?>"
                   id="txt_car_license_number"
                   class="form-control">
        </div>
        <label class="col-sm-1 control-label">تاريخ الترخيص </label>

        <div class="col-sm-2">
            <input type="text" data-type="date" data-date-format="DD/MM/YYYY"
                   name="date_of_license"
                   value="<?= $HaveRs ? $rs['DATE_OF_LICENSE'] : '' ?>"
                   id="txt_date_of_license"
                   class="form-control">
        </div>

    </div>

    <hr>

    <div class="form-group">

        <label class="col-sm-2 control-label">تاريخ بداية الترخيص</label>
        <div class="col-sm-2">
            <input type="text" data-type="date" data-date-format="DD/MM/YYYY"
                   name="car_license_start"
                   value="<?= $HaveRs ? $rs['CAR_LICENSE_START'] : '' ?>"
                   id="txt_car_license_start"
                   class="form-control">

        </div>

        <label class="col-sm-2 control-label">تاريخ نهاية الترخيص</label>

        <div class="col-sm-2">
            <input type="text" data-type="date" data-date-format="DD/MM/YYYY"
                   name="car_license_end"
                   value="<?= $HaveRs ? $rs['CAR_LICENSE_END'] : '' ?>"
                   id="txt_car_license_end"
                   class="form-control">
        </div>
        <br><hr>
        <?php echo modules::run('payment/cars/public_get_car_licences', (count($rs)) ? $rs['CAR_FILE_ID'] : 0); ?>
    </div>
</div>

<div class="tab-pane" id="tab_3">

    <div class="form-group">


        <label class="col-sm-1 control-label">رقم التأمين</label>

        <div class="col-sm-2">
            <input type="text" name="insurance_number"
                   value="<?= $HaveRs ? $rs['INSURANCE_NUMBER'] : '' ?>"
                   id="txt_insurance_number" class="form-control">
        </div>

        <label class="col-sm-1 control-label">نوع التأمين</label>
        <div class="col-sm-2">
            <select name="insurance_type" id="dp_insurance_type" class="form-control">
                <option></option>
                <?php foreach($insurance_type_list as $row) :?>
                    <option  value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==((count($rs)>0)?$rs['INSURANCE_TYPE']:0)){ echo " selected"; } ?> ><?php echo $row['CON_NAME']  ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <label class="col-sm-1 control-label">شركة التامين</label>
        <div class="col-sm-2">
            <select name="license_combany" id="dp_license_combany" class="form-control">
                <option></option>
                <?php foreach($license_combany_list as $row) :?>
                    <option  value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==((count($rs)>0)?$rs['LICENSE_COMBANY']:0)){ echo " selected"; } ?> ><?php echo $row['CON_NAME']  ?></option>
                <?php endforeach; ?>
            </select>
        </div>

    </div>

    <hr>
    <div class="form-group">

        <label class="col-sm-1 control-label"> قيمة التأمين </label>


        <div class="col-sm-2 control-label">
            <input type="text" name="insurance_value"
                   value="<?= $HaveRs ? $rs['INSURANCE_VALUE'] : '' ?>"
                   id="txt_insurance_value" class="form-control">

        </div>

        <label class="col-sm-1 control-label"> تاريخ بداية التأمين </label>

        <div class="col-sm-2">
            <input type="text" data-type="date" data-date-format="DD/MM/YYYY"
                   name="insurance_start_date"
                   value="<?= $HaveRs ? $rs['INSURANCE_START_DATE'] : '' ?>"
                   id="txt_insurance_start_date"
                   class="form-control">

        </div>

        <label class="col-sm-1 control-label">تاريخ نهاية التأمين</label>

        <div class="col-sm-2">
            <input type="text" data-type="date" data-date-format="DD/MM/YYYY"
                   name="insurance_end_date"
                   value="<?= $HaveRs ? $rs['INSURANCE_END_DATE'] : '' ?>"
                   id="txt_insurance_end_date"
                   class="form-control">

        </div>


    </div>


    <hr>
    <div class="form-group">
        <label class="col-sm-1 control-label">تاريخ التأمين</label>

        <div class="col-sm-2">
            <input type="text" data-type="date" data-date-format="DD/MM/YYYY"
                   name="insurance_date"
                   value="<?= $HaveRs ? $rs['INSURANCE_DATE'] : '' ?>"
                   id="txt_insurance_date"
                   class="form-control">
        </div>

    </div>

    <hr>



    <?php echo modules::run('payment/cars/public_get_car_insurance', (count($rs)) ? $rs['CAR_FILE_ID'] : 0); ?>


</div>

<div class="tab-pane" id="tab_4">
    <?php echo modules::run('payment/cars/public_get_CAR_LICENSE', (count($rs)) ? $rs['CAR_FILE_ID'] : 0); ?>
</div>


<div class="tab-pane" id="tab_5">
    <?php echo modules::run('payment/cars/public_get_car_depreciation', (count($rs)) ? $rs['CAR_FILE_ID'] : 0); ?>
</div>


</div>
</div>


<div style="clear: both;">

    <?php echo modules::run('settings/notes/public_get_page', (count($rs)) ? $rs['CAR_FILE_ID'] : 0, 'car_file_tb'); ?>

    <?php if ( $HaveRs ) : ?>
        <span><?php echo modules::run('attachments/attachment/index',$rs['CAR_FILE_ID'],'car_file_tb'); ?></span>
    <?php endif; ?>
</div>
<hr>
<div class="modal-footer">
    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
</div>
</form>

</div>

</div>

</div>

<div class="modal fade" id="notesModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <h4 class="modal-title"> ملاحظات</h4>
            </div>
            <div id="msg_container_alt"></div>

            <div class="form-group col-sm-12">

                <div class="">
                    <textarea type="text" data-val="true" rows="5" id="txt_g_notes" class="form-control "></textarea>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="apply_action();" class="btn btn-primary">حفظ البيانات</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

            </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal1 -->
<?php

$id = ((count($rs)) ? $rs['CAR_FILE_ID'] : 0);

$scripts = <<<SCRIPT

<script>
    $('.sel2').select2();
    $('button[data-action="submit"]').click(function(e){

        e.preventDefault();

        $('#notesModal').modal();

    });

    $('#txt_car_owner').click(function (e) {
        _showReport('{$select_url}/'+$(this).attr('id')+'/');
    });

     function apply_action(){

           saveDate();

        get_data('{$notes_url}',{source_id:{$id},source:'car_file_tb',notes:$('#txt_g_notes').val()},function(data){
            $('#txt_g_notes').val('');
        },'html');

         $('#notesModal').modal('hide');
     }

     function saveDate(){
         
        if ($('#dp_car_class').val() == '') {
            danger_msg('رسالة','الرجاء ادخال نوع الألية ..');
        return;
        }
        
        var form = $('#car_form');

        var isCreate = form.attr('action').indexOf('create') >= 0;

        ajax_insert_update(form,function(data){
        if (parseInt(data) >= 1) {
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
           get_to_link( '{$get_url}'+'/'+parseInt(data) );
            } else {
                    danger_msg('تحذير..', data);
                }
            //reload_Page();

        },"html");
     }

</script>

SCRIPT;

sec_scripts($scripts);


?>
