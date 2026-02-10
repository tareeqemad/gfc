<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 02/11/14
 * Time: 08:42 ص
 */
$back_url = base_url('financial/financial_mqasah/' . $action . '?type=12');
$get_balance_url = base_url('financial/financial_mqasah/public_get_balance');
$adopt_url = base_url('financial/financial_mqasah/adopt?type=12');
$review_url = base_url('financial/financial_mqasah/review?type=12');
$delete_details_url = base_url('financial/financial_mqasah/delete?type=12');
$notes_url = base_url('settings/notes/public_create');
$public_return_url = base_url('financial/financial_mqasah/public_return');
$print_url = base_url('/reports');

$isCreate = isset($chains_data) && count($chains_data) > 0 ? false : true;

$post_url = $isCreate ? 'financial/financial_mqasah/create?type=12' : 'financial/financial_mqasah/edit?type=12';
$rs = $isCreate ? array() : $chains_data[0];
$isReview = $action == 'review' ? (count($rs) > 0 && $rs['FINANCIAL_CHAINS_CASE'] >= 2) ? true : false : false;
$isAdopt = $action == 'adopt' ? (count($rs) > 0 && $rs['FINANCIAL_CHAINS_CASE'] >= 1 && $rs['FINANCIAL_CHAINS_CASE'] <= 2) ? true : false : false;
$post_url = $action == 'review' || $action == 'adopt' ? '' : $post_url;
$get_id_url = base_url('financial/accounts/public_get_id');

$case = count($rs) > 0 ? $rs['FINANCIAL_CHAINS_CASE'] : -1;


?>
<?= AntiForgeryToken() ?>
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if (HaveAccess($back_url)): ?>
                <li><a href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php endif; ?>

            <li><a onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a>
            </li>

        </ul>

    </div>

    <div class="form-body">

        <?php if (count($rs) > 0 && $rs['FINANCIAL_CHAINS_CASE'] == 0): ?>
            <span class="canceled">
السند ملغي
                </span>
        <?php endif; ?>

        <div id="msg_container"></div>
        <div id="container">
            <form class="form-vertical" id="chains_from" method="post" action="<?= base_url($post_url) ?>" role="form"
                  novalidate="novalidate">
                <div class="modal-body inline_form">
                    <div class="form-group col-sm-2">
                        <label class="control-label">الرقم</label>

                        <div class="">
                            <input type="text" name="financial_chains_id" readonly id="txt_financial_chains_id"
                                   class="form-control ">
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">نوع المقاصة </label>

                        <div class="">

                            <select name="mqasah_type" id="dp_mqasah_type" data-curr="false" class="form-control">
                                <?php foreach ($mqasah_type as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label"> تاريخ السند</label>

                        <div class="">
                            <input type="text" name="financial_chains_date" data-type="date"
                                   data-date-format="DD/MM/YYYY" value="<?= date('d/m/Y') ?>"
                                   id="txt_financial_chains_date" class="form-control ">
                        </div>
                    </div>
                    <div class="form-group col-sm-2" style="display: none;">
                        <label class="control-label">رقم سند المصدر </label>

                        <div class="">
                            <input type="text" name="payment_id" data-val-regex="المدخل غير صحيح!"
                                   data-val-regex-pattern="\d{1,}" id="txt_payment_id" class="form-control ">


                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">العملة </label>

                        <div class="">

                            <select name="curr_id" id="dp_curr_id" data-curr="false" class="form-control">
                                <?php foreach ($currency as $row) : ?>
                                    <option data-val="<?= $row['VAL'] ?>"
                                            value="<?= $row['CURR_ID'] ?>"><?= $row['CURR_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label"> سعر العملة</label>

                        <div class="">
                            <input type="text" name="curr_value"  <?= get_curr_user()->branch == 1 ? '' : 'readonly' ?>
                                   data-val-regex="المدخل غير صحيح!" data-val-regex-pattern="<?= float_format_exp() ?>"
                                   data-val="true" data-val-required="حقل مطلوب" id="txt_curr_value"
                                   class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="curr_value"
                                  data-valmsg-replace="true"></span>

                        </div>
                    </div>

                    <?php if (count($rs) > 0 && $rs['QEED_NUM'] != '' && $rs['QEED_NUM'] > 0): ?>

                        <div class="form-group col-sm-2" style="padding-top: 42px;padding-right: 30px;">

                            <div class="">
                                <a id="source_url"
                                   href="<?= base_url("financial/financial_chains/get/{$rs['QEED_NUM']}/index?type=4") ?>"
                                   class="btn-xs btn-success" target="_blank"> عرض القيد</a>
                            </div>
                        </div>

                    <?php endif; ?>

                    <div class="form-group col-sm-2" style="display: none;">
                        <label class="control-label"> رقم الشيك</label>

                        <div class="">
                            <input type="text" name="check_id" data-val-regex="المدخل غير صحيح!"
                                   data-val-regex-pattern="\d{1,}" id="txt_check_id" class="form-control ">

                        </div>
                    </div>


                    <div class="form-group col-sm-12">
                        <label class="control-label">البيان </label>

                        <div class="">

                            <textarea type="text" name="hints" data-val="true" rows="5" data-val-required="حقل مطلوب"
                                      id="txt_hints" class="form-control "></textarea>


                            <span class="field-validation-valid" data-valmsg-for="hints"
                                  data-valmsg-replace="true"></span>

                        </div>
                    </div>

                    <div class="form-group col-sm-12">
                        <label class="control-label">الملاحظات </label>

                        <div class="">

                            <textarea type="text" name="notes" rows="5" id="txt_notes" class="form-control "></textarea>


                        </div>
                    </div>
                    <hr/>
                    <br>

                    <div style="clear: both;">
                        <!--   --><?php /*echo modules::run('financial/financial_mqasah/public_get_details',(count($rs))?$rs['FINANCIAL_CHAINS_ID']:0,(isset($can_edit)?$can_edit:false)); */ ?>
                        <?php echo modules::run((isset($fin_copy_id) ? 'financial/financial_mqasah/public_get_details_copy' : 'financial/financial_mqasah/public_get_details'), (count($rs)) ? $rs['FINANCIAL_CHAINS_ID'] : (isset($fin_copy_id) ? $fin_copy_id : 0), isset($can_edit) ? $can_edit : 0); ?>

                    </div>
                    <hr/>
                    <div style="clear: both;">

                        <?php echo modules::run('settings/notes/public_get_page', (count($rs)) ? $rs['FINANCIAL_CHAINS_ID'] : 0, 'mqasah'); ?>
                        <?php echo (count($rs)) ? modules::run('attachments/attachment/index', $rs['FINANCIAL_CHAINS_ID'], 'mqasah') : ''; ?>

                    </div>
                    <hr/>
                    <div class="modal-footer">
                        <?php if (($isCreate || (isset($can_edit) ? $can_edit : false))) : ?>
                            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                        <?php endif; ?>

                        <?php if ($isReview && $case < 3) : ?>
                            <button type="button" onclick="javascript:return_mqasah(1);" class="btn btn-primary">إعتماد
                                الرقابة
                            </button>
                        <?php endif; ?>

                        <?php if ($isAdopt && $case < 2) : ?>
                            <button type="button" onclick="javascript:return_mqasah(1);" class="btn btn-primary">
                                إعتماد
                            </button>
                        <?php endif; ?>

                        <?php if ((($isReview && $case < 3) || ($isAdopt && $case < 2))) : ?>
                            <button type="button" onclick="javascript:return_mqasah(0);" class="btn btn-danger">إرجاع
                            </button>
                        <?php endif; ?>

                        <?php if ($isCreate): ?>
                            <button type="button" onclick="javascript:clear_form();" class="btn btn-default"> تفريغ
                                الحقول
                            </button>
                        <?php endif; ?>

                        <?php if ($case >= 3): ?>
                            <button type="button" style="display:none;" id="print_chain"
                                    onclick="javascript:print_chain();" class="btn btn-success"> طباعة
                            </button>
                        <?php endif; ?>

                    </div>
                </div>
            </form>

        </div>

    </div>

    </div>

    <div class="modal fade" id="notesModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span
                            aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"> ملاحظات</h4>
                </div>
                <div id="msg_container_alt"></div>

                <div class="form-group col-sm-12">

                    <div class="">
                        <textarea type="text" data-val="true" rows="5" id="txt_g_notes"
                                  class="form-control "></textarea>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="javascript:apply_action();" class="btn btn-primary">حفظ البيانات
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div>
        <?php if (count($rs)): ?>
            <table class="table info">
                <thead>
                <tr>
                    <th>المدخل</th>

                    <th>اعتماد المدير المالي</th>
                    <th>اعتماد الرقابة</th>
                </tr>
                </thead>
                <tbody>

                <tr>
                    <td><?= $rs['ENTRY_USER_NAME'] ?></td>
                    <td><?= $rs['TRANS_USER_NAME'] ?></td>

                    <td><?= $rs['AUDIT_USER_NAME'] ?></td>

                </tr>

                <tr>
                    <td><?= $rs['FINANCIAL_CHAINS_DATE'] ?></td>
                    <td><?= $rs['TRANS_DATE'] ?></td>

                    <td><?= $rs['AUDIT_DATE'] ?></td>

                </tr>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

<?php

$today = date('d/m/Y');
$exp = float_format_exp();
$select_accounts_url = base_url('financial/accounts/public_select_accounts');
$customer_url = base_url('payment/customers/public_index');
$project_accounts_url = base_url('projects/projects/public_select_project_accounts');

$reset_form = $isCreate ? 'true' : 'false';
$rs = preg_replace("/(\r\n|\n|\r)/", "\\n", $rs);


$SharedScripts = <<<SCRIPT
 function CalcVBalance(OBj){
            var name = $(OBj).attr('name');
            var val = parseFloat($(OBj).val());
            var curr= $('#txt_curr_value').val();

            var obj ;
            if(name == 'debit[]'){
                obj=   $(OBj).closest('tr').find('input[name="credit[]"]');
            }else {
                obj=  $(OBj).closest('tr').find('input[name="debit[]"]');
            }

            if(val > 0)
                $(obj).val(0);
            else
                val = parseFloat($(obj).val());

            var v_balance =   $(obj).closest('tr').find('.v_balance');
            $(v_balance).text((val*curr).toFixed(2));
    }

    function ReCalcVBalance(){
        $('input[name="debit[]"]').each(function(i){
            CalcVBalance($(this));
        });
    }


     function sumOfValues(){
        var sumD= 0 ;
        $('input[name="debit[]"]').each(function(i) {



            sumD += Number($(this).val());
        });


        var sumC= 0 ;
        $('input[name="credit[]"]').each(function(i) {



            sumC += Number($(this).val());
        });

        $('#debit_val').text(sumD.toFixed(2));
        $('#credit_val').text(sumC.toFixed(2));


    }

    function saveData(){
    
        var totalSelectedBnkFin = $('select[name="bk_fin_id[]"]:visible').filter(function( index ) {
                                                                        return $(this).val() != '';
                                                                  }).length;
        var totalBnkFin = $('select[name="bk_fin_id[]"]:visible').length;;
    
        if(totalSelectedBnkFin != totalBnkFin){
             return alert('يجب تحديد المقبوضات و المدفوعات');
        }
        
         $('select[name="bk_fin_id[]"]').each(function(i){
            $(this).prop('name','bk_fin_id'+i);
        });
        
        
      var form = $('#chains_from');
            ajax_insert_update(form,function(data){

                if($reset_form)
                    clear_form();
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                setTimeout(function(){

                   window.location.reload();

                }, 1000);

            },'html');
    }



    function reBind(){


        delete_action();
        Up_Down();


        $('input[name="account_id_name[]"]').click(function(e){
             selectAccount($(this));
        });

        $('input[name="credit[]"],input[name="debit[]"]').bind("keyup",function(e){

            CalcVBalance($(this));

            sumOfValues();

        });

        $('select[name="account_type_name[]"],select[name="account_type[]"]').change(function(){

            $(this).closest('tr').find('input[name="account_id_name[]"]').val('');
            $(this).closest('tr').find('input[name="account_id[]"]').val('');

            if($(this).val() == 2 ){
                $('select[name="customer_account_type[]"]',$(this).closest('tr')).show();
            } else {
                $('select[name="customer_account_type[]"]',$(this).closest('tr')).hide();
            }

            $(this).closest('tr').find('input[name="account_type[]"]').val($(this).val());


        });

        $('input[name="account_id[]"]').keyup(function(){
            get_account_name($(this));
        });
        $('input[name="account_id[]"]').change(function(){
            get_account_name($(this));
        });


        $('#txt_hints').change(function(){


            $('#chains_detailsTbl input[name="dhints[]"]').each(function(i) {
                console.log('',$(this).val());
                if($(this).val() == ''){
                    $(this).val( $('#txt_hints').val());
                }
            });



        });

          $('input[name="account_id[]"]').bind('keyup', '+', function(e) {

                $(this).val( $(this).val().replace('+', ''));

                 selectAccount($(this).closest('tr').find('input[name$="_name[]"]'));
           });

        $('input[name="debit[]"],input[name="credit[]"]').bind('keyup', 'space', function(e) {

            $(this).val( $(this).val().trim());
            var sum = 0;
            var curr= $('#txt_curr_value').val();
            var index= $('input[name="debit[]"]').index($(this));

            var credit = $('input[name="credit[]"]').get(index);
            var debit = $('input[name="debit[]"]').get(index);



            $('input[name="debit[]"]').each(function(i) {

                var obj = $('input[name="credit[]"]').get(i);
                if(i != index)
                    sum += Number($(this).val()) - Number($(obj).val());
            });

            if(sum > 0){
                $(credit).val(sum.toFixed(2));
                $(debit).val(0);
            }
            else{
                sum = sum * -1;
                $(debit).val(sum.toFixed(2));
                $(credit).val(0);
            }
            var v_balance =   $(debit).closest('tr').find('.v_balance');
            $(v_balance).text((sum*curr).toFixed(2));

            sumOfValues();

            return false;
        });

    }


SCRIPT;

$scripts = <<<SCRIPT

<script>

    var count = 0;
    var aTypes = $('#dp_account_type_0').html();
    $(function(){

        reBind();
        setCurrValue();
        $('#dp_curr_id').change(function(){

            setCurrValue();
            ReCalcVBalance();
        });

        count = $('input[name="account_id[]"]').length;

    });

    function setCurrValue(){
        $('#txt_curr_value').val($('#dp_curr_id').find(':selected').attr('data-val'));
    }


    function reBindAfterInsert(tr){
        $('select[name="customer_account_type[]"]',$(tr)).hide();
        $('select[name="account_type_name[]"]',$(tr)).prop('disabled',false);

    }

    function get_account_name(obj){
        $(obj).closest('tr').find('input[name$="_name[]"]').val('');
        var _type = $(obj).closest('tr').find('select').val();

        if(_type == 1)
            if($(obj).val().length >6  || $(obj).val().match("^60")){
                get_data('{$get_id_url}',{id:$(obj).val()},function(data){

                    if(data.length > 0){

                        var  tr = $(obj).closest('tr');
                        $(tr).find('input[name$="_name[]"]').val(data[0].ACOUNT_NAME+' ('+data[0].CURR_NAME+')');

                        if(data[0].ACOUNT_CASH_FLOW == 5) {
                            $('select[name="bk_fin_id[]"]',$(tr)).show();
                        } else {
                           $('select[name="bk_fin_id[]"]',$(tr)).val(0);
				           $('select[name="bk_fin_id[]"]',$(tr)).find(":selected").removeProp('selected');
                           $('select[name="bk_fin_id[]"]',$(tr)).hide(); 
                        }
                    }
                });
            }
    }


    function update_balance(obj){

        get_data('{$get_balance_url}',{id: $(obj).val()},function(data){
            $(obj).closest('tr').find('.balance').text(data);
        },'html');
    }



        {$SharedScripts}
    function selectAccount(obj){
         var _type = $(obj).closest('tr').find('select').val();

            if(_type == 1)
                _showReport('$select_accounts_url/'+$(obj).attr('id') );
            if(_type == 2)
                _showReport('$customer_url/'+$(obj).attr('id')+'/1');
            if(_type == 3)
                _showReport('$project_accounts_url/'+$(obj).attr('id'));
    }

    function clear_form(){
        clearForm($('#chains_from'));

        $('#txt_financial_chains_date').val('$today');
        $('.v_balance,.balance,#debit_val,#credit_val').text('');
        $('#print_chain').hide();

    }

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();


    if (typeof return_mqasah == 'function') {

        return_mqasah(-1);
    }else {
        saveData();
    }

        /*if(confirm('هل تريد حفظ السند ؟!')){

        }*/
    });

    function financial_chains_detail_tb_delete(td,id){
        if(confirm('هل تريد حذف السند؟!')){
            ajax_delete_any('$delete_details_url',{id:id},function(data){
                if(data == '1'){
                    $(td).closest('tr').remove();
                    success_msg('رسالة','تم حذف السند بنجاح ..');
                }
            });
        }
    }



</script>


SCRIPT;


if ($isCreate)
    sec_scripts($scripts);

else {


    $edit_script = <<<SCRIPT

<script>
    {$SharedScripts}



    var action_type;

    $('#txt_financial_chains_id').val('{$rs['FINANCIAL_CHAINS_ID']}');
    $('#txt_financial_chains_date').val('{$rs['FINANCIAL_CHAINS_DATE']}');
    $('#txt_payment_id').val('{$rs['PAYMENT_ID']}');
    $('#dp_curr_id').val('{$rs['CURR_ID']}');
    $('#txt_curr_value').val('{$rs['CURR_VALUE']}');
    $('#txt_check_id').val('{$rs['CHECK_ID']}');
    $('#txt_hints').val('{$rs['HINTS']}');
    $('#txt_notes').val('{$rs['NOTES']}');
    $('#dp_mqasah_type').val('{$rs['MQASAH_TYPE']}');

    $('#dp_curr_id').prop('disabled',true);
    $('#dp_curr_id').closest('div').append('<input type="hidden" name="curr_id" value="{$rs['CURR_ID']}" >');

    $('#print_chain,#source_url').show();
    $('#print_chain').click(function(){

      _showReport('$print_url?report=MAQASAH_MAIN&params[]={$rs['FINANCIAL_CHAINS_ID']}&params[]={$rs['BRANCHES']}');

    });
    function adopt_chains(){
        get_data('{$adopt_url}',{financial_chains_id:{$rs['FINANCIAL_CHAINS_ID']},hints:$('#txt_hints').val()},function(data){
            if(data =='1')
       success_msg('رسالة','تم إعتماد السند بنجاح ..');
       reloadPage();
        },'html');
    }

    function review_chains(){
     get_data('{$review_url}',{financial_chains_id:{$rs['FINANCIAL_CHAINS_ID']} ,hints:$('#txt_hints').val()},function(data){
            if(data =='1')
               success_msg('رسالة','تم إعتماد تدقيق السند بنجاح ..');
reloadPage();
        },'html');
    }

    function return_mqasah(type){

        if(type == 1 && ! confirm('هل تريد إعتماد السند ؟!')){
         return;
        }
        action_type = type;

        $('#notesModal').modal();

    }

    function reloadPage(){
      setTimeout(function(){

                   window.location.reload();

                }, 1000);
    }

    function apply_action(){

        if(action_type == -1){
            saveData();
        }else if(action_type == 1){
                if({$case} == 2){
                    review_chains();
                } else if({$case} == 1){
                    adopt_chains();
                }
        }
        else{

            if($('#txt_g_notes').val() =='' ){
                alert('تحذير : لم تذكر سبب الإرجاع ؟!!');
                return;
            }
         get_data('{$public_return_url}',{financial_chains_id:{$rs['FINANCIAL_CHAINS_ID']},hints:$('#txt_hints').val()},function(data){
            if(data =='1')
              success_msg('رسالة','تم  إرجاع السند بنجاح ..');
        },'html');
        }

         get_data('{$notes_url}',{source_id:{$rs['FINANCIAL_CHAINS_ID']},source:'mqasah',notes:$('#txt_g_notes').val()},function(data){
            $('#txt_g_notes').val('');
        },'html');

         $('#notesModal').modal('hide');
         //reloadPage();
    }


ReCalcVBalance();
sumOfValues();
</script>
SCRIPT;
    if (isset($can_edit) ? $can_edit : false)
        $edit_script = $edit_script . $scripts;


    sec_scripts($edit_script);


}


?>