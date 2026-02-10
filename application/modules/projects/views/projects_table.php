<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/27/14
 * Time: 8:46 AM
 */

$get_page_url = base_url('projects/projects/get_page_table');

?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>

        </ul>

    </div>

    <div class="form-body">
        <fieldset>
            <legend>بحـث</legend>
            <div class="modal-body inline_form">
                <div class="form-group col-sm-1">
                    <label class="control-label">الرقم الفني</label>
                    <div>
                        <input type="text"  name="tec_num" id="txt_tec_num"   class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label"> رقم الحساب</label>
                    <div>
                        <input type="text"  name="account_id" id="txt_account_id"   class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">اسم المشروع</label>
                    <div>
                        <input type="text"  name="project_name" id="txt_project_name"   class="form-control">
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

                <div class="form-group  col-sm-2">
                    <label class="control-label"> التصنيف الفنى للمشروع

                    </label>
                    <div>
                        <select class="form-control" name="project_tec_type" id="dp_project_tec_type">
                            <option></option>
                            <?php foreach($project_tec_type as $row) :?>
                                <option value="<?= $row['CON_NO'] ?>" data-tec="<?= $row['ACCOUNT_ID'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

            </div>

            <div class="modal-footer">

                <button type="button" onclick="javascript:do_search();" class="btn btn-success">بحث</button>
                <div class="btn-group">
                    <button class="btn btn-warning dropdown-toggle" onclick="$('#projectTbl').tableExport({type:'excel',escape:'false'});" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
                </div>
                <button type="button" onclick="javascript:clearForm_any($('fieldset'));do_search();" class="btn btn-default">تفريغ الحقول</button>

            </div>
        </fieldset>

        <div id="msg_container"></div>

        <div id="container">
            <?php// echo modules::run('projects/projects/get_page_table',$page); ?>
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

$report_url ='http://itdev:801/gfc.aspx?data='.get_report_folder().'&';
$scripts = <<<SCRIPT

<script>
 var selected = 0;
    var data_report_source ='';
    $(function(){
        reBind();
    });

    function reBind(){

        ajax_pager({project_tec_type:$('#dp_project_tec_type').val(),tec_num : $('#txt_tec_num').val(),project_name:$('#txt_project_name').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(),branch:$('#dp_branch').val(),account_id:$('#txt_account_id').val()});

    }

    function LoadingData(){

        ajax_pager_data('#projectTbl > tbody',{project_tec_type:$('#dp_project_tec_type').val(),tec_num : $('#txt_tec_num').val(),project_name:$('#txt_project_name').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(),branch:$('#dp_branch').val(),account_id:$('#txt_account_id').val()});

    }


    function do_search(){

        get_data('{$get_page_url}',{page: 1,project_tec_type:$('#dp_project_tec_type').val(),tec_num : $('#txt_tec_num').val(),project_name:$('#txt_project_name').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(),branch:$('#dp_branch').val(),account_id:$('#txt_account_id').val()},function(data){
            $('#container').html(data);

            reBind();

        },'html');
    }


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

                        url +='&report=FINANCIAL_BOOK_CHAINS_projects&params[]='+selected+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+selected+'&params[]=&params[]='+$('#dp_currency').val()+'&params[]=1&params[]={$this->user->id}';
                        break;
                     case '2':

                        url +='&report=FINANCIAL_BOOK_CHAINS_ALLprojects&params[]='+selected+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+selected+'&params[]=&params[]='+$('#dp_currency').val()+'&params[]=1&params[]={$this->user->id}';
                        break;
                }


                break;



        }

        _showReport(url);

    });

    function select_account(id,name,curr){
        selected = id;


        $('#menuModal').modal();
    }


</script>
SCRIPT;

sec_scripts($scripts);



?>
