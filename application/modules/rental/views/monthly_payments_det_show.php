<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 10/08/17
 * Time: 09:57 ص
 */

$MODULE_NAME= 'rental';
$TB_NAME= 'monthly_payments_det';
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");

$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];

$adopt= $HaveRs?$rs['ADOPT']:0;

if($adopt==2){
    $action='edit_branch';
}elseif($adopt==3){
    $action='edit_finance';
}else{
    $action='NoPost';
}

$post_url= base_url("$MODULE_NAME/$TB_NAME/$action");

if( HaveAccess($post_url) && $can_edit && ( $adopt==2 || $adopt==3 )  ){
    $can_update=1;
}else{
    $can_update=0;
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
                    <label class="control-label">رقم السند</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['SER']:''?>" id="txt_ser" class="form-control" />
                        <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                            <input type="hidden" value="<?=$HaveRs?$rs['SER']:''?>" name="ser" id="h_ser" />
                            <input type="hidden" value="<?=$adopt?>" name="adopt" id="h_adopt" />
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم المطالبة الشهرية</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['MONTHLY_CPAYMENTS_ID']:""?>" name="monthly_cpayments_id" id="txt_monthly_cpayments_id" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم سند ملف التعاقد</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['CONTRACTOR_FILE_ID']:""?>" name="contractor_file_id" id="txt_contractor_file_id" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم هوية المتعاقد</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['CONTRACTOR_ID']:""?>" name="contractor_id" id="txt_contractor_id" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">اسم المتعاقد</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['CONTRACTOR_NAME']:""?>" name="contractor_name" id="txt_contractor_name" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم السيارة</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['CAR_NUM']:""?>" name="car_num" id="txt_car_num" class="form-control" />
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الأجرة اليومية الصباحية</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['CAR_DAILY_COST']:""?>" name="car_daily_cost" id="txt_car_daily_cost" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الأجرة اليومية المسائية</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['EVCAR_DAILY_COST']:""?>" name="evcar_daily_cost" id="txt_evcar_daily_cost" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">قيمة الساعة الإضافية</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['CAR_HOVERTIME_COST']:""?>" name="car_hovertime_cost" id="txt_car_hovertime_cost" class="form-control" />
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-2">
                    <label class="control-label">عدد أيام العمل الصباحية</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['WORKDATE_COUNT']:""?>" name="workdate_count" id="txt_workdate_count" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">عدد أيام العمل المسائية</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['EVWORKDATE_COUNT']:""?>" name="evworkdate_count" id="txt_evworkdate_count" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">عدد الساعات الإضافية</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['CAR_HOVERTIME_COUNT']:""?>" name="car_hovertime_count" id="txt_car_hovertime_count" class="form-control" />
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-2">
                    <label class="control-label">عدد أيام العمل الصباحية المعتمدة من المقر</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['WORKDATE_BRANCH']:""?>" name="workdate_branch" id="txt_workdate_branch" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">عدد أيام العمل المسائية المعتمدة من المقر</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['EVWORKDATE_BRANCH']:""?>" name="evworkdate_branch" id="txt_evworkdate_branch" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الساعات الإضافية المعتمدة من المقر</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['OVERTIME_BRANCH']:""?>" name="overtime_branch" id="txt_overtime_branch" class="form-control" />
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-2">
                    <label class="control-label">عدد أيام العمل الصباحية المعتمدة من المالية</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['WORKDATE_FINANCE']:""?>" name="workdate_finance" id="txt_workdate_finance" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">عدد أيام العمل المسائية المعتمدة من المالية</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['EVWORKDATE_FINANCE']:""?>" name="evworkdate_finance" id="txt_evworkdate_finance" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الساعات الإضافية المعتمدة من المالية</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['OVERTIME_FINANCE']:""?>" name="overtime_finance" id="txt_overtime_finance" class="form-control" />
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-2">
                    <label class="control-label">صافي الراتب</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['NET_SALARY']:""?>" name="net_salary" id="txt_net_salary" class="form-control" />
                    </div>
                </div>

                <div style="clear: both"></div>

                <?php echo modules::run("$MODULE_NAME/$TB_NAME/public_get_details", ($HaveRs)?$rs['SER']:0 ); ?>

            </div>

            <div class="modal-footer">

                <?php if ($can_update) : ?>
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
    var msg= 'هل تريد حفظ التعديل ؟!';
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

    if({$can_update}){
        if({$adopt}==2){
            $('#txt_workdate_branch,#txt_evworkdate_branch,#txt_overtime_branch').prop('readonly',0);
        }else if({$adopt}==3){
            $('#txt_workdate_finance,#txt_evworkdate_finance,#txt_overtime_finance').prop('readonly',0);
        }
    }

    </script>
SCRIPT;
}

sec_scripts($scripts);

?>
