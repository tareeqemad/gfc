<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 09/08/17
 * Time: 12:52 م
 */

$MODULE_NAME= 'rental';
$TB_NAME= 'monthly_cpayments';
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");
$cancel_url= base_url("$MODULE_NAME/$TB_NAME/cancel_");


$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];

$report_url = base_url("JsperReport/showreport?sys=financial/rental");

$date_attr= " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";

$def_payroll_date= '01/'.date('m/Y');
//$def_date_from= '25/'.(date('m')-1).date('/Y');
$def_date_from= date('25/m/Y', strtotime(date('Y-m')." -1 month"));
$def_date_to= '24/'.date('m/Y');
$readonly="readonly";
if($HaveRs)
{
    if($rs['ADOPT']==4 and HaveAccess($cancel_url.'_4'))
        $readonly="";
}

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
                    <label class="control-label">رقم المطالبة الشهرية</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['MONTHLY_CPAYMENTS_ID']:""?>" name="monthly_cpayments_id" id="txt_monthly_cpayments_id" class="form-control" />
                        <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                            <input type="hidden" value="<?=$HaveRs?$rs['MONTHLY_CPAYMENTS_ID']:''?>" name="monthly_cpayments_id" id="h_monthly_cpayments_id" />
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">الفرع </label>
                    <div>
                        <select name="branch_id" id="dp_branch_id" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($branches as $row) :?>
                                <option <?=$HaveRs?($rs['BRANCH_ID']==$row['NO']?'selected':''):''?> value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label"> الشهر</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['PAYROLL_DATE']:$def_payroll_date?>" name="payroll_date" id="txt_payroll_date" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">الفترة من</label>
                    <div>
                        <input type="text" <?=$date_attr?> value="<?=$HaveRs?$rs['DATE_FROM']:$def_date_from?>" name="date_from" id="txt_date_from" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">الى</label>
                    <div>
                        <input type="text" <?=$date_attr?> value="<?=$HaveRs?$rs['DATE_TO']:$def_date_to?>" name="date_to" id="txt_date_to" class="form-control" />
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
                <div class="form-group col-sm-7">
                    <label class="control-label">ملاحظات سبب ارجاع الرقابة</label>
                    <div>
                        <input type="text" <?=$readonly?> <?=$HaveRs?$rs['NOTE_4']:""?>  value="<?=$HaveRs?$rs['NOTE_4']:""?>" name="note_4" id="txt_note_4" class="form-control" />

                    </div>
                </div>

            </div>


            <div class="modal-footer">

                <?php if ( HaveAccess($post_url) && ($isCreate || ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'2') and !$isCreate and $rs['ADOPT']==1 ) : ?>
                    <button type="button" onclick='javascript:adopt_(2);' class="btn btn-success">اعتماد الخدمات</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'3') and !$isCreate and $rs['ADOPT']==2 ) : ?>
                    <button type="button" onclick='javascript:adopt_(3);' class="btn btn-success">اعتماد المقر</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'4') and !$isCreate and $rs['ADOPT']==3 ) : ?>
                    <button type="button" onclick='javascript:adopt_(4);' class="btn btn-success"> اعتماد المالية</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'5') and !$isCreate and $rs['ADOPT']==4 ) : ?>
                    <button type="button" onclick='javascript:adopt_(5);' class="btn btn-success">اعتماد التدقيق </button>
                <?php endif; ?>

                <?php if ( HaveAccess($cancel_url.'2') and !$isCreate and $rs['ADOPT']==2 ) : ?>
                    <button type="button" onclick='javascript:cancel_(2);' class="btn btn-danger">الغاء اعتماد الخدمات </button>
                <?php endif; ?>

                <?php if ( HaveAccess($cancel_url.'3') and !$isCreate and $rs['ADOPT']==3 ) : ?>
                    <button type="button" onclick='javascript:cancel_(3);' class="btn btn-danger">الغاء اعتماد المقر </button>
                <?php endif; ?>

                <?php if ( HaveAccess($cancel_url.'4') and !$isCreate and $rs['ADOPT']==4 ) : ?>
                    <button type="button" onclick='javascript:cancel_(4);' class="btn btn-danger">الغاء اعتماد المالية </button>
                <?php endif; ?>

                <?php if ( HaveAccess($cancel_url.'_4') and !$isCreate and $rs['ADOPT']==4 ) : ?>
                    <button type="button" onclick='javascript:cancel_("_4");' class="btn btn-danger">إرجاع الرقابة</button>
                <?php endif; ?>

                <?php if ( !$isCreate and $rs['ADOPT'] > 1 ) : ?>
                    <button type="button" onclick="javascript:print_report();" class="btn btn-default"><span class="glyphicon glyphicon-print"></span>تقرير الاجور </button>
                <?php endif; ?>

            </div>

        </form>
    </div>
</div>


<?php
$scripts = <<<SCRIPT
<script>

$(function(){

    change_date_format( $('#txt_payroll_date') , 'DEL');

    $('#txt_payroll_date').datetimepicker({
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

$('button[data-action="submit"]').click(function(e){
    e.preventDefault();
    var msg= 'هل تريد حفظ الطلب ؟!';
    if(confirm(msg)){
        $(this).attr('disabled','disabled');
        change_date_format( $('#txt_payroll_date') , 'ADD');

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
            var values= {monthly_cpayments_id: "{$rs['MONTHLY_CPAYMENTS_ID']}" };
            get_data('{$adopt_url}'+no, values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تم اعتماد الطلب بنجاح ..');
                    $('button').attr('disabled','disabled');
                    setTimeout(function(){
                        get_to_link(window.location.href);
                    },1000);
                }else{
                    danger_msg('تحذير..',ret);
                }
            }, 'html');
        }
    }

    function cancel_(no){
        if(no=="4")
        {
            return 0;
        }
        var msg= 'هل تريد بالتأكيد الغاء الاعتماد ؟!';
        if(no=="_4"){
            var note_4= $('#txt_note_4').val();
            if (note_4==''){
                danger_msg('يجب إدخال سبب ارجاع الرقابة','');
                return 0;
            }
        }
        if(confirm(msg)){
            var values= {monthly_cpayments_id: "{$rs['MONTHLY_CPAYMENTS_ID']}" , NOTE_4: note_4};
            get_data('{$cancel_url}'+no, values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تم الغاء الاعتماد بنجاح ..');
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

    function print_report(){
        var rep_url = '{$report_url}&report_type='+'pdf'+'&report=rented_car_hire&p_monthly_cpayments_id='+{$rs['MONTHLY_CPAYMENTS_ID']}+'&p_rep_type='+'pdf'+'';
        _showReport(rep_url);
    }

    </script>
SCRIPT;
}

sec_scripts($scripts);

?>

