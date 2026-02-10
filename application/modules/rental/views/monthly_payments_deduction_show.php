<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 20/02/18
 * Time: 10:42 ص
 */

$MODULE_NAME= 'rental';
$TB_NAME= 'monthly_payments_deduction';

$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$select_payments_url= ("$MODULE_NAME/monthly_cpayments/public_get_all_for_select");
$select_contractors_url= ("$MODULE_NAME/rental_contractors/public_get_all_for_select");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");
$bill_url= base_url("$MODULE_NAME/$TB_NAME/public_bills_get_month_val");
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
                    <label class="control-label">رقم مسلسل الحركة</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['D_SER']:""?>" id="txt_d_ser" class="form-control" />
                        <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                            <input type="hidden" value="<?=$HaveRs?$rs['D_SER']:''?>" name="d_ser" id="h_d_ser" />
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم ملف الراتب </label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['SER']:""?>" id="txt_ser" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم المطالبة الشهرية</label>
                    <div>
                        <select name="monthly_cpayments_id" id="dp_monthly_cpayments_id" class="form-control sel2" >
                            <?=modules::run($select_payments_url, $HaveRs?$rs['MONTHLY_CPAYMENTS_ID']:0 );?>
                        </select>
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

                <div class="form-group col-sm-2">
                    <label class="control-label">اشتراكات الكهرباء</label>
                    <div>
                        <input type="text" readonly value="" id="txt_subscriptions" class="form-control" />
                    </div>
                </div>

                <div style="clear: both"></div>

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

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم الاشتراك </label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['SUBSCRIBER_ID']:""?>" name="subscriber_id" id="txt_subscriber_id" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">قيمة الاستقطاع</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['DEDUCTION_VALUE']:""?>" name="deduction_value" id="txt_deduction_value" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">شهر الفاتورة</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['BILL_MONTH']:'01/'.(date('m')-1).date('/Y')?>" name="bill_month" id="txt_bill_month" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">قيمة الفاتورة</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['BILL_VALUE']:""?>" name="bill_value" id="txt_bill_value" class="form-control" />
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-8">
                    <label class="control-label">البيان</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['NOTE']:""?>" name="note" id="txt_note" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-8">
                    <label class="control-label">ملاحظات خاصة بالاشتراك</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['SUBSCRIBER_NOTE']:""?>" name="subscriber_note" id="txt_subscriber_note" class="form-control" />
                    </div>
                </div>

            </div>

            <div class="modal-footer">

                <?php if ( HaveAccess($post_url) && ($isCreate || ( isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

            </div>

        </form>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script>

$(function(){

    change_date_format( $('#txt_bill_month') , 'DEL');

    $('#txt_bill_month').datetimepicker({
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

$('#dp_contractor_file_id').change(function(){
    $('#txt_subscriptions').val('');
    $('#txt_subscriptions').val( $('#dp_contractor_file_id').find('option:selected').attr('data-subscriptions') );
});

$('#dp_deduction_bill_id').change(function(){
    if( $(this).val()==1 ){
        $('#txt_subscriber_id,#txt_bill_month').prop('readonly',0);
    }else{
        $('#txt_subscriber_id,#txt_bill_month').val('').prop('readonly',1);
    }
});

$('button[data-action="submit"]').click(function(e){
    e.preventDefault();
    var msg= 'هل تريد حفظ الطلب ؟!';
    if(confirm(msg)){
        change_date_format( $('#txt_bill_month') , 'ADD');
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


$('#txt_subscriber_id, #txt_bill_month').change(function(){
    var values= {'subscriber_id': $('#txt_subscriber_id').val() , 'bill_month': '01/'+$('#txt_bill_month').val()};
    get_data('{$bill_url}',values ,function(data){
        $('#txt_bill_value,#txt_deduction_value').val(data.BILL_VALUE);
    },'json');
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

    $('#txt_subscriptions').val( $('#dp_contractor_file_id').find('option:selected').attr('data-subscriptions') );

    if( $('#dp_deduction_bill_id').val()!=1 ){
        $('#txt_subscriber_id,#txt_bill_month').prop('readonly',1);
    }

    </script>
SCRIPT;
}

sec_scripts($scripts);

?>
