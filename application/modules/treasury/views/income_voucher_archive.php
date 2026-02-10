<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 29/11/14
 * Time: 09:12 ص
 */
$get_page_url =base_url('treasury/income_voucher/get_page_archive');
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>

        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>

        <div>

            <fieldset>
                <legend>بحـث</legend>
                <div class="modal-body inline_form">
                    <div class="form-group col-sm-1">
                        <label class="control-label">الرقم</label>
                        <div class="">
                            <input type="text" id="txt_voucher_id"  class="form-control "/>


                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">من تاريخ</label>
                        <div class="">
                            <input type="text" data-type="date"  data-date-format="DD/MM/YYYY"  id="txt_voucher_date_from" class="form-control "/>


                        </div>
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="control-label">من تاريخ</label>
                        <div class="">
                            <input type="text" data-type="date"  data-date-format="DD/MM/YYYY"  id="txt_voucher_date_to"  class="form-control "/>


                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">الزبون </label>
                        <div class="">
                            <input type="text" id="txt_customer" class="form-control "/>


                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">رقم القيد</label>
                        <div class="">
                            <input type="text" id="txt_financial_chains_id" class="form-control "/>


                        </div>
                    </div>
					
					  <div class="form-group col-sm-1">
                        <label class="control-label">رقم الشيك</label>
                        <div class="">
                            <input type="text" id="txt_check" class="form-control "/>


                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">  العملة  </label>
                        <div class="">
                            <input type="hidden" name="currency_id"/>
                            <select   id="dp_currency_id" class="form-control">
                                <option></option>
                                <?php foreach($currency as $row) :?>
                                    <option value="<?= $row['CURR_ID'] ?>"><?= $row['CURR_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">  نوع القبض </label>
                        <div class="">
                            <select name="income_type" id="dp_income_type" class="form-control">
                                <option></option>
                                <?php  foreach($income_type as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>



                    <div class="form-group col-sm-2">
                        <label class="control-label">المحصل </label>
                        <div class="">
                            <input type="text" id="txt_entry_user_name"  class="form-control "/>


                        </div>
                    </div>


                    <div class="form-group col-sm-2">
                        <label class="control-label">البيان </label>
                        <div class="">
                            <input type="text" id="txt_hints"  class="form-control "/>


                        </div>
                    </div>

                </div>
                <div class="modal-footer">

                    <button type="button" onclick="javascript:search_data();" class="btn btn-success"> إستعلام</button>
                    <div class="btn-group">
                        <button class="btn btn-warning dropdown-toggle" onclick="$('#voucherTbl').tableExport({type:'excel',escape:'false'});" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
                    </div>
                    <button type="button" onclick="javascript:clearForm_any($('fieldset'));search_data();" class="btn btn-default"> تفريغ الحقول</button>
                </div>
            </fieldset>


        </div>

        <div id="container" class="clearfix">
            <?php echo modules::run('treasury/income_voucher/get_page_archive',$page); ?>
        </div>

    </div>

</div>



<?php


$scripts = <<<SCRIPT

<script>

    $(function(){
        reBind();
    });

    function reBind(){

    ajax_pager({voucher_id : $('#txt_voucher_id').val(),voucher_date_from:$('#txt_voucher_date_from').val(),voucher_date_to:$('#txt_voucher_date_to').val(),customer:$('#txt_customer').val(),financial_chains_id:$('#txt_financial_chains_id').val(),entry_user_name:$('#txt_entry_user_name').val(),curr_id : $('#dp_currency_id').val(),income_type : $('#dp_income_type').val(),hints:$('#txt_hints').val() , check_id : $('#txt_check').val()});

    }

    function LoadingData(){

    ajax_pager_data('#voucherTbl > tbody',{voucher_id : $('#txt_voucher_id').val(),voucher_date_from:$('#txt_voucher_date_from').val(),voucher_date_to:$('#txt_voucher_date_to').val(),customer:$('#txt_customer').val(),financial_chains_id:$('#txt_financial_chains_id').val(),entry_user_name:$('#txt_entry_user_name').val(),curr_id : $('#dp_currency_id').val(),income_type : $('#dp_income_type').val(),hints:$('#txt_hints').val(), check_id : $('#txt_check').val()});

    }


   function search_data(){

        get_data('{$get_page_url}',{page: 1,voucher_id : $('#txt_voucher_id').val(),voucher_date_from:$('#txt_voucher_date_from').val(),voucher_date_to:$('#txt_voucher_date_to').val(),customer:$('#txt_customer').val(),financial_chains_id:$('#txt_financial_chains_id').val(),entry_user_name:$('#txt_entry_user_name').val(),curr_id : $('#dp_currency_id').val(),income_type : $('#dp_income_type').val(),hints:$('#txt_hints').val(), check_id : $('#txt_check').val()},function(data){
            $('#container').html(data);

            reBind();

        },'html');
    }
</script>
SCRIPT;

sec_scripts($scripts);



?>

