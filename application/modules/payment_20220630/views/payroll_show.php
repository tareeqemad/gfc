<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 06/11/14
 * Time: 09:09 ص
 */

$get_account_url = base_url('financial/accounts_permission/public_get_user_accounts');
$back_url=base_url('payment/payroll/'.$action);
$adopt_url=base_url('payment/payroll/'.$action);
$select_accounts_url =base_url('financial/accounts/public_select_accounts');
$get_id_url =base_url('financial/accounts/public_get_id');
$get_balance_url=base_url('financial/financial_chains/public_get_balance');
$print_url =  base_url('/reports');
$delete_details_url=base_url('payment/payroll/delete_details');
$get_payroll_details_url=base_url('payment/payroll/public_get_details');

$HaveRs = isset($result) && count($result) > 0;

$rs=$HaveRs ?$result[0] : array();



?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php  if( HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>
</div>
<div class="form-body">

    <div id="msg_container"></div>
    <div id="container">
        <form class="form-vertical" id="payment_from" method="post" action="<?=base_url('payment/payroll/'.($action == 'index'?'create':$action))?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم المطالبة</label>
                    <div class="">
                        <input type="text" name="payroll_payment_id" readonly   value="<?= $HaveRs?$rs['PAYROLL_PAYMENT_ID']:'' ?>"  id="txt_payroll_payment_id" class="form-control ">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> التاريخ</label>
                    <div class="">
                        <input type="text" name="payroll_payment_date" readonly  value="<?= $HaveRs?$rs['PAYROLL_PAYMENT_DATE']:date('d/m/Y') ?>" data-val-regex="المدخل غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" data-val="true" value=""  data-val-required="حقل مطلوب"  id="txt_payroll_payment_date" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="payroll_payment_date" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> عن شهر </label>
                    <div class="">
                        <input type="text" name="payroll_payment_month" <?= $HaveRs?'readonly':'' ?>    value="<?= $HaveRs?substr($rs['PAYROLL_PAYMENT_MONTH'],6).substr($rs['PAYROLL_PAYMENT_MONTH'],3,2):'' ?>"  data-val-required="حقل مطلوب"  id="txt_payroll_payment_month" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="payroll_payment_month" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">  العملة  </label>
                    <div class="">
                        <select  name="curr_id" id="dp_curr_id" <?= $HaveRs?'disabled':'' ?>  data-curr="false"  class="form-control">
                            <?php foreach($currency as $row) :?>
                                <option  data-val="<?= $row['VAL'] ?>" <?= $HaveRs && $rs['CURR_ID'] == $row['CURR_ID'] ?'selected':''   ?>  value="<?= $row['CURR_ID'] ?>"><?= $row['CURR_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label"> سعر العملة</label>
                    <div class="">
                        <input type="text" name="curr_value" readonly  data-val-regex="المدخل غير صحيح!" data-val-regex-pattern="<?= float_format_exp() ?>" data-val="true" value="<?= $HaveRs?$rs['CURR_VALUE']:'' ?>"  data-val-required="حقل مطلوب"  id="txt_curr_value" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="curr_value" data-valmsg-replace="true"></span>
                    </div>
                </div>



                <hr>
                <div class="form-group col-sm-2">
                    <label class="control-label">  تاريخ الحوالة</label>
                    <div class="">
                        <input type="text" name="transfer_date"   data-type="date"  data-date-format="DD/MM/YYYY"   data-val-regex="المدخل غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" data-val="true" value="<?= $HaveRs?$rs['TRANSFER_DATE']:'' ?>"  data-val-required="حقل مطلوب"  id="txt_transfer_date" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="transfer_date" data-valmsg-replace="true"></span>
                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label"> رقم الحوالة </label>
                    <div class="">
                        <input type="text" name="transfer_number"   data-val-regex="المدخل غير صحيح!" data-val-regex-pattern="<?= float_format_exp() ?>" data-val="true" value="<?= $HaveRs?$rs['TRANSFER_NUMBER']:'' ?>"  data-val-required="حقل مطلوب"  id="txt_transfer_number" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="transfer_number" data-valmsg-replace="true"></span>
                    </div>
                </div>
                <div class="form-group col-sm-8">
                    <label class="control-label"> بيان الحوالة </label>
                    <div class="">
                        <input type="text" name="tranasfer_hint"  data-val="true" value="<?= $HaveRs?$rs['TRANASFER_HINT']:'' ?>"  data-val-required="حقل مطلوب"  id="txt_tranasfer_hint" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="tranasfer_hint" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <br/>
                <hr/>
                <div class="btn-group">
                    <button class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
                    <ul class="dropdown-menu " role="menu">
                        <li><a href="#" onclick="$('#chains_detailsTbl').tableExport({type:'excel',escape:'false'});">  XLS</a></li>
                        <li><a href="#" onclick="$('#chains_detailsTbl').tableExport({type:'doc',escape:'false'});">  Word</a></li>
                    </ul>
                </div>
                <div style="clear: both;" id="div_details">
                    <?php echo modules::run('payment/payroll/public_get_details',$HaveRs?$rs['PAYROLL_PAYMENT_ID']:0); ?>
                </div>




            </div>

            <hr/>

            <div class="modal-footer">


               <?php if ((!$HaveRs || ( isset($can_edit)?$can_edit:false)) && $action != 'adopt') :?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
              <?php endif; ?>
                <?php if ($HaveRs &&  $rs['PAYROLL_PAYMENT_CASE'] == 1  && $action == 'adopt' ) :?>
                    <button type="submit" data-action="submit" class="btn btn-primary"> اعتماد</button>
                <?php endif; ?>

            </div>
        </form>
    </div>

</div>


<?php
$exp = float_format_exp();



$shared_js = <<<SCRIPT


    $(function(){

        curr_changed();

        $('input[type="text"],body').bind('keydown', 'f3', function() {
            payroll_get();
            return false;
        });

    });

    function payroll_get(){
        get_data('{$get_payroll_details_url}',{month: $('#txt_payroll_payment_month').val(),curr_id:$('#dp_curr_id').val()},function(data){
            $('#div_details').html(data);
        },'html');
    }

    function curr_changed(){
        $('#txt_curr_value').val($(dp_curr_id).find(':selected').attr('data-val'));
        $('#dp_curr_id').change(function(){

            $('#txt_curr_value').val($(this).find(':selected').attr('data-val'));

        });
    }


       $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد حفظ السند ؟!')){
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){

                success_msg('رسالة','تم حفظ البيانات بنجاح ..');

                reload_Page();

            },'html');
        }
    });



SCRIPT;


$scripts = <<<SCRIPT
<script>


  {$shared_js}



</script>
SCRIPT;

if(!$HaveRs)
    sec_scripts($scripts);

else{


    $edit_script = <<<SCRIPT

<script>
    {$shared_js}

</script>
SCRIPT;
    if(isset($can_edit)?$can_edit:false)
        $edit_script =$edit_script;


    sec_scripts($edit_script);


}

?>

