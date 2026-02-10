<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 10/03/18
 * Time: 11:47 ص
 */

$MODULE_NAME= 'hr_attendance';
$TB_NAME= 'exit_permission';
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");
$get_balance_url= base_url("$MODULE_NAME/$TB_NAME/public_get_balance");
$manager_url= base_url("$MODULE_NAME/$TB_NAME/index/1/manager");

$gfc_domain= gh_gfc_domain();

$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];

// اذا استخدم الموظف العادي شاشة المدير يتم منعه
if($isCreate && count($emp_no_cons) <= 1 && $page_act=='manager'){
    $emp_no_cons= array();
}

?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?=$title.(($HaveRs)?' - '.$rs['EMP_NO_NAME']:'')?></div>
        <ul>
            <?php if( 0 and HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
        </ul>
    </div>
</div>

<div class="form-body">

    <div id="msg_container"></div>
    <div id="container">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">

                <div class="col-sm-10">

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم السند </label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['SER']:''?>" readonly id="txt_ser" class="form-control" />
                        <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                            <input type="hidden" value="<?=$HaveRs?$rs['SER']:''?>" name="ser" id="h_ser" />
                        <?php endif; ?>
                    </div>
                </div>

                    <input type="hidden" name="page_act" value="<?=$page_act?>" />

                <div class="form-group col-sm-3">
                    <label class="control-label">الموظف</label>
                    <div>
                        <select name="emp_no" id="dp_emp_no" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($emp_no_cons as $row) :?>
                                <option <?=$HaveRs?($rs['EMP_NO']==$row['EMP_NO']?'selected':''):''?> value="<?=$row['EMP_NO']?>" ><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">تاريخ الاذن</label>
                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" data-val-regex-pattern="<?=date_format_exp()?>" data-val-regex="Error" value="<?=$HaveRs?$rs['P_DATE']:date('d/m/Y')?>" name="p_date" id="txt_p_date" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">ساعة الخروج</label>
                    <div>
                        <input type="text" placeholder="09:00" value="<?=$HaveRs?$rs['P_EXIT_TIME']:""?>" name="p_exit_time" id="txt_p_exit_time" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">ساعة العودة</label>
                    <div>
                        <input readonly type="text" value="<?=$HaveRs?$rs['P_RET_TIME']:""?>" name="p_ret_time" id="txt_p_ret_time" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">نوع الاذن</label>
                    <div>
                        <select name="permi_type" id="dp_permi_type" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($permi_type_cons as $row) :?>
                                <option <?=$HaveRs?($rs['PERMI_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">المكان المقصود </label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['DESTINATION']:""?>" name="destination" id="txt_destination" class="form-control" />
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-1">
                    <label class="control-label">سنة الاذن</label>
                    <div>
                        <input readonly type="text" value="<?=$HaveRs?$rs['PERM_YEAR']:date('Y')?>" name="perm_year" id="txt_perm_year" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
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
                    <label class="control-label">المقر </label>
                    <div>
                        <select name="branch_id" id="dp_branch_id" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($branches as $row) :?>
                                <option <?=$HaveRs?($rs['BRANCH_ID']==$row['NO']?'selected':''):''?> value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-4">
                    <label class="control-label">ملاحظات المدخل</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['NOTES_EMP']:""?>" name="notes_emp" id="txt_notes_emp" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">ملاحظات المراقب</label>
                    <div>
                        <input readonly type="text" value="<?=$HaveRs?$rs['NOTES_OBSERVER']:""?>" name="notes_observer" id="txt_notes_observer" class="form-control" />
                    </div>
                </div>

                </div>


                <div class="col-md-2" id="div_emp_balance">
                </div>

            </div>


            <div class="modal-footer">

                <?php if ( HaveAccess($post_url) && $page_act!='hr_admin' && ($isCreate || ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

                <?php if ( HaveAccess($post_url) && $page_act=='move_admin' && !$isCreate && $rs['ADOPT']==30 ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ التعديل </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'10') and !$isCreate and $rs['ADOPT']==1 ) : ?>
                    <button type="button" id="btn_adopt_10" onclick='javascript:adopt_(10);' class="btn btn-success">اعتماد المدخل</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'20') and !$isCreate and $rs['ADOPT']==10 && $page_act=='manager' ) : ?>
                    <button type="button" onclick='javascript:adopt_(20);' class="btn btn-success">اعتماد المدير</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'30') and !$isCreate and $rs['ADOPT']==20 ) : ?>
                    <button type="button" onclick='javascript:adopt_(30);' class="btn btn-warning"> عودة</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'40') and !$isCreate and $rs['ADOPT']==30 ) : ?>
                    <button type="button" onclick='javascript:adopt_(40);' class="btn btn-success">اعتماد الشؤون الادارية </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'0') and !$isCreate and $rs['ADOPT']==1 ) : ?>
                    <button type="button" onclick='javascript:adopt_(0);' class="btn btn-danger">الغاء </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'_20') and !$isCreate and $rs['ADOPT']==20 && $page_act=='manager' ) : ?>
                    <button type="button" onclick='javascript:adopt_("_20");' class="btn btn-danger">الغاء الاذن </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'_40') and !$isCreate and $rs['ADOPT']==40 ) : ?>
                    <button type="button" onclick='javascript:adopt_("_40");' class="btn btn-danger">الغاء الاعتماد </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'_30') and !$isCreate and ($rs['ADOPT']==30 or $rs['ADOPT']==10) && $page_act=='move_admin' ) : ?>
                    <button type="button" onclick='javascript:adopt_("_30");' class="btn btn-danger">الغاء الاذن نهائيا</button>
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
    get_balance();
} // reBind

$('#dp_adopt, #dp_branch_id').select2('readonly',1);

if('{$page_act}'=='move_admin'){
    $('#txt_p_ret_time, #txt_notes_observer').prop('readonly',0);
}

$('#txt_p_exit_time').change(function(){
    if($(this).val() < '08:00'){
        alert('ساعة الخروج يتم ادخالها بنظام 24، الساعة المدخلة حاليا قبل بداية الدوام');
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
                get_to_link('{$get_url}/'+parseInt(data)+'/{$page_act}');
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


$('#dp_emp_no').change(function(){
    get_balance();
});


$('#txt_p_date').change(function(){
    var day_ = $('#txt_p_date').val();
    var year_ = day_.substring(day_.length - 4, day_.length);
    if(year_ > 2000 && year_ < 3000 && $('#txt_perm_year').val() != year_ ){
        $('#txt_perm_year').val(year_);
        get_balance();
    }
});


function get_balance(){
    $('#div_emp_balance').text('');
    emp_no= $('#dp_emp_no').select2('val');
    p_date= $('#txt_p_date').val();
    if(emp_no!='' && p_date!=''){
        get_data('{$get_balance_url}', {emp_no:emp_no, p_date:p_date}, function(data){
            $('#div_emp_balance').html(data);
        }, 'html');
    }
}


SCRIPT;

if($isCreate){
    $scripts = <<<SCRIPT
    {$scripts}

    $('#dp_emp_no').select2('val','{$emp_no_selected}');

    get_balance();

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }



    </script>
SCRIPT;

}else{ // get or edit
    $scripts = <<<SCRIPT
    {$scripts}

    if({$rs['ADOPT']}==1){
        warning_msg('تنويه','الاذن بحاجة لاعتماد الادخال..');
    }else if({$rs['ADOPT']}==10){
        warning_msg('تنويه','الاذن بحاجة لاعتماد المدير..');
    }

    if({$rs['ADOPT']}==10 && '{$page_act}'=='manager'){
        setTimeout(function() {
            info_msg('ملاحظة','يمكنك تغيير نوع الاذن قبل اعتماده..');
        }, 1000);
    }
    
    var btn__= '';
    $('#btn_adopt_10').click( function(){
        btn__ = $(this);
    });

    function adopt_(no){
        var msg= 'هل تريد الاعتماد ؟!';
        if(no=='_40') msg= 'هل تريد بالتأكيد الغاء اعتماد الشؤون الادارية؟';
        if(no==0 || no=='_20' || no=='_30') msg= 'هل تريد بالتأكيد الغاء الاذن بشكل نهائي؟!';
        if(no==40) msg= 'هل تريد بالتأكيد اعتماد الاذن بشكل نهائي؟ لا يمكن التراجع عن هذه العملية!';
        if(confirm(msg)){
            var values= {ser: "{$rs['SER']}", permi_type:$('#dp_permi_type').select2('val') };
            get_data('{$adopt_url}'+no, values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تمت العملية بنجاح..');
                    $('button').attr('disabled','disabled');

                    if( no==10 && '{$rs['MANAGER_EMAIL']}' != ''){
                        var sub= 'يرجى اعتماد الاذن {$rs['SER']}';
                        var text= 'يرجى اعتماد الاذن الخاص بالموظف {$rs['EMP_NO_NAME']}';
                        text+= '<br>للاطلاع افتح الرابط';
                        text+= ' <br>{$gfc_domain}{$manager_url} ';
                        _send_mail(btn__,'{$rs['MANAGER_EMAIL']}',sub,text);
                        btn__ = '';
                    }

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
