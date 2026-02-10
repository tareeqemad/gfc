<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 29/03/18
 * Time: 09:45 ص
 */


$MODULE_NAME= 'planning';
$TB_NAME= 'planning_unit';
$select_items_url =base_url("$MODULE_NAME/$TB_NAME/public_get_Objective");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create_goal");
$post_url='';
$backs_url=base_url("$MODULE_NAME/$TB_NAME"); //$action
$count=0;
echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php  if( HaveAccess($backs_url)):  ?><li><a  href="<?= $backs_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li><?php endif; ?>
            <li><a onclick='javascript:{$TB_NAME}_delete();' href='javascript:;'><i class='glyphicon glyphicon-remove'></i>حذف</a> </li>

            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>


        </ul>

    </div>
</div>
<div class="form-body">

<div id="msg_container"></div>
<div id="container">
<form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
<div class="modal-body inline_form">
<fieldset>
    <legend>الرؤية و الرسالة</legend>
    <div class="form-group col-sm-1">
        <label class="col-sm-2 control-label">المسلسل</label>
        <div>
            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="year" id="txt_year" class="form-control" dir="rtl" value="<?php echo 1;?>" readonly>
            <span class="field-validation-valid" data-valmsg-for="year" data-valmsg-replace="true"></span>

        </div>
    </div>

    <div class="form-group col-sm-9">
        <label class="control-label">عنوان الخطة</label>
        <div>
            <input  data-val="true"  data-val-required="حقل مطلوب"  class="form-control"  name="total_price" id="total_price_id" value="<?php echo (count(@$rs)>0)? @$rs['TOTAL_PRICE']: @$project_info['TOTAL_PRICE'] ;?>" />
            <span class="field-validation-valid" data-valmsg-for="total_price" data-valmsg-replace="true"></span>
        </div>
    </div>

    <div class="form-group col-sm-1 ">
        <label class="col-sm-2 control-label">من سنة</label>
        <div>
            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="year" id="txt_year" class="form-control" dir="rtl" value="<?php echo $year_paln;?>" >
            <span class="field-validation-valid" data-valmsg-for="year" data-valmsg-replace="true"></span>

        </div>
    </div>

    <div class="form-group col-sm-1 ">
        <label class="col-sm-2 control-label">الى سنة</label>
        <div>
            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="year" id="txt_year" class="form-control" dir="rtl" value="<?php echo $year_paln+4;?>" >
            <span class="field-validation-valid" data-valmsg-for="year" data-valmsg-replace="true"></span>

        </div>
    </div>
    <div style="clear: both;">
    <div class="form-group col-sm-12">
        <label class="control-label">الرؤية</label>
        <div>
            <textarea name="total_price" rows="5" id="txt_total_price"
                      class="form-control"><?= (count(@$rs)>0)? @$rs['TOTAL_PRICE']: @$project_info['TOTAL_PRICE'] ; ?></textarea>

            <span class="field-validation-valid" data-valmsg-for="total_price" data-valmsg-replace="true"></span>
        </div>
    </div>
        <div style="clear: both;">
    <div class="form-group col-sm-12">
        <label class="control-label">الرسالة</label>
        <div>
            <textarea name="total_price" rows="5" id="txt_total_price"
                      class="form-control"><?= (count(@$rs)>0)? @$rs['TOTAL_PRICE']: @$project_info['TOTAL_PRICE'] ; ?></textarea>

            <span class="field-validation-valid" data-valmsg-for="total_price" data-valmsg-replace="true"></span>
        </div>
    </div>
            <div class="modal-footer">

                <button type="submit" data-action="submit" class="btn btn-primary" id="save1" name="save1">حفظ</button>

                <button type="submit" data-action="submit" class="btn btn-info" id="save1" name="save1">اعتماد</button>
                <button type="submit" data-action="submit" class="btn btn-danger" id="save1" name="save1">الغاء اعتماد</button>

            </div>
</fieldset>
<hr/>

    <fieldset class="hidden">
        <legend>ادارة الاهداف التشغيلية والبرامج</legend>
        <div class="form-group col-sm-2">
            <label class="control-label">من تاريخ تفعيل الاهداف التشغيلية</label>

            <div>
                <input type="text" name="donation_approved_date" data-type="date" data-date-format="DD/MM/YYYY"
                       data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"
                       data-val="true" data-val-required="حقل مطلوب" id="txt_donation_approved_date"
                       class="form-control " value="<?php if (count(@$rs) > 0) echo @$rs['DONATION_APPROVED_DATE']; ?>">
                <span class="field-validation-valid" data-valmsg-for="donation_approved_date"
                      data-valmsg-replace="true"></span>
            </div>
        </div>

        <div class="form-group col-sm-2">
            <label class="control-label">الى تاريخ تفعيل الاهداف التشغيلية</label>

            <div>
                <input type="text" name="donation_end_date" data-type="date" data-date-format="DD/MM/YYYY"
                       data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"
                       id="txt_donation_end_date" class="form-control "
                       value="<?php if (count(@$rs) > 0) echo @$rs['DONATION_END_DATE']; ?>">
                <span class="field-validation-valid" data-valmsg-for="donation_end_date"
                      data-valmsg-replace="true"></span>
            </div>
        </div>

        <div class="form-group col-sm-1">
            <label class="col-sm-2 control-label">تفعيل ادخال البرامج</label>
            <div>

                <select name="active_prog" id="dp_active_prog" class="form-control">

                    <option>نعم</option>
                    <option>لا</option>




                </select>


            </div>
        </div>

        <div class="modal-footer">

                <button type="submit" data-action="submit" class="btn btn-primary" id="save1" name="save1">حفظ</button>

            <button type="submit" data-action="submit" class="btn btn-info" id="save1" name="save1">اعتماد</button>
            <button type="submit" data-action="submit" class="btn btn-danger" id="save1" name="save1">الغاء اعتماد</button>

        </div>

    </fieldset>

<fieldset>

<legend>الغايات و الاهداف الاستراتيجية</legend>


<div class="tb_container">
    <table class="table" id="details_tb2" data-container="container" style="width:100%" align="center">
        <thead>
        <tr>
            <th  style="width:1%" >#</th>
            <th  style="width:6%">المسلسل</th>
            <th style="width:8%">رمز الغاية</th>
            <th >اسم الغاية</th>
            <th style="width:6%">حفظ</th>
            <th style="width:6%">الاهداف الاستراتيجية</th>

            <th style="width:6%">اعتماد</th>



        </tr>
        </thead>

        <tbody>

        <tr>
            <td><input type='checkbox' class='checkboxes' value='<?=@$row['SEQ']?>' data-id='<?=@$row['PROJECT_ID']?>'></td>
            <td>
                <?=++$count;?>
                <input type="hidden" data-val="true"    data-val-required="حقل مطلوب" name="id[]" id="txt_id_<?= $count ?>" value="0" class="form-control">
                <input type="hidden" data-val="true"    data-val-required="حقل مطلوب" name="id_father[]" id="txt_id_father_<?= $count ?>" value="0" class="form-control">
            </td>

            <td>

                <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="id_label[]" id="txt_id_label_<?= $count ?>" class="form-control" dir="rtl" >
                <span class="field-validation-valid" data-valmsg-for="id_label[]" data-valmsg-replace="true"></span>

            </td>
            <td>
                <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="id_name[]" id="txt_id_name_<?= $count ?>" class="form-control" dir="rtl" >
                <span class="field-validation-valid" data-valmsg-for="id_name[]" data-valmsg-replace="true"></span>
            </td>

            <td>

                <button type="button" id="btn_save_<?= $count ?>" class="btn btn-primary" name="save[]">حفظ</button>
            </td>
            <td>
                <button type="button" id="btn_active_<?= $count ?>" class="btn btn-warning" data-ser1='<?=@$row['SEQ']?>'  name="active[]">الاهداف الاستراتيجية</button>
            </td>

            <td>
                <button type="button" id="btn_adopt_<?= $count ?>" class="btn btn-info" name="adopt[]">اعتماد</button>

            </td>



        </tr>

        </tbody>

        <tfoot>

        <tr>
            <th ></th>
            <th>
                <a onclick="javascript:add_row(this,'input:text,input:hidden[name^=seq],textarea,select',false);" onfocus="javascript:add_row(this,'input:text,input:hidden[name^=seq],textarea,select',false);" href="javascript:;" class="new"><i class="glyphicon glyphicon-plus"></i>جديد</a>
            </th>

            <th ></th>
            <th ></th>
            <th ></th>
            <th ></th>



            <th ></th>
        </tr>

        </tfoot>
    </table></div>
</fieldset><hr/>
<hr/>






</div>


</form>
</div>


<?php
$scripts = <<<SCRIPT
<script type="text/javascript">


  $('#dp_active_prog').select2().on('change',function(){

       //  checkBoxChanged();

        });

reBind_pram(1);




function reBind_pram(cnt){

             $('button#btn_active_'+cnt).on('click',  function (e) {



_showReport('$select_items_url/');



        });


}

</script>

SCRIPT;

sec_scripts($scripts);

?>
