<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 19/03/18
 * Time: 11:58 ص
 */

$MODULE_NAME= 'hr_attendance';
$TB_NAME= 'vacation_request';
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

$date_attr= " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";

$tomorrow = new DateTime('+1 day');
$tomorrow= $tomorrow->format('d/m/Y');

// اذا استخدم الموظف العادي شاشة المدير يتم منعه
if($isCreate && count($emp_no_cons) <= 1 && $page_act=='manager'){
    $emp_no_cons= array();
}

// اظهار نوع الاجازة - مرضية
if($HaveRs and $rs['ADOPT']>=10 ){
    $show_type_3= 1;
}else if($page_act=='move_admin'){
    $show_type_3= 1;
}else{
    $show_type_3= 0;
}

$vac_arr = array(1,2,17); // عادية او طارئة   او غياب بدون اذن

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

                <div class="col-sm-9">

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

                <div class="form-group col-sm-2">
                    <label class="control-label">نوع الاجازة</label>
                    <div>
                        <select name="vac_type" id="dp_vac_type" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($vac_type_cons as $row) :
                            if( $row['CON_NO']==3 and !$show_type_3){ $disabled='disabled'; }else{ $disabled= ''; }
                            ?>
                                <option <?=$disabled?> <?=$HaveRs?($rs['VAC_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ الاجازة</label>
                    <div>
                        <input type="text" <?=$date_attr?> value="<?=$HaveRs?$rs['VAC_DATE']:$tomorrow?>" name="vac_date" id="txt_vac_date" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ نهاية الاجازة</label>
                    <div>
                        <input type="text" <?=$date_attr?> value="<?=$HaveRs?$rs['VAC_END_DATE']:$tomorrow?>" name="vac_end_date" id="txt_vac_end_date" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">مدة الاجازة</label>
                    <div>
                        <input readonly type="text" value="<?=$HaveRs?$rs['VAC_DURATION']:""?>" name="vac_duration" id="txt_vac_duration" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ العودة</label>
                    <div>
                        <input readonly type="text" <?=$date_attr?> value="<?=$HaveRs?$rs['RET_DATE']:""?>" name="ret_date" id="txt_ret_date" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">العنوان أثناء الاجازة</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['ADD_IN_VAC']:""?>" name="add_in_vac" id="txt_add_in_vac" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">سبب القيام بالاجازة</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['THE_REASON']:""?>" name="the_reason" id="txt_the_reason" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">الموظف بالانابة</label>
                    <div>
                        <select name="acting_officer" id="dp_acting_officer" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($acting_officer_cons as $row) :?>
                                <option <?=$HaveRs?($rs['ACTING_OFFICER']==$row['EMP_NO']?'selected':''):''?> value="<?=$row['EMP_NO']?>" ><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">السنة</label>
                    <div>
                        <input readonly type="text" value="<?=$HaveRs?$rs['VAC_YEAR']:date('Y')?>" name="vac_year" id="txt_vac_year" class="form-control" />
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

                <div class="form-group col-sm-2">
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

                <div class="form-group col-sm-7">
                    <label class="control-label">ملاحظات</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['NOTES']:""?>" name="notes" id="txt_notes" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-7">
                    <label class="control-label">سبب الرفض</label>
                    <div>
                        <input readonly type="text" value="<?=$HaveRs?$rs['REFUSAL_NOTE']:""?>" name="refusal_note" id="txt_refusal_note" class="form-control" />
                    </div>
                </div>

                </div>


                <div class="col-md-3" id="div_emp_balance">
                </div>

            </div>


            <div class="modal-footer">

                <?php if ( HaveAccess($post_url) && $page_act!='hr_admin' && ($isCreate || ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

                <?php if ( HaveAccess($post_url) && $page_act=='move_admin' && !$isCreate && $rs['ADOPT']==30 ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ التعديل</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'10') and !$isCreate and $rs['ADOPT']==1 and in_array($rs['VAC_TYPE'], $vac_arr) ) : /* OLD -  $rs['VAC_TYPE']!=3  */ ?>
                    <button type="button" id="btn_adopt_10" onclick='javascript:adopt_(10);' class="btn btn-success">اعتماد المدخل</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'20') and !$isCreate and $rs['ADOPT']==10 && $page_act=='manager' and in_array($rs['VAC_TYPE'], $vac_arr) ) : /* OLD -  $rs['VAC_TYPE']!=3  */ ?>
                    <button type="button" onclick='javascript:adopt_(20);' class="btn btn-success">اعتماد المدير</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'30') and !$isCreate and $rs['ADOPT']==20 and in_array($rs['VAC_TYPE'], $vac_arr) ) : /* OLD -  $rs['VAC_TYPE']!=3  */ ?>
                    <button type="button" onclick='javascript:adopt_(30);' class="btn btn-warning"> عودة</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'40') and !$isCreate and $rs['ADOPT']<40 and (($rs['ADOPT']==1 and !in_array($rs['VAC_TYPE'], $vac_arr)) or (in_array($rs['VAC_TYPE'], $vac_arr) and $rs['ADOPT']==30)) ) : /* OLD - ($rs['ADOPT']==30 or ($rs['VAC_TYPE']==3 and $rs['ADOPT']==1) ) */ ?>
                    <button type="button" onclick='javascript:adopt_(40);' class="btn btn-success">اعتماد الشؤون الادارية </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'0') and !$isCreate and $rs['ADOPT']==1 and $rs['ENTRY_USER']==$this->user->id ) : ?>
                    <button type="button" onclick='javascript:adopt_(0);' class="btn btn-danger">الغاء الاجازة </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'_10') and !$isCreate and $rs['ADOPT']==10 and $rs['ENTRY_USER']==$this->user->id ) : ?>
                    <button type="button" onclick='javascript:adopt_("_10");' class="btn btn-danger">الغاء الاعتماد </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'_40') and !$isCreate and $rs['ADOPT']==40 ) : ?>
                    <button type="button" onclick='javascript:adopt_("_40");' class="btn btn-danger">الغاء الاعتماد </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'_20') and !$isCreate and $rs['ADOPT']==20 && $page_act=='manager' ) : ?>
                    <button type="button" onclick='javascript:adopt_("_20");' class="btn btn-danger">الغاء الاجازة </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'_30') and !$isCreate and ($rs['ADOPT']==30 or $rs['ADOPT']==10) && ($page_act=='move_admin' or $page_act=='hr_admin') ) : ?>
                    <button type="button" onclick='javascript:adopt_("_30");' class="btn btn-danger">الغاء الاجازة نهائيا</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'_1') and !$isCreate and $rs['ADOPT']==10 && $page_act=='manager' ) : ?>
                    <button type="button" onclick='javascript:adopt_("_1");' class="btn btn-warning">رفض الاجازة </button>
                <?php endif; ?>

                <?php if ( !$isCreate ) : ?>
                    <span><?php echo modules::run('attachments/attachment/index',$rs['SER'],'hr_vacation_request'); ?></span>
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
    $('#txt_ret_date').prop('readonly',0);
}

if('{$page_act}'=='manager'){
    $('#txt_refusal_note').prop('readonly',0);
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


$('#txt_vac_date').change(function(){
    var day_ = $('#txt_vac_date').val();
    var year_ = day_.substring(day_.length - 4, day_.length);
    if(year_ > 2000 && year_ < 3000 && $('#txt_vac_year').val() != year_ ){
        $('#txt_vac_year').val(year_);
        get_balance();
    }
});


function get_balance(){
    $('#div_emp_balance').text('');
    emp_no= $('#dp_emp_no').select2('val');
    vac_year= $('#txt_vac_year').val();
    if(emp_no!=''){
        get_data('{$get_balance_url}', {emp_no:emp_no, vac_year:vac_year}, function(data){
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

    if({$rs['ADOPT']}==1 && {$rs['VAC_TYPE']} <= 2 ){ // OR VAC_TYPE= 17
        warning_msg('تنويه','الاجازة بحاجة لاعتماد الادخال..');
    }else if({$rs['ADOPT']}==10 && {$rs['VAC_TYPE']} <= 2 ){ // OR VAC_TYPE= 17
        warning_msg('تنويه','الاجازة بحاجة لاعتماد المدير..');
    }
    
    var btn__= '';
    $('#btn_adopt_10').click( function(){
        btn__ = $(this);
    });

    function adopt_(no){
        var msg= 'هل تريد الاعتماد ؟!';
        if(no=='_10') msg= 'هل تريد بالتأكيد الغاء اعتمادك؟';
        if(no=='_40') msg= 'هل تريد بالتأكيد الغاء اعتماد الشؤون الادارية؟';
        if(no==0 || no=='_20' || no=='_30') msg= 'هل تريد بالتأكيد الغاء الاجازة بشكل نهائي؟!';
        if(no=='_1') msg= 'هل تريد بالتأكيد رفض الاجازة؟ يمكنك ادخال سبب الرفض..';
        if(no==40) msg= 'هل تريد بالتأكيد اعتماد الاجازة بشكل نهائي؟ لا يمكن التراجع عن هذه العملية!';
        if(confirm(msg)){
            var values= {ser: "{$rs['SER']}" , refusal_note: $('#txt_refusal_note').val() };
            get_data('{$adopt_url}'+no, values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تمت العملية بنجاح..');
                    $('button').attr('disabled','disabled');

                    if( no==10 && '{$rs['MANAGER_EMAIL']}' != ''){
                        var sub= 'يرجى اعتماد الاجازة {$rs['SER']}';
                        var text= 'يرجى اعتماد الاجازة الخاصة بالموظف {$rs['EMP_NO_NAME']}';
                        text+= '<br>للاطلاع افتح الرابط';
                        text+= ' <br>{$gfc_domain}{$manager_url} ';
                        _send_mail(btn__,'{$rs['MANAGER_EMAIL']}',sub,text);
                        btn__ = '';
                    }

                    setTimeout(function(){
                        get_to_link(window.location.href);
                    },1000);
                }else{
                    danger_msg('تحذير..',ret);
                }
            }, 'html');
        }
    }


    </script>
SCRIPT;
}

sec_scripts($scripts);

?>
