<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/27/14
 * Time: 8:46 AM
 */
$delete_url =base_url('payment/cars/delete');
$get_url =base_url('payment/cars/get_id');
$edit_url =base_url('payment/cars/edit');
$create_url =base_url('payment/cars/create');
$get_page_url = base_url('payment/cars/get_page');


$report_url =base_url('reports?type=31');
?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if( HaveAccess($create_url)): ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif;?>
            <?php if( HaveAccess($delete_url)): ?><li><a  onclick="javascript:user_delete();" href="javascript:;"><i class="glyphicon glyphicon-remove"></i>حذف</a> </li><?php endif;?>

            <!--     <li><a href="#">بحث</a> </li>-->
        </ul>

    </div>

    <div class="form-body">
        <fieldset>
            <legend>بحـث</legend>
            <div class="modal-body inline_form">
                <div class="form-group col-sm-2">
                    <label class="control-label"> رقم الألية  </label>
                    <div>
                        <input type="text"  name="car_no" id="txt_car_no" class="form-control">

                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label"> صاحب العهدة</label>
                    <div>
                        <input type="text"  name="car_owner"   id="txt_car_owner" class="form-control ltr" "="">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label"> متابعة العداد</label>
                    <div>
                        <select type="text" name="car_case" id="dp_car_case" class="form-control">
                            <option></option>
                            <?php foreach ($car_case as $row) : ?>
                                <option  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label"> حالة الآلية</label>
                    <div>
                        <select type="text" name="machine_case" id="dp_machine_case_id" class="form-control">
                            <option></option>
                            <?php foreach ($machine_case as $row) : ?>
                                <option  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

            </div>

            <div class="modal-footer">

                <button type="button" onclick="javascript:do_search();" class="btn btn-success"> إستعلام</button>
                <button type="button" onclick="$('#carsTbl').tableExport({type:'excel',escape:'false'});" class="btn btn-success">  اكسل </button>
                <button type="button" onclick="javascript:clearForm_any($('fieldset'));do_search();" class="btn btn-default">تفريغ الحقول</button>

            </div>
        </fieldset>

        <div id="msg_container"></div>

        <div id="container">
<!--            --><?php //echo modules::run('payment/cars/get_page',$page); ?>
        </div>

    </div>

</div>



<?php


$scripts = <<<SCRIPT

<script>

    $('#dp_machine_case_id').val(1);
    
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

        get_data('{$get_page_url}',{page: 1,no : $('#txt_car_no').val(),name:$('#txt_car_owner').val(),branch:$('#dp_branch').val(),machine_case:$('#dp_machine_case_id').val(),car_case:$('#dp_car_case').val()},function(data){
            $('#container').html(data);
        },'html');
    }

  function LoadingData(){

    ajax_pager_data('#carsTbl > tbody',{no : $('#txt_car_no').val(),name:$('#txt_car_owner').val(),branch:$('#dp_branch').val()});

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

    function select_account(id,name,curr){
           selected = id;


           $('#menuModal').modal();
    }



</script>
SCRIPT;

sec_scripts($scripts);

?>



<div class="modal fade" id="menuModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">التقارير و الشاشات </h4>
            </div>
            <div class="modal-body">

                <ul class="report-menu">
                    <li><a class="btn green" data-report-source="2" data-option="" data-type="report" href="javascript:;">حركة الحساب  </a> </li>
                    <li><a class="btn blue" data-report-source="1" data-option="#t_report,#branch,#currency" data-type="report" href="javascript:;">حساب الاستاذ </a> </li>

                    <li></li>
                    <li></li>
                    <li></li>

                </ul>

            </div>


        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="reportFilterModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">معير البحث</h4>
            </div>
            <div class="form-horizontal">
                <div class="modal-body">

                    <div class="form-group rp_prm" id="t_report" style="display: none;" >
                        <label class="col-sm-3 control-label"> نوع التقرير</label>
                        <div class="col-sm-7">
                            <select   class="form-control"   id="dp_report">
                                <option value="1">حساب استاذ بسيط حسب المجموعة</option>
                                <option value="2">حساب استاذ مفصل</option>

                            </select>
                        </div>
                    </div>
                    <div class="form-group rp_prm" id="m_report" style="display: none;" >
                        <label class="col-sm-3 control-label"> نوع التقرير</label>
                        <div class="col-sm-7">
                            <select   class="form-control"   id="dp_m_report">
                                <option value="1">ميزان المراجعة بالمجاميع  </option>
                                <option value="2">ميزان المراجعة بالأرصدة </option>
                                <option value="3">ميزان المراجعة بالأرصدة و المجاميع</option>
                                <option value="4">ميزان مراجعه الحسابات وتوابعها بالمجاميع </option>

                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="date1" >
                        <label class="col-sm-3 control-label"> التاريخ </label>
                        <div class="col-sm-4">
                            <input type="text" data-type="date" data-date-format="DD/MM/YYYY" id="txt_date1" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group" id="date2" >
                        <label class="col-sm-3 control-label"> حتى</label>
                        <div class="col-sm-4">
                            <input type="text" data-type="date" data-date-format="DD/MM/YYYY" id="txt_date2" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group rp_prm" id="currency" style="display: none;" >
                        <label class="col-sm-3 control-label"> العملة</label>
                        <div class="col-sm-4">
                            <select name="report_curr" class="form-control"   id="dp_currency">
                                <?php foreach($currency as $row) :?>
                                    <option data-dept="<?= $row['CURR_ID'] ?>" value="<?= $row['CURR_ID'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group rp_prm" id="branch" style="display: none;" >
                        <label class="col-sm-3 control-label"> الفرع</label>
                        <div class="col-sm-4">
                            <select name="report" class="form-control"   id="dp_branch">
                                <option></option>
                                <?php foreach($branches as $row) :?>
                                    <option data-dept="<?= $row['NO'] ?>" value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" data-action="report" class="btn btn-primary">عرض التقرير </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                    </div>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

