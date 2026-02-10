<?php

$MODULE_NAME= 'rental';
$TB_NAME= 'Monthly_payments_bonus';

$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$select_payments_url= ("$MODULE_NAME/monthly_cpayments/public_get_all_for_select");
$select_contractors_url= ("$MODULE_NAME/rental_contractors/public_get_all_for_select");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");
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
                        <input type="text" readonly value="<?=$HaveRs?$rs['B_SER']:""?>" id="txt_b_ser" class="form-control" />
                        <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                            <input type="hidden" value="<?=$HaveRs?$rs['B_SER']:''?>" name="b_ser" id="h_b_ser" />
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

                <div class="form-group col-sm-3">
                    <label class="control-label">رقم سند ملف التعاقد (يمكن الاضافة للجميع اذا لم تحدد متعاقد)</label>
                    <div>
                        <select name="contractor_file_id" id="dp_contractor_file_id" class="form-control sel2" >
                            <?=modules::run($select_contractors_url, $HaveRs?$rs['CONTRACTOR_FILE_ID']:0 );?>
                        </select>
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> الاضافة</label>
                    <div>
                        <select name="bonus_bill_id" id="dp_bonus_bill_id" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($bonus_bill_id_cons as $row) :?>
                                <option <?=$HaveRs?($rs['BONUS_BILL_ID']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NO'].': '.$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">قيمة الاضافة</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['BONUS_VALUE']:""?>" name="bonus_value" id="txt_bonus_value" class="form-control" />
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-8">
                    <label class="control-label">البيان</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['NOTE']:""?>" name="note" id="txt_note" class="form-control" />
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

reBind();

function reBind(){
    $('.sel2:not("[id^=\'s2\']")').select2();
} // reBind

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

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }

    </script>
SCRIPT;

}else{ // get or edit
    $scripts = <<<SCRIPT
    {$scripts}


    </script>
SCRIPT;
}

sec_scripts($scripts);

?>
