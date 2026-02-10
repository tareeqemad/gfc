<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 09/08/17
 * Time: 11:40 ص
 */

$MODULE_NAME= 'rental';
$TB_NAME= 'contractor_attendance';
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$delete_url= base_url("$MODULE_NAME/$TB_NAME/delete");
$select_contractors_url= ("$MODULE_NAME/rental_contractors/public_get_all_for_select");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");

$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];
?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?=$title?></div>
        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?=$create_url?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <?php if( HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
        </ul>
    </div>
</div>

<div class="form-body">

    <div id="msg_container"></div>
    <div id="container">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">

                <div class="form-group col-sm-1">
                    <label class="control-label">مسلسل الحركة</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['SER']:''?>" readonly id="txt_ser" class="form-control" />
                        <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                            <input type="hidden" value="<?=$HaveRs?$rs['SER']:''?>" name="ser" id="h_ser" />
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم سند ملف التعاقد
                        <span style="font-size: 10px; font-weight:400; color: #0070a3" >
                            <?=$isCreate?'(يمكن اختيار اكثر من متعاقد)':''?>
                        </span>
                    </label>
                    <div>
                        <select <?=$isCreate?'multiple':''?> name="contractor_file_id00" id="dp_contractor_file_id" class="form-control sel2" >
                            <?=modules::run($select_contractors_url, $HaveRs?$rs['CONTRACTOR_FILE_ID']:0 );?>
                        </select>
                        <input type="hidden" id="h_contractor_file_id" name="contractor_file_id" value="<?=$HaveRs?$rs['CONTRACTOR_FILE_ID']:""?>" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم السيارة</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['CAR_NUM']:""?>" name="car_num" id="txt_car_num" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">نوع الدوام </label>
                    <div>
                        <select name="signature_type" id="dp_signature_type" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($signature_type_cons as $row) :?>
                                <option <?=$HaveRs?($rs['SIGNATURE_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ  توقيع المتعاقد</label>
                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" data-val-regex-pattern="<?=date_format_exp()?>" data-val-regex="Error" value="<?=$HaveRs?$rs['SIGNATURE_DATE']:date('d/m/Y')?>" name="signature_date" id="txt_signature_date" class="form-control" />
                    </div>
                </div>


                <div class="form-group col-sm-1">
                    <label class="control-label">وقت الحضور</label>
                    <div>
                        <input type="text" placeholder="08:05" value="<?=$HaveRs?$rs['SIGNATURE_TIME_IN']:""?>" name="signature_time_in" id="txt_signature_time_in" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">وقت الانصراف</label>
                    <div>
                        <input type="text" placeholder="14:01" value="<?=$HaveRs?$rs['SIGNATURE_TIME_OUT']:""?>" name="signature_time_out" id="txt_signature_time_out" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">عدد ساعات الاضافي </label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['OVERTIME_HOURS']:""?>" name="overtime_hours" id="txt_overtime_hours" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">مصدر التوقيع</label>
                    <div>
                        <select name="signature_source" id="dp_signature_source" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($signature_source_cons as $row) :?>
                                <option <?=$HaveRs?($rs['SIGNATURE_SOURCE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-11">
                    <label class="control-label">ملاحظات</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['NOTE']:""?>" name="note" id="txt_note" class="form-control" />
                    </div>
                </div>

            </div>

            <div class="modal-footer">

                <?php if ( HaveAccess($post_url) && ($isCreate || ( isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

                <?php if ( HaveAccess($delete_url) and !$isCreate ) : ?>
                    <button type="button" onclick='javascript:delete_();' class="btn btn-danger"> حذف  </button>
                <?php endif; ?>

            </div>

        </form>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script>

reBind();

function reBind(){
    $('.sel2:not("[id^=\'s2\']")').select2();
} // reBind

$('#dp_signature_source').select2('readonly',1);

$('#dp_contractor_file_id').change(function(e){
    $('#h_contractor_file_id').val( $(this).val() );
});

$('button[data-action="submit"]').click(function(e){
    e.preventDefault();
    var msg= 'هل تريد حفظ الطلب ؟!';
    if(confirm(msg)){
        $(this).attr('disabled','disabled');
        var form = $(this).closest('form');
        ajax_insert_update(form,function(data){
            if(parseInt(data)>1){
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                get_to_link('{$get_url}/'+parseInt(data)+'/edit');
            }else if(data==1){
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                get_to_link(window.location.href);
            }else{
                danger_msg('تحذير..',data);
            }
        },'html');
    }
    setTimeout(function() {
        $('button[data-action="submit"]').removeAttr('disabled');
    }, 3000);
});

SCRIPT;

if($isCreate){
    $scripts = <<<SCRIPT
    {$scripts}

    $('#dp_signature_type').select2('val',1);

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }

    </script>
SCRIPT;

}else{ // get or edit
    $scripts = <<<SCRIPT
    {$scripts}

    function delete_(){
        if(confirm('هل تريد بالتأكيد حذف السجل نهائيا؟')){
            get_data('{$delete_url}',{ser: {$rs['SER']} },function(data){
                if(data==1){
                    success_msg('رسالة','تم حذف السجل بنجاح ..');
                    get_to_link('{$back_url}');
                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('button').removeAttr('disabled');
        }, 3000);
    }

    </script>
SCRIPT;
}

sec_scripts($scripts);

?>
