<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 09/08/17
 * Time: 09:52 ص
 */

$MODULE_NAME= 'rental';
$TB_NAME= 'car_drivers';
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$select_contractors_url= ("$MODULE_NAME/rental_contractors/public_get_all_for_select");

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
                    <label class="control-label">رقم السند </label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['SER']:''?>" readonly id="txt_ser" class="form-control" />
                        <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                            <input type="hidden" value="<?=$HaveRs?$rs['SER']:''?>" name="ser" id="h_ser" />
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
                    <label class="control-label">رقم هوية السائق</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['DRIVER_ID']:""?>" name="driver_id" id="txt_driver_id" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">اسم السائق</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['DRIVER_NAME']:""?>" name="driver_name" id="txt_driver_name" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">نوع رخصة السائق</label>
                    <div>
                        <select name="license_type" id="dp_license_type" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($license_type_cons as $row) :?>
                                <option <?=$HaveRs?($rs['LICENSE_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">تاريخ انتهاء الرخصة</label>
                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" data-val-regex-pattern="<?=date_format_exp()?>" data-val-regex="Error" value="<?=$HaveRs?$rs['LICENSE_END_DATE']:""?>" name="license_end_date" id="txt_license_end_date" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">حالة عمل السائق مع المتعاقد</label>
                    <div>
                        <select name="driver_work_case" id="dp_driver_work_case" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($driver_work_case_cons as $row) :?>
                                <option <?=$HaveRs?($rs['DRIVER_WORK_CASE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">تاريخ حالة العمل</label>
                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" data-val-regex-pattern="<?=date_format_exp()?>" data-val-regex="Error" value="<?=$HaveRs?$rs['DRIVER_WORK_DATE']:""?>" name="driver_work_date" id="txt_driver_work_date" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-10">
                    <label class="control-label">ملاحظات</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['NOTE']:""?>" name="note" id="txt_note" class="form-control" />
                    </div>
                </div>

            </div>


            <div class="modal-footer">

                <?php if ( HaveAccess($post_url) && ($isCreate || ( /*$rs['ADOPT']==1 and*/ isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

                <?php if($HaveRs){
                    echo modules::run('attachments/attachment/index',$rs['SER'],'car_drivers');
                } ?>

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
