<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/27/14
 * Time: 8:46 AM
 */
$delete_url = base_url('payment/cars_coupon_fuel/delete');
$get_url = base_url('payment/cars_coupon_fuel/get_id');
$edit_url = base_url('payment/cars_coupon_fuel/edit');
$create_url = base_url('payment/cars_coupon_fuel/create');
$get_page_url = base_url('payment/cars_coupon_fuel/get_page');


$adopt_url = base_url('payment/cars_coupon_fuel/paid');
$report_url = base_url('reports?type=31');
$date_attr= " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";

?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if (HaveAccess($create_url)): ?>
                <li><a href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <?php if (HaveAccess($delete_url)): ?>
                <li><a onclick="javascript:user_delete();" href="javascript:;"><i
                            class="glyphicon glyphicon-remove"></i>حذف</a></li><?php endif; ?>

            <!--     <li><a href="#">بحث</a> </li>-->
        </ul>

    </div>

    <div class="form-body">
        <fieldset>
            <legend>بحـث</legend>
            <div class="modal-body inline_form">

                <div class="form-group col-sm-1">
                    <label class="control-label"> رقم الكوبون </label>

                    <div>
                        <input type="text" name="coupon_fuel_id" id="txt_coupon_fuel_id" class="form-control">

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
                    <label class="control-label"> رقم الألية </label>

                    <div>
                        <input type="text" name="car_no" id="txt_car_no" class="form-control">

                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> صاحب العهدة</label>

                    <div>
                        <input type="text" name="car_owner" id="txt_car_owner" class="form-control ltr"  >


                    </div>


                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> المستلم </label>

                    <div>
                        <input type="text" name="emp_id_name" id="txt_emp_id_name" class="form-control ltr"  >


                    </div>


                </div>


                <div class="form-group col-sm-2">
                    <label class="control-label">حالة الكوبون</label>
                    <select type="text" name="coupon_case" id="dp_coupon_case" class="form-control sel2" >
                        <option value="">__________</option>
                        <?php foreach ($coupon_case as $row) : ?>
                            <option  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ الكوبونات من</label>
                    <div>
                        <input type="text" <?=$date_attr?> name="coupon_start_date" id="txt_coupon_start_date" value="<?=date('d/m/Y',strtotime('-0 day'))?>" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الى</label>
                    <div>
                        <input type="text" <?=$date_attr?> name="coupon_end_date" id="txt_coupon_end_date" class="form-control">
                    </div>
                </div>


                <div class="form-group col-sm-2">
                    <label class="control-label"> المدخل </label>

                    <div>
                        <input type="text" name="entry_user" id="txt_entry_user" class="form-control ltr"  >


                    </div>


                </div>
            </div>

            <div class="modal-footer">

                <button type="button" onclick="javascript:do_search();" class="btn btn-success"> إستعلام</button>
				<button type="button" onclick="$('#cars_coupon_fuel_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success">  اكسل </button>
                <button type="button" onclick="javascript:clearForm_any($('fieldset'));do_search();"
                        class="btn btn-default">تفريغ الحقول
                </button>

            </div>
        </fieldset>

        <div id="msg_container"></div>

        <div id="container">
<!--            --><?php //echo modules::run('payment/cars_coupon_fuel/get_page', $page); ?>
        </div>

    </div>

</div>



<?php


$scripts = <<<SCRIPT

<script>


    function user_delete(){

        if(confirm('هل تريد حذف الحساب ؟!!!')){

            var url = '{$delete_url}';

            var tbl = '#carsTbl';

            var container = $('#container');

            var val = [];

            $(tbl + ' .checkboxes:checked').each(function (i) {
                val[i] = $(this).val();

            });

            ajax_delete(url, val ,function(data){

                success_msg('رسالة','تم حذف السجلات بنجاح ..');
                container.html(data);

            });
        }

    }


    function do_search(){

        get_data('{$get_page_url}',{page: 1,branch:$('#dp_branch').val(),entry_user:$('#txt_entry_user').val(),coupon_fuel_id:$('#txt_coupon_fuel_id').val(),car_no:$('#txt_car_no').val(),car_owner:$('#txt_car_owner').val(),emp_id_name:$('#txt_emp_id_name').val(),coupon_case:$('#dp_coupon_case').val(),coupon_start_date:$('#txt_coupon_start_date').val(),coupon_end_date:$('#txt_coupon_end_date').val() },function(data){
            $('#container').html(data);
        },'html');
    }

  function LoadingData(){

    ajax_pager_data('#carsTbl > tbody',{branch:$('#dp_branch').val(),entry_user:$('#txt_entry_user').val(),coupon_fuel_id:$('#txt_coupon_fuel_id').val(),car_no:$('#txt_car_no').val(),car_owner:$('#txt_car_owner').val(),emp_id_name:$('#txt_emp_id_name').val(),coupon_case:$('#dp_coupon_case').val(),coupon_start_date:$('#txt_coupon_start_date').val(),coupon_end_date:$('#txt_coupon_end_date').val() });

    }


 var selected = 0;
    var data_report_source ='';

    $(function () {



        $('#accountsModal').on('shown.bs.modal', function () {
            $('#txt_acount_name').focus();
        });

        $('a[data-type="report"]').click(function(){

            $('.rp_prm').hide();
            clearForm_any('#reportFilterModal');

            data_report_source = $(this).attr('data-report-source');

            $($(this).attr('data-option')).show();
            $('#reportFilterModal').modal();
        });


        $('button[data-action="report"]').click(function(){

            var url='';
            var f_date =$('#txt_date1').val();
            var t_date =$('#txt_date2').val();
            var branch =$('#dp_branch').val();


            switch(data_report_source){

                 case '1':
                   var report_id = $('#dp_report').val();

                   switch(report_id){
                         case '1':

                          url ='$report_url&report=FINANCIAL_BOOK_CHAINS_CAR&params[]='+selected+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+selected+'&params[]=&params[]='+$('#dp_currency').val()+'&params[]=1&params[]={$this->user->id}';
                         break;

                         case '2':

                          url ='$report_url&report=FINANCIAL_BOOK_CHAINS_ALL-CAR&params[]='+selected+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+selected+'&params[]=&params[]='+$('#dp_currency').val()+'&params[]=1&params[]={$this->user->id}';
                         break;
                   }


                 break;


                 case '2':
                        url ='$report_url&report=FINANC_CHAINS_acount_CAR_REP&params[]='+selected+'&params[]='+f_date+'&params[]='+t_date;

                 break;

            }

            _showReport(url);

        });
    });


  function cars_coupon_paid(id){

             if( confirm('هل تريد اتمام صرف الكوبون')){
                get_data('$adopt_url',{ser:id},function(data){
                        success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                        reload_Page();
                },'html');

             }
     }


    function select_account(id,name,curr){
           selected = id;


           $('#menuModal').modal();
    }



</script>
SCRIPT;

sec_scripts($scripts);

?> 