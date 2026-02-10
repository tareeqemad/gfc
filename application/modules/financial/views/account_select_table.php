<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 01/12/14
 * Time: 09:19 ص
 */


$report_url = 'https://itdev.gedco.ps/gfc.aspx?data='.get_report_folder().'&' ;
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>

        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>
        <div class="form-group">
            <div class="input-group col-sm-4">
                <span class="input-group-addon">  <i class="icon icon-search"></i></span>

                <input type="text" id="search-tbl" data-set="accountsTbl" class="form-control" placeholder="بحث">
            </div>
        </div>
        <div id="container">
            <div class="btn-group">
                <button class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
                <ul class="dropdown-menu " role="menu">
                    <li><a href="#" onclick="$('#accountsTbl').tableExport({type:'excel',escape:'false'});">  XLS</a></li>
                    <li><a href="#" onclick="$('#accountsTbl').tableExport({type:'doc',escape:'false'});">  Word</a></li>
                </ul>
            </div>
            <table class="table selected-red" id="accountsTbl" data-container="container">
                <thead>
                <tr>
                    <th>رقم الحساب</th>
                    <th>اسم الحساب</th>
                    <th>رصيد الحساب</th>
                    <th>الرصيد بعملته</th>
                    <th>عملة الحساب</th>
                    <th style="width: 100px">نوع الحساب</th>
					 <th>المركز المالي</th>
                    <th></th>

                </tr>
                </thead>
                <tbody>

                <?php foreach($accounts as $index => $row) :?>
                    <tr class="level-<?= strlen($row['ACOUNT_ID']) ?>" ondblclick="javascript:select_account('<?= $row['ACOUNT_ID'] ?>','<?= $row['ACOUNT_NAME'] ?>','');">

                        <td class="align-left"><?= $row['ACOUNT_ID'] ?></td>
                        <td class="align-right"><?= replace_d_dsh($row['ACOUNT_ID']).  $row['ACOUNT_NAME'] ?></td>
                        <td><?=check_credit($row['BALANCE']) ?></td>
                        <td><?= check_credit(($row['ABALANCE'])) ?></td>
                        <td><?= $row['CURR_NAME'] ?></td>
                        <td><?= $row['ACOUNT_FOLLOW_NAME'] ?></td>
					    <td><?= $row['FINANCIAL_CENTER_NAME'] ?></td>
                        <td>

                            <div class="btn-group">
                                <button class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
                                <ul class="dropdown-menu " role="menu">
                                    <li><a href="javascript:;" onclick="javascript:_showReport('http://itdev:801/gfc.aspx?type=30&data=<?=get_report_folder() ?>&report=FINANCIAL_CHAINS_TB_acount_REP&params[]=<?=$row['ACOUNT_ID'] ?>&params[]=&params[]=&params[]=1');">  XLS</a></li>
                                    <li><a href="javascript:;" onclick="javascript:_showReport('http://itdev:801/gfc.aspx?data=<?=get_report_folder() ?>&report=FINANCIAL_CHAINS_TB_acount_REP&params[]=<?=$row['ACOUNT_ID'] ?>&params[]=&params[]=&params[]=1');">  PDF</a></li>
                                </ul>
                            </div>
                         </td>

                    </tr>



                <?php endforeach;?>

                </tbody>
                <tfoot></tfoot>
            </table>
        </div>

    </div>
</div>
<div class="modal fade" id="menuModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">التقارير و الشاشات </h4>
            </div>
            <div class="modal-body">

                <ul class="report-menu">
                    <li><a class="btn green" onclick="javascript:get_to_link('<?= base_url('financial/accounts/stm_accounts/1') ?>/'+selected);" href="javascript:;">حركة الحساب</a> </li>
                    <li><a class="btn blue" data-report-source="1" data-option="#t_report,#branch,#currency" data-type="report" href="javascript:;">حساب الاستاذ </a> </li>
                    <li><a class="btn yellow" data-report-source="2" data-option="#m_report,#branch,#currency" data-type="report" href="javascript:;">ميزان المراجعة  </a> </li>
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
                        <button type="button" data-action="report" data-type="31" class="btn btn-primary">عرض التقرير </button>
                        <button type="button" data-action="report" data-type="28" class="btn btn-warning">عرض التقرير xls </button>

                        <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                    </div>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php


$scripts = <<<SCRIPT

<script>
    var selected = 0;
    var data_report_source ='';

    $(function () {



        $('#accountsModal').on('shown.bs.modal', function () {
            $('#txt_acount_name').focus();
        });

        $('#accounts').tree();

        $('a[data-type="report"]').click(function(){

            $('.rp_prm').hide();
            clearForm_any('#reportFilterModal');

            data_report_source = $(this).attr('data-report-source');

            $($(this).attr('data-option')).show();
            $('#reportFilterModal').modal();
        });


        $('button[data-action="report"]').click(function(){

            var url='{$report_url}type='+$(this).attr('data-type');
            var f_date =$('#txt_date1').val();
            var t_date =$('#txt_date2').val();
            var branch =$('#dp_branch').val();


            switch(data_report_source){

                 case '1':
                   var report_id = $('#dp_report').val();

                   switch(report_id){
                         case '1':

                          url +='&report=FINANCIAL_BOOK_CHAINS&params[]='+selected+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+selected+'&params[]=&params[]='+$('#dp_currency').val()+'&params[]=1&params[]={$this->user->id}&params[]=';
                         break;

                         case '2':

                          url +='&report=FINANCIAL_BOOK_CHAINS_ALL&params[]='+selected+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+selected+'&params[]=&params[]='+$('#dp_currency').val()+'&params[]=1&params[]={$this->user->id}&params[]=';
                         break;
                   }


                 break;

                 case '2':
                   var report_id = $('#dp_m_report').val();

                   switch(report_id){
                         case '1':
                              url +='&report=ACCOUNT_NAME_SUM&params[]='+selected+'&params[]='+selected+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+branch+'&params[]=';
                         break;
                         case '2':
                              url +='&report=ACCOUNT_NAME_rased&params[]='+selected+'&params[]='+selected+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+branch+'&params[]=';
                         break;
                         case '3':
                              url +='&report=ACCOUNT_NAME_SUM_rased&params[]='+selected+'&params[]='+selected+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+branch+'&params[]=';
                         break;
                         case '4':
                              url +='&report=ACCOUNT_NAME_rased_SumP&params[]='+selected+'&params[]='+selected+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+branch+'&params[]='+$('#dp_currency').val()+'&params[]=';
                         break;
                   }
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

