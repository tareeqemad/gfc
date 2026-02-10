<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 08/11/14
 * Time: 09:08 ص
 */
$create_url=base_url('financial/warranty/create');
$get_page_url = base_url('financial/warranty/get_page');
$return_bail_url = base_url('financial/warranty/return_bail');
$cash_bail_url = base_url('financial/warranty/cash_bail');
$create_details_url = base_url('financial/warranty/create_details');
$select_accounts_url =base_url('financial/accounts/public_select_accounts');
?>

<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

        </ul>

    </div>

    <div class="form-body">
        <fieldset>
            <legend>بحـث</legend>
            <div class="modal-body inline_form">
                <div class="form-group col-sm-1">
                    <label class="control-label">رقم السند </label>
                    <div>
                        <input type="text"  name="entry_ser" id="txt_entry_ser"   class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> المستفيد</label>
                    <div>
                        <input type="text"  name="customer_name"    id="txt_customer_name" class="form-control">
                    </div>
                </div>


                <div class="form-group col-sm-2">
                    <label class="control-label"> التاريخ من</label>
                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="from_date"    id="txt_from_date" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> التاريخ الي</label>
                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="to_date"    id="txt_to_date" class="form-control">
                    </div>
                </div>


                <div class="form-group col-sm-2">
                    <label class="control-label"> تنتهي من تاريخ</label>
                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="to_finished_dateFrom"    id="txt_finished_dateFrom" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">  الي تاريخ</label>
                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="to_finished_date"    id="txt_finished_date" class="form-control">
                    </div>
                </div>


                <div class="form-group  col-sm-2">
                    <label class="control-label">نوع الكفالة </label>
                    <div>

                        <select type="text"   name="bail_case" id="dp_bail_case" class="form-control" >
                            <option></option>
                            <?php foreach($bail_case as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group  col-sm-1">
                    <label class="control-label">  العملة  </label>
                    <div class="">

                        <select   id="dp_currency_id" class="form-control">
                            <option></option>
                            <?php foreach($currency as $row) :?>
                                <option data-val="<?= $row['VAL'] ?>" value="<?= $row['CURR_ID'] ?>"><?= $row['CURR_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

            </div>
            <div class="modal-footer">

                <button type="button" onclick="javascript:do_search();" class="btn btn-success">بحث</button>
                <div class="btn-group">
                    <button class="btn btn-warning dropdown-toggle" onclick="$('#warrantyTbl').tableExport({type:'excel',escape:'false'});" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
                </div>
                <button type="button" onclick="javascript:clearForm_any($('fieldset'));do_search();" class="btn btn-default">تفريغ الحقول</button>

            </div>
        </fieldset>
        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run('financial/warranty/get_page',$page,$case);?>
        </div>

    </div>

</div>
<?=modules::run('financial/warranty/public_actions');?>

<?php


$scripts = <<<SCRIPT

<script>

    $(function(){
        reBind();
    });

    function reBind(){

    ajax_pager({action:'{$action}',id : $('#txt_entry_ser').val(),name:$('#txt_customer_name').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(),finished_date : $('#txt_finished_date').val(),to_finished_dateFrom:$('#txt_finished_dateFrom').val(),bill_case : $('#dp_bail_case').val(),curr_id : $('#dp_currency_id').val()});

    }

    function LoadingData(){

    ajax_pager_data('#warrantyTbl > tbody',{action:'{$action}',id : $('#txt_entry_ser').val(),name:$('#txt_customer_name').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(),finished_date : $('#txt_finished_date').val(),to_finished_dateFrom:$('#txt_finished_dateFrom').val(),bill_case : $('#dp_bail_case').val(),curr_id : $('#dp_currency_id').val()});

    }


   function do_search(){

        get_data('{$get_page_url}',{page: 1,action:'{$action}',id : $('#txt_entry_ser').val(),name:$('#txt_customer_name').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(),finished_date : $('#txt_finished_date').val(),to_finished_dateFrom:$('#txt_finished_dateFrom').val(),bill_case : $('#dp_bail_case').val(),curr_id : $('#dp_currency_id').val()},function(data){
            $('#container').html(data);

            reBind();

        },'html');
    }

var selected = -1;

        function select_warranty(id){
           selected = id;


           $('#menuModal').modal();
    }

      $('#h_txt_account_1,#h_txt_account_2').keyup(function(){
            get_account_name($(this));
     });

       $('#txt_account_1,#txt_account_2').click(function(e){
             _showReport('$select_accounts_url/'+$(this).attr('id')+'/-1' );
        });

    function return_bail(){
        if(confirm('هل تريد إرجاع الكفالة')){
                get_data('{$return_bail_url}',{ id : selected},function(data){

                  if(data == '1'){
                      success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                  }

                },'html');
        }
    }
       var data_report_source ='';
       $('a[data-type="report"]').click(function(){

            $('.rp_prm').hide();
            clearForm_any('#actionsModal');

            data_report_source = $(this).attr('data-report-source');

            $($(this).attr('data-option')).show();
            $('#actionsModal').modal();
        });

          $('button[data-action="report"]').click(function(){

            var url='';
            var f_date =$('#txt_date1').val();



            switch(data_report_source){

                 case '1':
                    if(f_date !='' && $('#txt_reasons').val() !=''){
                         if(confirm('هل تريد تجديد الكفالة')){
                            get_data('{$create_details_url}',{ bail_id : selected,date : f_date ,reason :  $('#txt_reasons').val()},function(data){

                              if(data == '1'){
                                  success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                                  $('#actionsModal').modal('hide');
                                   $('#menuModal').modal('hide');
                              }

                            },'html');
                         }
                     } else alert('يجب إدخال البيانات ؟!');

                 break;

                  case '2':
                    if($('#h_txt_account_1').val()  !='' && $('#h_txt_account_2').val() !=''){
                         if(confirm('هل تريد تجديد الكفالة')){
                            get_data('{$cash_bail_url}',{ bail_id : selected,bank_account : $('#h_txt_account_1').val()  ,income_account : $('#h_txt_account_2').val() },function(data){

                              if(data == '1'){
                                  success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                                  $('#actionsModal').modal('hide');
                                   $('#menuModal').modal('hide');
                              }

                            },'html');
                         }
                     } else alert('يجب إدخال البيانات ؟!');

                 break;
            }
          });


</script>
SCRIPT;

sec_scripts($scripts);



?>

