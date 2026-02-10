<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 14/10/14
 * Time: 09:35 ص
 */
$create_url = base_url('treasury/checks_processing/create');
$delete_url = base_url('payment/checks_portfolio/delete');
$get_page_url = base_url('payment/checks_portfolio/get_page');
?>

<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <li><a href="javascript:;"
                   onclick="javascript: _showReport('<?= base_url('payment/checks_portfolio/public_checks/' . $type) ?>');"><i
                        class="glyphicon glyphicon-list-alt"></i>الشيكات </a></li>

        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>

        <fieldset>
            <legend>بحـث</legend>
            <div class="modal-body inline_form">
                <div class="form-group col-sm-1">
                    <label class="control-label">رقم الشيك</label>

                    <div class="">
                        <input type="text" id="txt_check_id" class="form-control "/>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">المبلغ</label>

                    <div>
                        <select id="price_op" class="form-control col-sm-1">
                            <option>=</option>
                            <option><=</option>
                            <option>>=</option>
                            <option><</option>
                            <option>></option>
                        </select>

                        <input type="text" name="price" id="txt_price" class="form-control col-sm-5">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> البنك </label>

                    <div class="">
                        <select name="check_bank_id" id="dp_check_bank_id" class="form-control">
                            <option></option>
                            <?php foreach ($banks as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

            </div>
            <div class="modal-footer">

                <button type="button" onclick="javascript:do_search();" class="btn btn-success"> إستعلام</button>

                <button type="button" onclick="javascript:clearForm_any($('fieldset'));do_search();"
                        class="btn btn-default"> تفريغ الحقول
                </button>

                <div class="btn-group">
                    <button class="btn btn-warning dropdown-toggle"
                            onclick="$('#cashTbl').tableExport({type:'excel',escape:'false'});" data-toggle="dropdown">
                        <i class="fa fa-bars"></i> تصدير
                    </button>
                </div>
            </div>
        </fieldset>

        <div id="container">
            <?php echo modules::run('payment/checks_portfolio/get_page', $page, $type); ?>
        </div>

    </div>

</div>

<div id="withdraw_modal" class="modal fade">
    <div class="modal-dialog" style="width: 715px;">
        <div class="modal-content">

            <div class="modal-body">
                <div c="form-form-vertical">
                    <div class="form-group col-md-12">
                        <label class="col-sm-1 control-label">المدين </label>

                        <div class="col-sm-9">
                            <select name="account_type_debit" id="dp_account_type_debit" class="form-control col-sm-2">

                                <option value="1">شجرة الحسابات</option>
                                <option value="2">المستفيد</option>

                            </select>


                            <input type="text" id="h_txt_debit" class="form-control col-sm-3"/>
                            <input type="text" readonly name="debit" id="txt_debit" class="form-control col-sm-6"/>

                        </div>
                    </div>


                    <div class="form-group col-md-12">
                        <label class="col-sm-1 control-label">الدائن</label>

                        <div class="col-sm-9">

                            <select name="account_type_credit" id="dp_account_type_credit"
                                    class="form-control col-sm-2">

                                <option value="1">شجرة الحسابات</option>
                                <option value="2">المستفيد</option>

                            </select>
                            <input type="text" id="h_txt_credit" class="form-control col-sm-3"/>
                            <input type="text" readonly name="credit" id="txt_credit" class="form-control col-sm-6"/>

                        </div>
                    </div>


                    <div class="form-group col-md-12">
                        <label class="col-sm-1 control-label"> تاريخ </label>

                        <div class="col-sm-2">
                            <input type="text" name="convert_cash_bank_date" data-type="date"
                                   data-date-format="DD/MM/YYYY" value="<?= date('d/m/Y') ?>"
                                   id="txt_convert_cash_bank_date" class="form-control ">
                        </div>
                    </div>
					
					  <div class="form-group col-md-12">
                        <label class="col-sm-1 control-label"> البيان </label>

                        <div class="col-sm-2">
                            <input type="text" name="w_hints" id="w_hints" class="form-control ">
                        </div>
                    </div>

					
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="javascript:do_withdraw()" class="btn btn-danger"
                ">سحب</button>
                <button class="btn" data-dismiss="modal" aria-hidden="true">إغلاق</button>
            </div>
        </div>
    </div>
</div>

<?php

$select_parent_url = base_url('financial/accounts/public_select_accounts');
$customer_url =base_url('payment/customers/public_index');
$withdraw_url = base_url('payment/checks_portfolio/withdraw');
$scripts = <<<SCRIPT

<script>

    var selected_id ;
    function withdraw(id){
        $('#withdraw_modal').modal();
        $('input[name="debit"],input[name="credit"],#h_txt_debit,#h_txt_credit,#w_hints').val('');

        selected_id = id;
    }



  function delete_check(a,id){
          if(confirm('هل تريد حذف البند ؟!')){

                  get_data('{$delete_url}',{id:id},function(data){
                             if(data == '1'){

                                $(a).closest('tr').remove();

                               }else{
                                     danger_msg( 'تحذير','فشل في العملية');
                               }
                        });
                 }
    }

    function do_withdraw(){

        var debit = $('#h_txt_debit').val();
        var credit = $('#h_txt_credit').val();
        var date = $('#txt_convert_cash_bank_date').val();

        if(debit == '' || credit == '' || date == '')
            {
                alert('يجب إدخال البيانات ..');
                return;
            }

        get_data('{$withdraw_url}',{type:{$type},id:selected_id,debit:debit,credit:credit,date:date , account_type_debit : $('#dp_account_type_debit').val() ,account_type_credit : $('#dp_account_type_credit').val() , hints : $('#w_hints').val() },function(data){
            if(data =='1')
                success_msg('رسالة','تم إعتماد السند بنجاح ..');
                 setTimeout(function(){

                  window.location.reload();

                }, 1000);
        },'html');
    }

$(function(){
    if( {$checks_count} > 0 ){

    }

     $('input[name="debit"],input[name="credit"]').click(function(e){

              var _type = $(this).closest('div').find('select').val();

            if(_type == 1)
              _showReport('$select_parent_url/'+$(this).attr('id')+'/' );
            if(_type == 2)
               _showReport('$customer_url/'+$(this).attr('id')+'/1');


    });


    });

    $(function(){
        reBind();
    });

    function reBind(){

    ajax_pager({check_id : $('#txt_check_id').val(),bank:$('#dp_check_bank_id').val(),price:$('#txt_price').val(),price_op:$('#price_op').val()});

    }

    function LoadingData(){

    ajax_pager_data('#cashTbl > tbody',{check_id : $('#txt_check_id').val(),bank:$('#dp_check_bank_id').val(),price:$('#txt_price').val(),price_op:$('#price_op').val()});

    }


   function do_search(){

        get_data('{$get_page_url}/1/{$type}',{page: 1 ,check_id : $('#txt_check_id').val(),bank:$('#dp_check_bank_id').val(),price:$('#txt_price').val(),price_op:$('#price_op').val()},function(data){
            $('#container').html(data);

            reBind();

        },'html');
    }
</script>
SCRIPT;

sec_scripts($scripts);



?>

