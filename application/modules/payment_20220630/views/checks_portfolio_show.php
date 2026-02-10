<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat

 */
$back_url = base_url('payment/checks_portfolio/index?type='.$_REQUEST['type']);

if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;

$rs = $HaveRs ? $result[0] : $result;


?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <li><a href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li>
        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>
        <div id="container">
            <form class="form-vertical" id="cash_from" method="post"
                  action="<?= base_url('payment/Checks_portfolio/create?type='.$_REQUEST['type']) ?>" role="form" novalidate="novalidate">
                <div class="modal-body inline_form">

                    <div class="form-group col-sm-1">
                        <label class="control-label"> م. </label>

                        <div>
                            <input type="hidden" value="<?= $_REQUEST['type'] ?>" name="check_type">
                            <input type="text" readonly value="<?= $HaveRs ? $rs['SEQ'] : "" ?>"
                                   name="seq" id="txt_seq" class="form-control">
                        </div>
                    </div>

                    <div class="form-group  col-sm-1">
                        <label class="control-label">الفرع </label>
                        <div>

                            <select type="text"   name="branch" id="dp_branch" class="form-control" >
                                <option></option>
                                <?php foreach($branches as $row) :?>
                                    <option  value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>


                    <div class="form-group col-sm-1">
                        <label class="control-label"> البنك </label>

                        <div class="">
                            <select name="check_bank_id" id="dp_check_bank_id" class="form-control">

                                <?php foreach ($banks as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-sm-1">
                        <label class="control-label">رقم الشيك</label>

                        <div class="">

                            <input type="text" name="check_id" data-val-regex="المدخل غير صحيح!"
                                   data-val-regex-pattern="\d{1,}" data-val="true" data-val-required="حقل مطلوب"
                                   id="txt_check_id" class="form-control ">

                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">تاريخ الشيك</label>

                        <div class="">

                            <input type="text" readonly name="check_date" data-type="date" data-date-format="DD/MM/YYYY"
                                   data-val-regex="المدخل غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"
                                   data-val="true" data-val-required="حقل مطلوب" id="txt_check_date"
                                   class="form-control ">


                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">قيمة الشيك</label>

                        <div class="">
                            <input type="text" name="cridet" data-val-regex="المدخل غير صحيح "
                                   data-val-regex-pattern="<?= float_format_exp() ?>" data-val="true"
                                   data-val-required="حقل مطلوب" id="txt_cridet" class="form-control ">

                        </div>
                    </div>

                    <hr>

                    <div class="form-group col-sm-3">
                        <label class="control-label">اسم صاحب الشيك</label>

                        <div class="">
                            <input type="text" name="check_customer" data-val="true" data-val-required="حقل مطلوب"
                                   id="txt_check_customer" class="form-control ">

                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label"> العملة </label>
                        <select id="dp_curr_id" name="curr_id" class="form-control">

                            <?php foreach ($currency as $row) : ?>
                                <option data-val="<?= $row['VAL'] ?>"
                                        value="<?= $row['CURR_ID'] ?>"><?= $row['CURR_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label"> سعر العملة </label>

                        <div class="">
                            <input type="text" name="curr_value" readonly data-val="true"
                                   data-val-required="حقل مطلوب" id="txt_curr_value" class="form-control ">

                        </div>
                    </div>

                    <hr>

                    <div class="form-group col-sm-4">
                        <label class="control-label"> حساب </label>

                        <div class="">

                            <input type="text" name="account_id" data-val="true" data-val-required="حقل مطلوب"
                                   id="h_txt_account_id" class="form-control col-md-3">
                            <input type="text" name="account_id_name" data-val="true" data-val-required="حقل مطلوب"
                                   readonly
                                   id="txt_account_id" class="form-control col-md-9">


                        </div>
                    </div>


                </div>


                <div class="modal-footer">

                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <button type="button" onclick="javascript:clear_form()" class="btn btn-default"> تفريغ
                        الحقول
                    </button>


                </div>


            </form>

        </div>

    </div>

</div>
<?php

$currency_date_url = base_url('settings/currency/public_get_currency_date');
$select_accounts_url =base_url('financial/accounts/public_select_accounts');
$scripts = <<<SCRIPT
<script>


</script>
SCRIPT;

//print_r($rs);

$shared_script = <<<SCRIPT
<script>

        setCurrValue();

        $('#dp_curr_id').change(function(){

          setCurrValue();

        });

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


         function setCurrValue(){   $('#txt_curr_value').val($('#dp_curr_id').find(':selected').attr('data-val')); }


           $('#txt_check_date').change(function(){
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


         function selectAccount(obj,prefix){

                _showReport('$select_accounts_url/'+$(obj).attr('id')+prefix );
         }


                $('#txt_account_id').click(function(e){
                         selectAccount($(this),'/-1/');

                });



    </script>
SCRIPT;


if ($HaveRs)
    sec_scripts($shared_script . $scripts);
else {

    $delete_script = <<<SCRIPT
<script>

</script>
SCRIPT;

    $cancel_script = <<<SC
        <script>

</script>
SC;


    $edit_script = <<<SCRIPT
<script>


</script>
SCRIPT;
    sec_scripts($shared_script . $edit_script);
}



?>



