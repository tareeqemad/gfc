<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 09/08/17
 * Time: 11:27 ص
 */

$MODULE_NAME= 'rental';
$TB_NAME= 'contractor_deduction';
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$select_contractors_url= ("$MODULE_NAME/rental_contractors/public_get_all_for_select");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");

$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];

?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?=$title?></div>
        <ul>
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
                    <label class="control-label">رقم مسلسل الحركة</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['CONTRACTOR_DEDUCTION_ID']:""?>" id="txt_contractor_deduction_id" class="form-control" />
                        <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                            <input type="hidden" value="<?=$HaveRs?$rs['CONTRACTOR_DEDUCTION_ID']:''?>" name="contractor_deduction_id" id="h_contractor_deduction_id" />
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم سند ملف التعاقد</label>
                    <div>
                        <select name="contractor_file_id" id="dp_contractor_file_id" class="form-control sel2" >
                            <?=modules::run($select_contractors_url, $HaveRs?$rs['CONTRACTOR_FILE_ID']:0 );?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">حالة السند</label>
                    <div>
                        <select name="adopt" id="dp_adopt" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($adopt_cons as $row) :?>
                                <option <?=$HaveRs?($rs['ADOPT']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-8">
                    <label class="control-label">ملاحظات للمالية</label>
                    <div>
                        <input type="text" readonly id="txt_notes_for_finance" class="form-control" />
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم قرار الاستقطاع</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['DEDUCTION_ID']:""?>" name="deduction_id" id="txt_deduction_id" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">تاريخ قرار الاستقطاع</label>
                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" data-val-regex-pattern="<?=date_format_exp()?>" data-val-regex="Error" value="<?=$HaveRs?$rs['DEDUCTION_DECDATE']:""?>" name="deduction_decdate" id="txt_deduction_decdate" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ بداية الاستقطاع(من شهر)</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['DEDUCTION_SDATE']:""?>" name="deduction_sdate" id="txt_deduction_sdate" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ نهاية الاستقطاع(إلى شهر)</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['DEDUVTION_EDATE']:""?>" name="deduvtion_edate" id="txt_deduvtion_edate" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> الاستقطاع</label>
                    <div>
                        <select name="deduction_bill_id" id="dp_deduction_bill_id" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($deduction_bill_id_cons as $row) :?>
                                <option <?=$HaveRs?($rs['DEDUCTION_BILL_ID']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NO'].': '.$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">قيمة الاستقطاع</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['DEDUCTION_VALUE']:""?>" name="deduction_value" id="txt_deduction_value" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">اضافة على لاستقطاع</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['DEDUCTION_ADD']:""?>" name="deduction_add" id="txt_deduction_add" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">خصم من الاستقطاع</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['DEDUCTION_DISCOUNT']:""?>" name="deduction_discount" id="txt_deduction_discount" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-8">
                    <label class="control-label">البيان</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['NOTE']:""?>" name="note" id="txt_note" class="form-control" />
                    </div>
                </div>

            </div>

            <div class="modal-footer">

                <?php if ( HaveAccess($post_url) && ($isCreate || ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'2') and !$isCreate and $rs['ADOPT']==1 ) : ?>
                    <button type="button" onclick='javascript:adopt_(2);' class="btn btn-success">اعتماد</button>
                <?php endif; ?>

            </div>


        </form>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script>

$(function(){
    change_date_format( $('#txt_deduction_sdate') , 'DEL');
    change_date_format( $('#txt_deduvtion_edate') , 'DEL');

    $('#txt_deduction_sdate,#txt_deduvtion_edate').datetimepicker({
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

reBind();

function reBind(){
    $('.sel2:not("[id^=\'s2\']")').select2();
} // reBind

$('#dp_adopt').select2('readonly',1);

$('#dp_contractor_file_id').change(function(){
    $('#txt_notes_for_finance').val('');
    $('#txt_notes_for_finance').val( $('#dp_contractor_file_id').find('option:selected').attr('data-FNotes') );
});

$('#dp_deduction_bill_id').change(function(){
    if( $(this).val()==304 ){
        $('#txt_deduction_value').val('').prop('readonly',1);
    }else{
        $('#txt_deduction_value').prop('readonly',0);
    }
});

$('button[data-action="submit"]').click(function(e){
    e.preventDefault();
    var msg= 'هل تريد حفظ الطلب ؟!';
    if(confirm(msg)){
        $(this).attr('disabled','disabled');
        change_date_format( $('#txt_deduction_sdate') , 'ADD');
        change_date_format( $('#txt_deduvtion_edate') , 'ADD');

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

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }

    </script>
SCRIPT;

}else{ // get or edit
    $scripts = <<<SCRIPT
    {$scripts}

    $('#txt_notes_for_finance').val( $('#dp_contractor_file_id').find('option:selected').attr('data-FNotes') );

    if( $('#dp_deduction_bill_id').val()==304 ){
        $('#txt_deduction_value').prop('readonly',1);
    }

    function adopt_(no){
        var msg= 'هل تريد الاعتماد ؟!';
        if(confirm(msg)){
            var values= {contractor_deduction_id: "{$rs['CONTRACTOR_DEDUCTION_ID']}" };
            get_data('{$adopt_url}'+no, values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تم اعتماد الطلب بنجاح ..');
                    $('button').attr('disabled','disabled');
                    setTimeout(function(){
                        get_to_link(window.location.href);
                    },1000);
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }
    }

    </script>
SCRIPT;
}

sec_scripts($scripts);

?>
