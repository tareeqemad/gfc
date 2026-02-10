<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 02/11/14
 * Time: 08:42 ص
 */
$back_url=base_url('financial/financial_chains/'.$action.'?type='.$type);
$get_balance_url=base_url('financial/financial_chains/public_get_balance');
$adopt_url=base_url('financial/financial_chains/adopt?type='.$type);
$review_url=base_url('financial/financial_chains/review?type='.$type);
$delete_details_url=base_url('financial/financial_chains/delete?type='.$type);
$notes_url = base_url('settings/notes/public_create');
$public_return_url =base_url('financial/financial_chains/public_return');
$print_url =  base_url('/reports');
$report_jasper_url = base_url("JsperReport/showreport?sys=financial/archives").'&';

$isCreate =isset($chains_data) && count($chains_data) > 0?false:true;

$post_url = $isCreate ?'financial/financial_chains/create?type='.$type:'financial/financial_chains/edit?type='.$type;
$rs=$isCreate ?array() : $chains_data[0];
$isReview = $action == 'review'?(count($rs) > 0 && $rs['FINANCIAL_CHAINS_CASE'] >= 2)?true:false:false;
$isAdopt = $action == 'adopt'?(count($rs) > 0 && $rs['FINANCIAL_CHAINS_CASE'] >= 1  && $rs['FINANCIAL_CHAINS_CASE'] <= 2)?true:false:false;
$post_url = $action == 'review' ||  $action == 'adopt' ? '':$post_url;


$get_id_url =base_url('financial/accounts/public_get_id');

$case = count($rs) > 0 ? $rs['FINANCIAL_CHAINS_CASE'] : -1;

$create_url=base_url('financial/financial_chains/create?type='.$type);



?>
<?= AntiForgeryToken() ?>
    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>
                <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>

                <?php  if( HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>

                <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

            </ul>

        </div>

        <div class="form-body">

            <div id="msg_container"></div>
            <div id="container">
                <form class="form-vertical" id="chains_from" method="post" action="<?=base_url($post_url)?>" role="form" novalidate="novalidate">
                    <div class="modal-body inline_form">
                        <div class="form-group col-sm-2">
                            <label class="control-label"> رقم القيد</label>
                            <div class="">
                                <input type="text" name="financial_chains_id"  readonly  id="txt_financial_chains_id" class="form-control ">
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label"> تاريخ القيد</label>
                            <div class="">
                                <input type="text" name="financial_chains_date" data-type="date" data-date-format="DD/MM/YYYY" value="<?= date('d/m/Y') ?>"  id="txt_financial_chains_date" class="form-control ">
                            </div>
                        </div>

                        <?php if(count($rs) > 0 &&  $rs['PAYMENT_ID'] != ''): ?>

                            <div class="form-group col-sm-2">
                                <label class="control-label">رقم سند المصدر </label>
                                <div class="">
                                    <input type="text" name="payment_id" readonly data-val-regex="المدخل غير صحيح!" data-val-regex-pattern="\d{1,}" id="txt_payment_id" class="form-control ">


                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="form-group col-sm-2">
                            <label class="control-label">العملة </label>
                            <div class="">

                                <select name="curr_id" id="dp_curr_id" data-curr="false" class="form-control">
                                    <?php foreach($currency as $row) :?>
                                        <option data-val="<?= count($rs) > 0 && $row['CURR_ID'] ==  $rs['CURR_ID'] ? $rs['CURR_VALUE'] : $row['VAL'] ?>" value="<?= $row['CURR_ID'] ?>"><?= $row['CURR_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label"> سعر العملة</label>
                            <div class="">
                                <input type="text" name="curr_value" <?= get_curr_user()->branch == 1?'':'readonly' ?>   data-val-regex="المدخل غير صحيح!" data-val-regex-pattern="<?= float_format_exp() ?>" data-val="true"  data-val-required="حقل مطلوب"  id="txt_curr_value" class="form-control ">
                                <span class="field-validation-valid" data-valmsg-for="curr_value" data-valmsg-replace="true"></span>

                            </div>
                        </div>


                        <?php if(count($rs) > 0 &&  $rs['CHECK_ID'] != ''): ?>
                            <div class="form-group col-sm-2">
                                <label class="control-label"> رقم الشيك</label>
                                <div class="">
                                    <input type="text" name="check_id" readonly  data-val-regex="المدخل غير صحيح!" data-val-regex-pattern="\d{1,}" id="txt_check_id" class="form-control ">

                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="form-group col-sm-12">
                            <label class="control-label">البيان </label>
                            <div class="">
                                <?php if(intval($type) == 12 ):?>
                                    <textarea type="text" name="hints" data-val="true" rows="5"  data-val-required="حقل مطلوب"  id="txt_hints" class="form-control "></textarea>

                                <?php else: ?>
                                    <input type="text" name="hints" data-val="true"  data-val-required="حقل مطلوب"  id="txt_hints" class="form-control ">

                                <?php endif; ?>

                                <span class="field-validation-valid" data-valmsg-for="hints" data-valmsg-replace="true"></span>

                            </div>
                        </div>
                        <hr/>
                        <br>
                        <div class="btn-group">
                            <button class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
                            <ul class="dropdown-menu " role="menu">
                                <li><a href="#" onclick="$('#chains_detailsTbl').tableExport({type:'excel',escape:'false'});">  XLS</a></li>
                                <li><a href="#" onclick="$('#chains_detailsTbl').tableExport({type:'doc',escape:'false'});">  Word</a></li>
                            </ul>
                        </div>
                        <div style="clear: both;">
                            <?php echo modules::run((isset($fin_copy_id)?'financial/financial_chains/public_get_details_copy':'financial/financial_chains/public_get_details'),(count($rs))?$rs['FINANCIAL_CHAINS_ID']:(isset($fin_copy_id)?$fin_copy_id:0)); ?>

                        </div>
                        <hr/>
                        <div style="clear: both;">
                            <?php echo modules::run('settings/notes/public_get_page',(count($rs))?$rs['FINANCIAL_CHAINS_ID']:0,'chains'); ?>
                            <?php echo (count($rs))?  modules::run('attachments/attachment/index',$rs['FINANCIAL_CHAINS_ID'],'financial_chains') : ''; ?>

                        </div>
                        <hr/>
                        <div class="modal-footer">
                            <?php if (($isCreate || ( isset($can_edit)?$can_edit:false))) : ?>
                                <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                            <?php endif; ?>

                            <?php if ($isReview  && $case < 3) : ?>
                                <button type="button" onclick="javascript:return_mqasah(1);" class="btn btn-primary">إعتماد الرقابة </button>
                            <?php endif; ?>

                            <?php if ($isAdopt && $case < 2) : ?>
                                <button type="button" onclick="javascript:return_mqasah(1);" class="btn btn-primary">إعتماد  </button>
                            <?php endif; ?>

                            <?php if ((($isReview  && $case < 3)|| ($isAdopt && $case < 2))  ) : ?>
                                <button type="button" onclick="javascript:return_mqasah(0);" class="btn btn-danger">إرجاع  </button>
                            <?php endif; ?>

                            <?php if ($isCreate): ?>
                                <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
                            <?php   endif; ?>

                            <button type="button" style="display:none;" id="print_chain" onclick="javascript:print_chain();"  class="btn btn-success">  طباعة</button>

                            <?php if (count($rs) && $rs['FIANANCIAL_CHAINS_SOURCE'] != 4 ): ?>
                                <button type="button" style="display:none;" id="source_url" onclick=" javascript:get_to_link('<?= f_c_source((count($rs))?$rs['FIANANCIAL_CHAINS_SOURCE']:0,(count($rs))?$rs['PAYMENT_ID']:0) ?>');"  class="btn btn-default">  فتح مصدر القيد</button>
                            <?php   endif; ?>
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
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"> ملاحظات</h4>
                </div>
                <div id="msg_container_alt"></div>

                <div class="form-group col-sm-12">

                    <div class="">
                        <textarea type="text" data-val="true" rows="5"    id="txt_g_notes" class="form-control "></textarea>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button"  onclick="javascript:apply_action();" class="btn btn-primary">حفظ البيانات</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


<?php if(count($rs) && $rs['FIANANCIAL_CHAINS_SOURCE'] == 4): ?>
    <table class="table info">
        <thead>
        <tr>
            <th>المدخل</th>

            <th>اعتماد المدير المالي</th>
            <th>اعتماد الرقابة</th></tr>
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
<?php

$today = date('d/m/Y');
$exp = float_format_exp();
$select_accounts_url =base_url('financial/accounts/public_select_accounts');
$customer_url =base_url('payment/customers/public_index');
$project_accounts_url =base_url('projects/projects/public_select_project_accounts');
$reset_form = $isCreate ?'true':'false';
$currency_date_url =base_url('settings/currency/public_get_currency_date');

$isCopy =  isset($fin_copy_id)?1:0;

$rs = preg_replace("/(\r\n|\n|\r)/", "\\n", $rs);

$curr_id = count($rs) > 0 ?$rs['CURR_ID'] : 1;

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

     $('#dp_curr_id').change(function(){

            $('#txt_curr_value').val($('#dp_curr_id').find(':selected').attr('data-val'));
            ReCalcVBalance();
        });
  function setCurrValue(){
        $('#dp_curr_id').val({$curr_id});
        $('#txt_curr_value').val($('#dp_curr_id').find(':selected').attr('data-val'));
        ReCalcVBalance();
    }

    $('#txt_curr_value').keyup(function(){ReCalcVBalance();});




SCRIPT;

$scripts = <<<SCRIPT

<script>

    var count = 0;
    var aTypes = $('#dp_account_type_0').html();
    $(function(){

        reBind();
        setCurrValue();


        show_data_root();

        $('#txt_financial_chains_date').change(function(){
            get_data('{$currency_date_url}',{date:$(this).val()},function(data){

                if(data.length > 0){
                    $('#dp_curr_id').html('');

                    $.each(data,function(i,item){
                        $('#dp_curr_id').append('<option data-val="'+item.VAL+'"   value="'+item.CURR_ID+'">'+item.CURR_NAME+'</option>');

                    });

                    setCurrValue();
                }
            });
        });

        count = $('input[name="account_id[]"]').length;

    });

    function show_data_root(){
        if($('select[name="account_type[]"]').val()== 2 || $('select[name="account_type[]"]').val()== 3){
            // $('td[data-root="true"],th[data-root="true"]').show();
        }else{
            // $('td[data-root="true"],th[data-root="true"]').hide();
        }

    }


    function reBindAfterInsert(tr){
        if($('select[name="account_type[]"]',$(tr)).val() == 2)
            $('select[name="customer_account_type[]"]',$(tr)).show();
        else
            $('select[name="customer_account_type[]"]',$(tr)).hide();


        //$('select[name="account_type[]"]',$(tr)).prop('disabled',false);
        //$('input[id^="isChange_"]', $(tr) ).val(1) ;
    }

    function reBind(){
        delete_action();

        $('select[name="account_type[]"]').change(function(){
            if($(this).val() == 2 ){
                $('select[name="customer_account_type[]"]',$(this).closest('tr')).show();
            } else {
                $('select[name="customer_account_type[]"]',$(this).closest('tr')).hide();
            }
        });

        $('input[name="account_id_name[]"]').click(function(e){
            selectAccount($(this));
        });

        $('input[name="root_account_id_name[]"]').click(function(e){
            _showReport('$select_accounts_url/'+$(this).attr('id') );
        });

        $('input[name="credit[]"],input[name="debit[]"]').bind("keyup",function(e){

            CalcVBalance($(this));

            sumOfValues();

        });

        $('select[name="account_type[]"]').change(function(){

            $(this).closest('tr').find('input[name="account_id_name[]"]').val('');
            $(this).closest('tr').find('input[name="account_id[]"]').val('');
            if($(this).val()== 2){
                $('td[data-root="true"],th[data-root="true"]').show();
            }else{
                $('td[data-root="true"],th[data-root="true"]').hide();
            }

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

        show_data_root();

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

        $(obj).closest('tr').find('input[id^="isChange_"]').val(1);

        get_data('{$get_balance_url}',{id: $(obj).val()},function(data){
            $(obj).closest('tr').find('.balance').text(data);
        },'html');
    }

    function addRow(){


        if($('input:text',$('#chains_detailsTbl')).not('input[name="root_account_id[]"],input[name="root_account_id_name[]"]').filter(function() { return this.value == ""; }).length <= 0){

            count = count+1;

            var html ='<tr><td>   <input type="hidden" name="financial_chains_seq[]" >  <select name="account_type[]" id="dp_account_type_'+count+'" class="form-control"> '+aTypes+' </select></td>'+
                '<td> <input type="text" name="account_id[]" id="h_account_'+count+'"  class="form-control col-sm-4"> <input name="account_id_name[]" data-val="true"  data-val-required="حقل مطلوب"   readonly id="account_'+count+'" class="form-control col-sm-8"> </td>'+
                '<td data-root="true" ><input type="hidden" value="1" name="root_account_type[]"> <input type="text" name="root_account_id[]"   id="h_root_account_id_'+count+'"  class="form-control col-sm-3"><input name="root_account_id_name[]"    id="root_account_id_'+count+'" readonly class="form-control col-sm-8"></td>'+
                '<td><input name="debit[]" data-type="decimal" id="debit_'+count+'" data-val="true"  data-val-required="حقل مطلوب"    data-val-regex="المدخل غير صحيح!" data-val-regex-pattern="$exp" class="form-control"></td>'+
                '<td><input name="credit[]" data-type="decimal" id="credit_'+count+'" data-val="true"  data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!" data-val-regex-pattern="$exp" class="form-control"> </td>  <td><input name="dhints[]" type="text" value="'+$('#txt_hints').val()+'" class="form-control"> </td> <td class="v_balance"></td><td class="balance"></td><td><a data-action="delete" href="javascript:;"><i class="icon icon-trash delete-action"></i> </a> </td></tr>';

            $('#chains_detailsTbl tbody').append(html);



            reBind();
            Up_Down();
        }
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

    $('input,select',$('#chains_detailsTbl')).change(function(){
        $(this).closest('tr').find('input[id^="isChange_"]').val(1);
    });
    $('input,select',$('#chains_detailsTbl')).keyup(function(){
        $(this).closest('tr').find('input[id^="isChange_"]').val(1);
    });


    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if (typeof return_mqasah == 'function') {

            return_mqasah(-1);
        }else {
            saveData();
        }
    });


    function saveData(){
    
        var totalSelectedBnkFin = $('select[name="bk_fin_id[]"]:visible').filter(function( index ) {
                                                                        return $(this).val() != '';
                                                                  }).length;
        var totalBnkFin = $('select[name="bk_fin_id[]"]:visible').length;
    
        if(totalSelectedBnkFin != totalBnkFin){
             return alert('يجب تحديد المقبوضات و المدفوعات');
        }
   
        $('select[name="bk_fin_id[]"]').each(function(i){
            $(this).prop('name','bk_fin_id'+i);
        });
            
        if(confirm('هل تريد حفظ القيد ؟!')){
            var form = $('#chains_from');

           ////if({$isCopy} != 1)
           //    $('tr',$('#chains_detailsTbl')).filter(function( index ) {
           //        return $('input[id^="isChange_"]', this ).val() == 0;
           //    }).find('input,select').not('input[name="debit[]"] ,input[name="credit[]"],input[name="isChange[]"] ').prop('disabled',true);

 
             $('input[name="account_id_name[]"],button[type="submit"]').prop('disabled',true);
           
            ajax_insert_update(form,function(data){

                if(data=='1'){

                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    setTimeout(function(){
                        if({$isCopy} != 1)
                            window.location.reload();
                        else
                            get_to_link('{$back_url}');

                    }, 1000);
                } 
            },'html',function(){ $('input[name="account_id_name[]"],button[type="submit"]').prop('disabled',false); });
        }
    }



    function financial_chains_detail_tb_delete(td,id){
        if(confirm('هل تريد حذف القيد؟!')){
            ajax_delete_any('$delete_details_url',{id:id},function(data){
                if(data == '1'){
                    $(td).closest('tr').remove();
                    success_msg('رسالة','تم حذف القيد بنجاح ..');
                }
            });
        }
    }

</script>


SCRIPT;



if($isCreate || $isCopy == 1)
    sec_scripts($scripts);

else{


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

    if({$rs['FINANCIAL_CHAINS_CASE']} > 1)
        $('#dp_curr_id').prop('disabled',true);
    //$('#dp_curr_id').closest('div').append('<input type="hidden" name="curr_id" value="{$rs['CURR_ID']}" >');

    $('#print_chain,#source_url').show();
    $('#print_chain').click(function(){

      _showReport('{$report_jasper_url}report=financial_chains&p_financial_chains_id={$rs['FINANCIAL_CHAINS_ID']}');
    });
   function adopt_chains(){
        get_data('{$adopt_url}',{financial_chains_id:{$rs['FINANCIAL_CHAINS_ID']},hints:'{$rs['HINTS']}'},function(data){
            if(data =='1'){
       success_msg('رسالة','تم إعتماد السند بنجاح ..');
        setTimeout(function(){

                   window.location.reload();

                }, 1000);
                }
        },'html');
    }

    function review_chains(){
     get_data('{$review_url}',{financial_chains_id:{$rs['FINANCIAL_CHAINS_ID']}},function(data){
            if(data =='1')
               success_msg('رسالة','تم إعتماد تدقيق السند بنجاح ..');
                setTimeout(function(){

                  window.location.reload();

                }, 1000);
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

        get_data('{$notes_url}',{source_id:{$rs['FINANCIAL_CHAINS_ID']},source:'chains',notes:$('#txt_g_notes').val()},function(data){
            $('#txt_g_notes').val('');
        },'html');

           //reloadPage();

         $('#notesModal').modal('hide');
    }


ReCalcVBalance();
sumOfValues();
</script>
SCRIPT;
    if(isset($can_edit)?$can_edit:false)
        $edit_script =$edit_script.$scripts;


    sec_scripts($edit_script);


}


?>