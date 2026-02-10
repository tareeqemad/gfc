<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 27/07/17
 * Time: 10:49 ص
 */

$MODULE_NAME= 'rental';
$TB_NAME= 'rental_contractors';
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$get_car_model_url =base_url("$MODULE_NAME/$TB_NAME/public_get_car_model");
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");
$case_url= base_url("$MODULE_NAME/$TB_NAME/contract_case");
$edit_branch_url= base_url("$MODULE_NAME/$TB_NAME/edit_branch");
$get_service_info_url =base_url("$MODULE_NAME/$TB_NAME/public_get_service_info");

$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];

?>

<style type="text/css">
    .control-label .required {
        color: #e02222;
    }
</style>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?=$title?></div>

        <ul>
            <?php if(HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
        </ul>
    </div>
</div>


<div class="form-body">

    <div id="msg_container"></div>
    <div id="container">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم سند التعاقد</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['CONTRACTOR_FILE_ID']:''?>" readonly id="txt_contractor_file_id" class="form-control" />
                        <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                            <input type="hidden" value="<?=$HaveRs?$rs['CONTRACTOR_FILE_ID']:''?>" name="contractor_file_id" id="h_contractor_file_id" />
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">نوع الخدمة
                        <span class="required">*</span>
                    </label>
                    <div>
                        <select name="service_type" id="dp_service_type" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($service_type_cons as $row) :?>
                                <option <?=$HaveRs?($rs['SERVICE_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
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

                <div class="form-group col-sm-1">
                    <label class="control-label">الفرع التابع له
                        <span class="required">*</span>
                    </label>
                    <div>
                        <select name="branch_id" id="dp_branch_id" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($branch_id_cons as $row) :?>
                                <option <?=$HaveRs?($rs['BRANCH_ID']==$row['NO']?'selected':''):''?> value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم هويه المتعاقد
                        <span class="required">*</span>
                    </label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['CONTRACTOR_ID']:''?>" name="contractor_id" id="txt_contractor_id" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> اسم المتعاقد
                        <span class="required">*</span>
                    </label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['CONTRACTOR_NAME']:''?>" name="contractor_name" id="txt_contractor_name" class="form-control" />
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم اشتراك المنتفع   </label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['SUBSCRIBER_ID']:''?>" name="subscriber_id" id="txt_subscriber_id" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> اسم صاحب الاشتراك</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['SUBSCRIBER_NAME']:''?>" name="subscriber_name" id="txt_subscriber_name" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">فرع الاشتراك </label>
                    <div>
                        <select name="subscriber_branch_id" id="dp_subscriber_branch_id" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($branch_id_cons as $row) :?>
                                <option <?=$HaveRs?($rs['SUBSCRIBER_BRANCH_ID']==$row['NO']?'selected':''):''?> value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>


                <div class="form-group col-sm-1">
                    <label class="control-label">رقم اشتراك المنتفع 2</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['SUBSCRIBER_ID_2']:''?>" name="subscriber_id_2" id="txt_subscriber_id_2" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> اسم صاحب الاشتراك 2</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['SUBSCRIBER_NAME_2']:''?>" name="subscriber_name_2" id="txt_subscriber_name_2" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">فرع الاشتراك 2</label>
                    <div>
                        <select name="subscriber_branch_id_2" id="dp_subscriber_branch_id_2" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($branch_id_cons as $row) :?>
                                <option <?=$HaveRs?($rs['SUBSCRIBER_BRANCH_ID_2']==$row['NO']?'selected':''):''?> value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-2">
                    <label class="control-label">بنك المستفيد</label>
                    <div>
                        <select name="bank_id" id="dp_bank_id" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($bank_id_cons as $row) :?>
                                <option <?=$HaveRs?($rs['BANK_ID']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">فرع بنك المستفيد</label>
                    <div>
                        <select name="bank_branch" id="dp_bank_branch" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($bank_branch_cons as $row) :?>
                                <option <?=$HaveRs?($rs['BANK_BRANCH']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم الحساب</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['ACCOUNT_ID']:""?>" name="account_id" id="txt_account_id" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">iban</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['IBAN']:""?>" name="iban" id="txt_iban" class="form-control" />
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-1">
                    <label class="control-label">نوع التعاقد
                        <span class="required">*</span>
                    </label>
                    <div>
                        <select name="contract_type" id="dp_contract_type" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($contract_type_cons as $row) :?>
                                <option <?=$HaveRs?($rs['CONTRACT_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">اسم المتعاقد المحول للبنك</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['CONTRACTOR_NAME_TO_BANK']:""?>" name="contractor_name_to_bank" id="txt_contractor_name_to_bank" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم هوية المتعاقد المحول للبنك</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['CONTRACTOR_TO_BANK_ID']:""?>" name="contractor_to_bank_id" id="txt_contractor_to_bank_id" class="form-control" />
                    </div>
                </div>


                <div class="form-group col-sm-1">
                    <label class="control-label">رقم الجوال</label>
                    <div>
                        <input  type="text" value="<?=$HaveRs?$rs['MOBILE_NO']:""?>" name="mobile_no" id="txt_mobile_no" class="form-control" maxlength="10"  onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-1">
                    <label class="control-label">حالة التعاقد
                        <span class="required">*</span>
                    </label>

                    <div>
                        <select name="contract_case" id="dp_contract_case" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($contract_case_cons as $row) :?>
                                <option <?=$HaveRs?($rs['CONTRACT_CASE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">تاريخ بداية التعاقد
                        <span class="required">*</span>
                    </label>
                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" data-val-regex-pattern="<?=date_format_exp()?>" data-val-regex="Error" value="<?=$HaveRs?$rs['CONTRACT_START_DATE']:""?>" name="contract_start_date" id="txt_contract_start_date" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">تاريخ انتهاء التعاقد</label>
                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" data-val-regex-pattern="<?=date_format_exp()?>" data-val-regex="Error" value="<?=$HaveRs?$rs['CONTRACT_END_DATE']:""?>" name="contract_end_date" id="txt_contract_end_date" class="form-control" placeholder="مفتوح" />
                    </div>
                </div>

                <div class="form-group col-sm-5">
                    <label class="control-label">بيان التعاقد الحالى</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['NOTES']:''?>" name="notes" id="txt_notes" class="form-control" />
                    </div>
                </div>

                <?php if($HaveRs){
                    echo "<div data-txt='مرفقات العقد' class='form-group col-sm-2 rent_attach'>";
                    echo modules::run('attachments/attachment/index',$rs['CONTRACTOR_FILE_ID'],'rental_contractors');
                    echo "</div>";
                } ?>

                <div style="clear: both"></div>

                <div class="form-group col-sm-8">
                    <label class="control-label">ملاحظات للمالية</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['NOTES_FOR_FINANCE']:""?>" name="notes_for_finance" id="txt_notes_for_finance" class="form-control" />
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الأجر اليومى الصباحى
                        <span class="required">*</span>
                    </label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['DAILY_COST_MO']:"90"?>" name="daily_cost_mo" id="txt_daily_cost_mo" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الأجر اليومى المسائى
                        <span class="required">*</span>
                    </label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['DAILY_COST_EV']:"120"?>" name="daily_cost_ev" id="txt_daily_cost_ev" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">أجرة  الساعة الإضافية
                        <span class="required">*</span>
                    </label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['H_OVERTIME_COST']:"12.86"?>" name="h_overtime_cost" id="txt_h_overtime_cost" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الحد الاعلى لقيمة الاضافي %
                        <span class="required">*</span>
                    </label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['OVERTIME_LIMIT_RATE']:"25"?>" name="overtime_limit_rate" id="txt_overtime_limit_rate" class="form-control" />
                    </div>
                </div>

                <div style="clear: both"></div>

                <div id="service_info">

                </div>

            </div>

            <div class="modal-footer">

                <?php if ( HaveAccess($post_url) && ($isCreate || ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'2') and !$isCreate and $rs['ADOPT']==1 ) : ?>
                    <button type="button" onclick='javascript:adopt_(2);' class="btn btn-success">اعتماد</button>
                <?php endif; ?>

                <?php if ( HaveAccess($edit_branch_url) and !$isCreate ) : ?>
                    <button type="button" onclick='javascript:edit_branch();' class="btn btn-info">تعديل المقر </button>
                <?php endif; ?>

                <?php if ( HaveAccess($case_url) and !$isCreate and $rs['CONTRACT_CASE']!=1 ) : ?>
                    <button type="button" onclick='javascript:case_(1);' class="btn btn-success">تفعيل العقد</button>
                <?php endif; ?>
                <?php if ( HaveAccess($case_url) and !$isCreate and $rs['CONTRACT_CASE']!=2 ) : ?>
                    <button type="button" onclick='javascript:case_(2);' class="btn btn-danger">انهاء العقد</button>
                <?php endif; ?>
                <?php if ( HaveAccess($case_url) and !$isCreate and $rs['CONTRACT_CASE']!=3 ) : ?>
                    <button type="button" onclick='javascript:case_(3);' class="btn btn-warning">تعليق العقد</button>
                <?php endif; ?>

            </div>

        </form>
    </div>
</div>


<?php
$HaveRs= ($HaveRs)?1:0;
$scripts = <<<SCRIPT
<script>

reBind();

function reBind(){
    $('.sel2:not("[id^=\'s2\']")').select2();

    $('#txt_car_insurance_start_date,#txt_car_insurance_end_date,#txt_car_license_start,#txt_car_license_end').datetimepicker({
        format: 'DD/MM/YYYY',
        pickTime: false
    });

    $('.rent_attach').each(function(){
        $(this).find('a div').text( $(this).attr('data-txt') );
    });

} // reBind

$('#dp_adopt').select2('readonly',1);

$('#dp_service_type').change(function(){
    var service_type= parseInt($(this).val());
    if(service_type > 0){
        get_data('{$get_service_info_url}', {service_type:service_type, HaveRs:{$HaveRs}, contractor_file_id:0}, function(data){
            $('#service_info').html(data);
            reBind();
        }, 'html');
    }else{
        $('#service_info').text('');
    }
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



/* GET Model Car
$('#dp_car_class').change(function(){
    var car= $(this).select2('val');

    get_data('{$get_car_model_url}', {table:57,id:car}, function(data){
        $('#dp_car_model').html('');
        $.each(data, function(i, row) {
            $('#dp_car_model').append('<option value="'+row.CON_NO+'">'+row.CON_NAME+'</option>');
        });
    }, 'json');
});
*/

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

    $('#dp_service_type').select2('readonly',1);

    {
        var service_type= parseInt($('#dp_service_type').val());
        if(service_type > 0){
            get_data('{$get_service_info_url}', {service_type:service_type, HaveRs:{$HaveRs}, contractor_file_id: {$rs['CONTRACTOR_FILE_ID']} }, function(data){
                $('#service_info').html(data);
                reBind();
            }, 'html');
        }else{
            $('#service_info').text('خطأ: نوع الخدمة');
            $('button').attr('disabled','disabled');
        }
    }

    function adopt_(no){
        var msg= 'هل تريد الاعتماد ؟!';
        if(confirm(msg)){
            var values= {contractor_file_id: "{$rs['CONTRACTOR_FILE_ID']}" };
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

    function case_(no){
        var msg= 'هل تريد بالتأكيد تغيير حالة التعاقد ؟!';
        if(confirm(msg)){
            var values= {contractor_file_id: "{$rs['CONTRACTOR_FILE_ID']}", case:no };
            get_data('{$case_url}', values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تمت العملية بنجاح ..');
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
    
    function edit_branch(){
        if ( $('#dp_branch_id').val()=='' ){
            alert('اختر المقر');
            return 0;
        }
        var msg= 'هل تريد بالتأكيد تغيير المقر؟!';
        if(confirm(msg)){
            var values= {contractor_file_id: "{$rs['CONTRACTOR_FILE_ID']}", branch_id: $('#dp_branch_id').val() };
            get_data('{$edit_branch_url}', values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تمت العملية بنجاح ..');
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