<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 2019-04-23
 * Time: 9:41 AM
 */

$MODULE_NAME= 'hr';
$TB_NAME= 'dependent_students';

$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_10");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");

$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?$master_tb_data2[0] : $master_tb_data[0];

$res=$rs;

echo AntiForgeryToken();
?>

<div class="modal-header" style="background-color: #24A1F5; color: #fff;">
    <h4 class="modal-title">إدخال طالب جامعي</h4>
</div>
<form class="form-horizontal" id="<?=$TB_NAME?>_from" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">

    <div class="modal-body inline_form">

        <div class="form-group col-sm-2">
            <label class="control-label">رقم السند </label>
            <div>
                <input type="text" value="<?=$HaveRs?$rs['SER_D']:""?>" readonly id="txt_ser_d" class="form-control" />
                <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                    <input type="hidden" value="<?=$HaveRs?$rs['SER_D']:''?>" name="ser_d" id="h_ser_d" />
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group col-sm-2">
            <label class="control-label">رقم الحركة</label>
            <div>
                <input type="text"  value="<?=$HaveRs?$rs['SER_M']:$res['SER']?>"  readonly name="ser_m" id="txt_ser_m" class="form-control" />
            </div>
        </div>

        <div class="form-group col-sm-2">
            <label class="control-label">الرقم الوظيفي</label>
            <div>
                <input type="text" value="<?=$HaveRs?$rs['EMP_NO']:$res['EMP_NO']?>"  readonly name="emp_no" id="txt_emp_no" class="form-control" />
            </div>
        </div>

        <div class="form-group col-sm-3">
            <label class="control-label">اسم الموظف</label>
            <div>
                <input type="text" value="<?=$HaveRs?$rs['EMP_NO_NAME']:$res['EMP_NO_NAME']?>" readonly name="emp_no_name" id="txt_emp_no_name" class="form-control" />
            </div>
        </div>

        <div class="form-group col-sm-2">
            <label class="control-label">رقم هوية المعال</label>
            <div>
                <input type="text" value="<?=$HaveRs?$rs['IDNO_RELATIVE']:$res['IDNO_RELATIVE']?>" readonly name="idno_relative" id="txt_idno_relative" class="form-control" />
            </div>
        </div><br />

        <div class="form-group col-sm-3">
            <label class="control-label">اسم المعال</label>
            <div>
                <input type="text" value="<?=$HaveRs?$rs['IDNO_RELATIVE']:$res['FNAME_ARB'].' '.$res['SNAME_ARB'].' '.$res['TNAME_ARB'].' '.$res['LNAME_ARB']?>"  readonly name="idno_relative_name" id="txt_idno_relative_name" class="form-control" />
            </div>
        </div>

        <div class="form-group col-sm-2">
            <label class="control-label">المؤهل العلمي للمعال</label>
            <div>
                <select name="qualification_type" id="dp_qualification_type" class="form-control sel2" >
                    <option value="">_________</option>
                    <?php foreach($qualification_type_cons as $row) :?>
                        <option <?=$HaveRs?($rs['QUALIFICATION_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group col-sm-2">
            <label class="control-label">تاريخ الإلتحاق بالجامعة</label>
            <div>
                <input type="text" name="join_date" value="<?=$HaveRs?$rs['JOIN_DATE']:""?>" id="txt_join_date" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" class="form-control" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?=date_format_exp()?>"  />
                <span class="field-validation-valid" data-valmsg-for="from_date" data-valmsg-replace="true"></span>
            </div>
        </div>

        <div class="form-group col-sm-2">
            <label class="control-label">تاريخ التخرج من الجامعة</label>
            <div>
                <input type="text" name="graduation_date" value="<?=$HaveRs?$rs['GRADUATION_DATE']:""?>" id="txt_graduation_date" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" class="form-control" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?=date_format_exp()?>"  />
                <span class="field-validation-valid" data-valmsg-for="from_date" data-valmsg-replace="true"></span>
            </div>
        </div>

        <div class="form-group col-sm-2">
            <label class="control-label">تاريخ بداية الإحتساب</label>
            <div>
                <input type="text" name="from_date" value="<?=$HaveRs?$rs['FROM_DATE']:""?>" id="txt_from_date" class="form-control" />
                <span class="field-validation-valid" data-valmsg-for="from_date" data-valmsg-replace="true"></span>
            </div>
        </div>

        <div class="form-group col-sm-2">
            <label class="control-label">تاريخ نهاية الإحتساب</label>
            <div>
                <input type="text" name="to_date" value="<?=$HaveRs?$rs['TO_DATE']:""?>" id="txt_to_date" class="form-control" />
                <span class="field-validation-valid" data-valmsg-for="from_date" data-valmsg-replace="true"></span>
            </div>
        </div>

    </div>

    <div class="modal-footer">
        <?php if ( HaveAccess($post_url) && ($isCreate || ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false))) : ?>
            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
        <?php endif; ?>

        <?php if ( HaveAccess($adopt_url) and !$isCreate and $rs['ADOPT']==1 ) : ?>
            <button type="button" id="btn_adopt" onclick='javascript:adopt(10);' class="btn btn-success">اعتماد  </button>
        <?php endif; ?>
    </div>
</form>


<?php
$scripts = <<<SCRIPT
<script>

    $(function(){
        change_date_format( $('#txt_from_date') , 'DEL');
        change_date_format( $('#txt_to_date') , 'DEL');

        $('#txt_from_date,#txt_to_date').datetimepicker({
            format: 'MM/YYYY',
            minViewMode: "months",
            pickTime: false
        });

    });

    // add (01/) to date and delete it..
    function change_date_format(obj,act){
        if(act=='ADD' && obj.val()!='')
            obj.val('01/'+obj.val());
        else if(act=='DEL')
            obj.val(obj.val().substr(3));
    }

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var msg= 'هل تريد حفظ السند ؟!';
        if(confirm(msg)){
            $(this).attr('disabled','disabled');
            change_date_format( $('#txt_from_date') , 'ADD');
            change_date_format( $('#txt_to_date') , 'ADD');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    $('#dependent_studentsModal').modal('toggle');
                }else if(data==1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    $('#dependent_studentsModal').modal('toggle');
                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
    });


    function adopt(no){
        if(confirm('هل تريد اعتماد السند ؟!')){
            var adopt_url, adopt_btn;
            ser_d = $('#h_ser_d').val();
            if(no==10){
                adopt_url= '{$adopt_url}';
                adopt_btn= $('#btn_adopt');
            }
            var values= {ser_d: ser_d };
            get_data(adopt_url, values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تم الإعتماد بنجاح ..');
                    adopt_btn.attr('disabled','disabled');
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>

