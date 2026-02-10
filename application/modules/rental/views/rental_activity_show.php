<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 09/08/17
 * Time: 10:19 ص
 */

$MODULE_NAME= 'rental';
$TB_NAME= 'rental_activity';
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");
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
    <div id="msg5_container" class="alert alert-warning <?=$HaveRs?(($rs['ADOPT']==2 and ($rs['ACTIVITY_TYPE'] ==4 or $rs['ACTIVITY_TYPE']==5 or $rs['ACTIVITY_TYPE']==6 or $rs['ACTIVITY_TYPE']==7 or $rs['ACTIVITY_TYPE']==8))?'  ':' hidden '):' hidden '?>" role="alert">يجب اعتماد هذه الحركة طرف الرقابة!!</div>
    <div id="msg_container"></div>
    <div id="container">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم مسلسل الحركة</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['RENTAL_ACTIVITY_ID']:""?>" name="rental_activity_id" id="txt_rental_activity_id" class="form-control" />
                        <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                            <input type="hidden" value="<?=$HaveRs?$rs['RENTAL_ACTIVITY_ID']:''?>" name="rental_activity_id" id="h_rental_activity_id" />
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم سند ملف التعاقد</label>
                    <div>
                        <select name="contractor_file_id" id="dp_contractor_file_id" class="form-control sel2" >
                            <?=modules::run($select_contractors_url, $HaveRs?$rs['CONTRACTOR_FILE_ID']:0 ,0,null);?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم السيارة</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['CAR_NUM']:''?>" name="car_num" id="txt_car_num" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">نوع الحركة</label>
                    <div>
                        <select name="activity_type" id="dp_activity_type" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($activity_type_cons as $row) :?>
                                <option <?=$HaveRs?($rs['ACTIVITY_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
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

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ قرار التجديد</label>
                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" data-val-regex-pattern="<?=date_format_exp()?>" data-val-regex="Error" value="<?=$HaveRs?$rs['ACTIVITY_DATE']:""?>" name="activity_date" id="txt_activity_date" class="form-control" />
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-1">
                    <label class="control-label">تاريخ البداية الجديد</label>
                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" data-val-regex-pattern="<?=date_format_exp()?>" data-val-regex="Error" value="<?=$HaveRs?$rs['NEW_START_DATE']:""?>" name="new_start_date" id="txt_new_start_date" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">تاريخ النهاية الجديد</label>
                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" data-val-regex-pattern="<?=date_format_exp()?>" data-val-regex="Error" value="<?=$HaveRs?$rs['NEW_END_DATE']:""?>" name="new_end_date" id="txt_new_end_date" class="form-control" placeholder="اختياري" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم مسلسل التأمين الجديد</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['NEW_INSURANCE_NUM']:""?>" name="new_insurance_num" id="txt_new_insurance_num" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> شركة التأمين الجديد</label>
                    <div>
                        <select name="new_insurance_company" id="dp_new_insurance_company" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($insurance_company_cons as $row) :?>
                                <option <?=$HaveRs?($rs['NEW_INSURANCE_COMPANY']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> نوع التأمين الجديد</label>
                    <div>
                        <select name="new_insurance_type" id="dp_new_insurance_type" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($insurance_type_cons as $row) :?>
                                <option <?=$HaveRs?($rs['NEW_INSURANCE_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-8">
                    <label class="control-label">البيان الجديد</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['NEW_NOTE']:""?>" name="new_note" id="txt_new_note" class="form-control" />
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم السيارة الجديد</label>
                    <div>
                        <input type="text" placeholder="اختياري" value="<?=$HaveRs?$rs['NEW_CAR_NUM']:""?>" name="new_car_num" id="txt_new_car_num" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-4">
                    <label class="control-label">iban الجديد</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['NEW_IBAN']:""?>" name="new_iban" id="txt_new_iban" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم الاشتراك الجديد</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['NEW_SUBSCRIBER_ID']:""?>" name="new_subscriber_id" id="txt_new_subscriber_id" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم الجوال الجديد</label>
                    <div>
                        <input  type="text" value="<?=$HaveRs?$rs['NEW_MOBILE_NO']:""?>" name="new_mobile_no" id="txt_new_mobile_no" class="form-control" maxlength="10"  onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-2">
                    <label class="control-label">اسم بنك المستفيد الجديد</label>
                    <div>
                        <select name="new_bank_id" id="dp_new_bank_id" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($bank_id_cons as $row) :?>
                                <option <?=$HaveRs?($rs['NEW_BANK_ID']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">فرع بنك المستفيد الجديد</label>
                    <div>
                        <select name="new_bank_branch" id="dp_new_bank_branch" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($bank_branch_cons as $row) :?>
                                <option <?=$HaveRs?($rs['NEW_BANK_BRANCH']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم الحساب الجديد</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['NEW_ACCOUNT_ID']:""?>" name="new_account_id" id="txt_new_account_id" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">اسم المتعاقد المحول للبنك الجديد</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['NEW_CONTRACTOR_NAME_TO_BANK']:""?>" name="new_contractor_name_to_bank" id="txt_new_contractor_name_to_bank" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم هوية المتعاقد المحول للبنك الجديد</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['NEW_CONTRACTOR_TO_BANK_ID']:""?>" name="new_contractor_to_bank_id" id="txt_new_contractor_to_bank_id" class="form-control" />
                    </div>
                </div>

                <hr style="margin-bottom: 10px;" />

                <div class="form-group col-sm-1">
                    <label class="control-label">تاريخ البداية القديم</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['OLD_START_DATE']:""?>" name="old_start_date" id="txt_old_start_date" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">تاريخ النهاية القديم</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['OLD_END_DATE']:""?>" name="old_end_date" id="txt_old_end_date" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم مسلسل التأمين القديم</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['OLD_INSURANCE_NUM']:""?>" name="old_insurance_num" id="txt_old_insurance_num" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> شركة التأمين القديم</label>
                    <div>
                        <select disabled name="old_insurance_company" id="dp_old_insurance_company" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($insurance_company_cons as $row) :?>
                                <option <?=$HaveRs?($rs['OLD_INSURANCE_COMPANY']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> شركة التأمين القديم</label>
                    <div>
                        <select disabled name="old_insurance_type" id="dp_old_insurance_type" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($insurance_type_cons as $row) :?>
                                <option <?=$HaveRs?($rs['OLD_INSURANCE_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-8">
                    <label class="control-label">البيان القديم</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['OLD_NOTE']:""?>" name="old_note" id="txt_old_note" class="form-control" />
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم السيارة القديم</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['OLD_CAR_NUM']:""?>" name="old_car_num" id="txt_old_car_num" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-4">
                    <label class="control-label">iban القديم</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['OLD_IBAN']:""?>" name="old_iban" id="txt_old_iban" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم الاشتراك القديم</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['OLD_SUBSCRIBER_ID']:""?>" name="old_subscriber_id" id="txt_old_subscriber_id" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم الجوال القديم</label>
                    <div>
                        <input  type="text" readonly value="<?=$HaveRs?$rs['OLD_MOBILE_NO']:""?>" name="old_mobile_no" id="txt_old_mobile_no" class="form-control" maxlength="10"  onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-2">
                    <label class="control-label">اسم بنك المستفيد القديم</label>
                    <div>
                        <select disabled name="old_bank_id" id="dp_old_bank_id" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($bank_id_cons as $row) :?>
                                <option <?=$HaveRs?($rs['OLD_BANK_ID']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">فرع بنك المستفيد القديم</label>
                    <div>
                        <select disabled name="old_bank_branch" id="dp_old_bank_branch" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($bank_branch_cons as $row) :?>
                                <option <?=$HaveRs?($rs['OLD_BANK_BRANCH']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم الحساب القديم</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['OLD_ACCOUNT_ID']:""?>" name="old_account_id" id="txt_old_account_id" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">اسم المتعاقد المحول للبنك القديم</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['OLD_CONTRACTOR_NAME_TO_BANK']:""?>" name="old_contractor_name_to_bank" id="txt_old_contractor_name_to_bank" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم هوية المتعاقد المحول للبنك القديم</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['OLD_CONTRACTOR_TO_BANK_ID']:""?>" name="old_contractor_to_bank_id" id="txt_old_contractor_to_bank_id" class="form-control" />
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

                <?php if ( HaveAccess($adopt_url.'3') and !$isCreate and $rs['ADOPT']==2 and ($rs['ACTIVITY_TYPE'] ==4 or $rs['ACTIVITY_TYPE']==5 or $rs['ACTIVITY_TYPE']==6 or $rs['ACTIVITY_TYPE']==7 or $rs['ACTIVITY_TYPE']==8)) : ?>
                    <button type="button" onclick='javascript:adopt_(3);' class="btn btn-success">اعتماد الرقابة </button>
                <?php endif; ?>

            </div>

        </form>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script>

reBind();
chk_activity_type();

function reBind(){
    $('.sel2:not("[id^=\'s2\']")').select2();
} // reBind

$('#dp_adopt').select2('readonly',1);

$('#dp_activity_type').change(function(){
    chk_activity_type();
});

function chk_activity_type(){
    var activity_type= $('#dp_activity_type').val();
    var all_new_input= $('#txt_new_start_date,#txt_new_end_date,#txt_new_insurance_num,#txt_new_note,#txt_new_car_num,#txt_new_iban,#txt_new_subscriber_id,#txt_new_account_id,#txt_new_contractor_name_to_bank,#txt_new_contractor_to_bank_id,#txt_new_mobile_no');
    var all_new_sel2=  $('#dp_new_insurance_company,#dp_new_insurance_type,#dp_new_bank_id,#dp_new_bank_branch');
    var available_input= $('#DontSelectAnyThing__');
    var available_sel2=  $('#DontSelectAnyThing__');

    switch (parseInt(activity_type)){
        case 1:
            available_input= $('#txt_new_start_date,#txt_new_end_date,#txt_new_note,#txt_new_car_num');
            break;
        case 2:
            available_input= $('#txt_new_start_date,#txt_new_end_date');
            break;
        case 3:
            available_input= $('#txt_new_start_date,#txt_new_end_date,#txt_new_insurance_num');
            available_sel2=  $('#dp_new_insurance_company,#dp_new_insurance_type');
            break;
        case 4:
            available_input= $('#txt_new_iban');
            break;
        case 5:
        case 6:
            available_input= $('#txt_new_subscriber_id');
            break;
        case 7:
            available_input= $('#txt_new_account_id');
            available_sel2=  $('#dp_new_bank_id,#dp_new_bank_branch');
            break;
        case 8:
            available_input= $('#txt_new_contractor_name_to_bank,#txt_new_contractor_to_bank_id');
            break;
        case 9:
            available_input= $('#txt_new_mobile_no');
            break;
        default:
            return 0;
    }

    available_input.prop('readonly',0);
    available_sel2.select2('readonly',0);
    all_new_input.not(available_input).val('').prop('readonly',1);
    all_new_sel2.not(available_sel2).select2('val','').select2('readonly',1);
}

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

    function adopt_(no){
        var msg= 'هل تريد الاعتماد ؟!';
        if(confirm(msg)){
            var values= {rental_activity_id: "{$rs['RENTAL_ACTIVITY_ID']}" };
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
