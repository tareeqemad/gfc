<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 14/12/14
 * Time: 12:15 م
 */

$report_path = base_url('/reports');
$report_url = base_url("JsperReport/showreport?sys=technical");
$trial_balance_url = base_url('financial/reports/trial_balance');
$professor_account_url = base_url('financial/reports/professor_account');
$cars_url = base_url('payment/cars/public_select_car');
$customer_url = base_url('payment/customers/public_index');


?>

<?= AntiForgeryToken() ?>
<div class="row">
<div class="toolbar">

    <div class="caption"><?= $title ?></div>

    <ul>
        <!--     <li><a href="#">بحث</a> </li>-->
    </ul>

</div>

<div class="form-body">

<fieldset>
    <legend>تقارير الأعمال</legend>
    <ul class="report-menu">

        <li><a class="btn blue" data-report-source="1"

               data-option="#project_div,#branch,#date1,#date2" data-type="report"
               href="javascript:;">تقرير الأعمال</a></li>

        <li><a class="btn blue" data-report-source="3"
               data-option="#project_div,#branch,#date1,#date2" data-type="report"
               href="javascript:;"> أوامر عمل المشاريع </a></li>

        <li><a class="btn green" data-report-source="2"
               data-option="#project_div,#branch,#date1,#date2"
               data-type="report" href="javascript:;">تقرير بالمواد المستخدمة </a></li>


        <li><a class="btn green" data-report-source="4"
               data-option="#branch,#date1,#date2"
               data-type="report" href="javascript:;">تقرير المتابعة</a></li>


        <li><a class="btn green" data-report-source="5"
               data-option="#adapter,#jobs,#date1,#date2"
               data-type="report" href="javascript:;">بيانات المحول</a></li>


        <li><a class="btn blue" data-report-source="6"
               data-option="#projects_list,#date1,#date2"
               data-type="report" href="javascript:;"> تجميعات لأصناف المشاريع</a></li>


        <li><a class="btn red" data-report-source="7"
               data-option="#workoder_list,#jobs,#request_type,#branch,#date1,#date2"
               data-type="report" href="javascript:;"> تجميعات لأصناف لأوامر العمل</a></li>

        <li><a class="btn red" data-report-source="8"
               data-option="#branch,#request_type,#date1,#date2"
               data-type="report" href="javascript:;">تقرير المواد</a></li>

        <li><a class="btn red" data-report-source="9"
               data-option="#request_type,#date1,#date2"
               data-type="report" href="javascript:;">تقرير اجماليات الاعمال</a></li>

        <li><a class="btn red" data-report-source="10"
               data-option="#branch,#project_rp_req_type,#date1,#date2"
               data-type="report" href="javascript:;"> صيانة فرعي</a></li>

        <li><a class="btn red" data-report-source="11"
               data-option="#branch,#project_rp_req_type,#date1,#date2"
               data-type="report" href="javascript:;">  صيانة رئيسي</a></li>

        <li><a class="btn red" data-report-source="12"
               data-option="#branch,#project_tec_type,#date1,#date2"
               data-type="report" href="javascript:;">تقرير المشاريع</a></li>

        <li><a class="btn red" data-report-source="13"
               data-option="#project_div,#date1,#date2"
               data-type="report" href="javascript:;">تفاصيل المشروع </a></li>

        <li><a class="btn green" data-report-source="14"
               data-option="#class_id"
               data-type="report" href="javascript:;">أسعار أصناف المشاريع</a></li>

    </ul>
</fieldset>


<div class="modal fade" id="reportFilterModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span
                        class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">معايير البحث</h4>
            </div>
            <div class="form-horizontal">
                <div class="modal-body">



                    <div class="form-group  rp_prm" id="project_div" style="display: none;">
                        <label class="col-sm-3 control-label">المشروع</label>

                        <div class="col-sm-3">
                            <input type="text" id="h_txt_project" class="form-control"/>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" readonly name="customer" id="txt_project" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group  rp_prm" id="adapter" style="display: none;">
                        <label class="col-sm-3 control-label">رقم المحول</label>

                        <div class="col-sm-3">
                            <input type="text" id="h_txt_adapter" class="form-control"/>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" readonly name="customer" id="txt_adapter" class="form-control"/>
                        </div>
                    </div>


                    <div class="form-group rp_prm" id="date1" style="display: none;">
                        <label class="col-sm-3 control-label"> التاريخ </label>

                        <div class="col-sm-4">
                            <input type="text" data-type="date" data-date-format="DD/MM/YYYY" id="txt_date_1"
                                   class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group rp_prm" id="date2" style="display: none;">
                        <label class="col-sm-3 control-label"> حتى</label>

                        <div class="col-sm-4">
                            <input type="text" data-type="date" data-date-format="DD/MM/YYYY" id="txt_date_2"
                                   class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group rp_prm" id="jobs" style="display: none;">
                        <label class="col-sm-3 control-label"> رقم المهمة</label>

                        <div class="col-sm-3">
                            <input type="text" id="h_txt_job_id"
                                   class="form-control"/>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" readonly id="txt_job_id"
                                   class="form-control"/>
                        </div>
                    </div>


                    <div class="form-group rp_prm" id="request_type" style="display: none;">
                        <label class="col-sm-3 control-label"> نوع الطلب</label>

                        <div class="col-sm-4">
                            <select name="request_type" class="form-control" id="dp_request_type">
                                <option></option>
                                <?php foreach ($REQUESTS_TYPE as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>


                    <div class="form-group rp_prm" id="branch" style="display: none;">
                        <label class="col-sm-3 control-label"> الفرع</label>

                        <div class="col-sm-4">
                            <select name="report" class="form-control" id="dp_branch_1">
                                <option></option>
                                <?php foreach ($branches as $row) : ?>
                                    <option data-dept="<?= $row['NO'] ?>"
                                            value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>


                    <div class="form-group rp_prm" id="project_tec_type" style="display: none;">
                        <label class="col-sm-3 control-label"> تصنيف المشروع</label>

                        <div class="col-sm-4">
                            <select name="report" class="form-control" id="dp_project_tec_type">
                                <option></option>
                                <?php foreach ($project_tec_type as $row) : ?>
                                    <option data-dept="<?= $row['CON_NO'] ?>"
                                            value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group rp_prm" id="project_rp_req_type" style="display: none;">
                        <label class="col-sm-3 control-label"> نوع التقرير </label>

                        <div class="col-sm-4">
                            <select name="report" class="form-control" id="dp_project_rp_req_type">
                                <option ></option>
                                <option value="10">الكفاء الفنية</option>
                                <option value="2">صيانة شبكة</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group rp_prm" id="projects_list" style="display: none;">
                        <label class="col-sm-3 control-label"> المشروع </label>

                        <div class="col-sm-3">
                            <input type="text" id="txt_project_id" class="form-control"/>
                        </div>

                        <div class="col-sm-1">
                            <button class="btn blue">+</button>
                        </div>
                        <div class="col-sm-12">

                            <ul class="project-list">

                            </ul>

                        </div>

                    </div>

                    <div class="form-group rp_prm" id="workoder_list" style="display: none;">
                        <label class="col-sm-3 control-label"> اوامر العمل </label>

                        <div class="col-sm-3">
                            <input type="text" id="txt_project_id" class="form-control"/>
                        </div>

                        <div class="col-sm-1">
                            <button class="btn blue">+</button>
                        </div>
                        <div class="col-sm-12">

                            <ul class="project-list">

                            </ul>

                        </div>

                    </div>

                    <div class="form-group rp_prm" id="class_id" style="display: none;">
                        <label class="col-sm-3 control-label">رقم الصنف</label>
                        <div class="col-sm-6">
                            <input type="text"  id="txt_class_id" class="form-control"/>
                        </div>

                    </div>


                    <div class="modal-footer">
                        <button type="button" data-action="report" data-type="pdf" class="btn btn-primary">عرض
                            التقرير
                        </button>
                        <button type="button" data-action="report" data-type="xls" class="btn btn-warning">عرض
                            التقرير xls
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                    </div>
                </div>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

</div>
</div>
<?php
$select_accounts_url = base_url('financial/accounts/public_select_accounts');
$select_parent_url = base_url('financial/accounts/public_select_parent');
$get_id_url = base_url('financial/accounts/public_get_id');
$project_accounts_url = base_url('projects/projects/public_select_project_accounts');
$adapters_url = base_url('projects/adapter/public_index');
$get_Tjob_url = base_url('technical/Technical_jobs/public_index');

$scripts = <<<SCRIPT
<script>

$(function(){
    $('#list').hide();
    $('#dp_check').change(function(e){

        if ($('#dp_check').val()==1){


            $('#list').show();
            $('#dp_type_list').show();
            $('#dp_types_lists').hide();

        }else  if ($('#dp_check').val()==2){

            $('#list').show();
            $('#dp_type_list').hide();
            $('#dp_types_lists').show();
        }else{

            $('#list').hide();
            $('#dp_type_list').hide();
            $('#dp_types_lists').hide();
        }

    });


    $('#dp_report').on('change',function(){

        var val =parseInt( $(this).val());

        $('.rp_prm').slideUp();

        $($(this).find(':selected').attr('data-op')).slideDown();

    });

    $('input[name="account1"]').click(function(e){
        _showReport('$select_accounts_url/'+$(this).attr('id')+'/-1/' );
    });

    $('input[name="account2"]').click(function(e){
        _showReport('$select_accounts_url/'+$(this).attr('id')+'/-1/' );
    });



    $('input[name="parent"]').click(function(e){
        _showReport('$select_parent_url/'+$(this).attr('id')+'/-1/' );
    });
    $('input[name="parent1"]').click(function(e){
        _showReport('$select_parent_url/'+$(this).attr('id')+'/-1/' );
    });

  $('#txt_project').click(function(e){
       _showReport('$project_accounts_url/'+$(this).attr('id'));
    });



});

function get_account_name(obj){
    $(obj).closest('tr').find('input[name="parent[]"]').val('');

}

var selected = 0;
var data_report_source ='';

$(function () {



    $('#accountsModal').on('shown.bs.modal', function () {
        $('#txt_acount_name').focus();
    });

    $('#txt_customer,#txt_customer1').click(function(e){
        _showReport('$customer_url/'+$(this).attr('id') );
    });

    $('#txt_customer,#txt_customer1').click(function(e){
        _showReport('$customer_url/'+$(this).attr('id') );
    });
    $('#txt_car').click(function(e){
        _showReport('$cars_url/'+$(this).attr('id') );
    });

    $('#txt_car').click(function(e){
        _showReport('$cars_url/'+$(this).attr('id') );
    });


     $('#h_txt_account_1,#h_txt_account_2').keyup(function(){
            get_account_name($(this));
     });

       $('#txt_account_1,#txt_account_2').click(function(e){
             _showReport('$select_parent_url/'+$(this).attr('id')+'/-1' );
        });

    $('a[data-type="report"]').click(function(){

        $('.rp_prm').hide();
        clearForm_any('#reportFilterModal');

         $('.project-list').html('');

        data_report_source = $(this).attr('data-report-source');

        $($(this).attr('data-option')).show();
        $('#reportFilterModal').modal();
    });


     $('#txt_adapter').click(function(e){
        _showReport('$adapters_url/'+$(this).attr('id') );
    });

   $('#txt_job_id').click(function(e){
        _showReport('$get_Tjob_url/'+$(this).attr('id') );
    });


$('#check_type_1').change(function(){

    if($(this).val() == 1){
            $('#check_status_in_rp').show();
            $('#check_status_out_rp').hide();
    }else {

        $('#check_status_in_rp').hide();
        $('#check_status_out_rp').show();
    }

});

    $('#projects_list button,#workoder_list button').click(function(){
        var id = $('#txt_project_id',$(this).closest('.form-group')).val();
        if(id != '')
        {
            $('ul',$(this).closest('.form-group')).append('<li><button onclick="javascript:$(this).parent().remove()" >×</button> <input type="hidden" value="'+id+'" />'+id+'</li>');
            $('#txt_project_id',$(this).closest('.form-group')).val('');
        }
    });

    $('button[data-action="report"]').click(function(){

        var url='{$report_url}&report_type='+$(this).attr('data-type')+'&report=';
        var f_date =$('#txt_date_1').val();
        var t_date =$('#txt_date_2').val();
        var branch =$('#dp_branch_1').val();
        var curr_id = $('#dp_currency_1').val();
        var account_1 = $('#h_txt_account_1').val();
        var account_2 = $('#h_txt_account_2').val();
        var customer_id = $('#h_txt_customer').val();
        var customer_id1 = $('#h_txt_customer1').val();
        var customer_type = $('#customer_type_1').val();
        var car_id = $('#h_txt_car').val();
        var payment_type =$('#payment_type_1').val();
        var doc_id =$('#doc_id').val();

        var project_account = $('#h_txt_project').val();


        switch(data_report_source){

            case '1':

                  url +='Projects_WS&REQUEST_TYPE=4&TIME_OUT_FROM='+$('#txt_date_1').val()+'&TIME_OUT_TO='+$('#txt_date_2').val()+'&BRANCH_ID='+$('#dp_branch_1').val()+'&SOURCE_ID='+$('#h_txt_project').val()+'';
                break;

            case '2':

                 url +='Projects_tools&REQUEST_TYPE=4&TIME_OUT_FROM='+$('#txt_date_1').val()+'&TIME_OUT_TO='+$('#txt_date_2').val()+'&BRANCH_ID='+$('#dp_branch_1').val()+'&SOURCE_ID='+$('#h_txt_project').val()+'';

            case '3':

                 url +='WORK_ORDER_FProjects&TIME_FROM='+$('#txt_date_1').val()+'&TIME_TO='+$('#txt_date_2').val()+'&BRANCH_ID='+$('#dp_branch_1').val()+'&SOURCE_ID='+$('#h_txt_project').val()+'';


                break;
             case '4':

                 url +='TEC_STATISTICS_INFO&from_date='+$('#txt_date_1').val()+'&to_date='+$('#txt_date_2').val()+'&branch='+$('#dp_branch_1').val();


                break;

                case '4':

                 url +='TEC_STATISTICS_INFO&from_date='+$('#txt_date_1').val()+'&to_date='+$('#txt_date_2').val()+'&branch='+$('#dp_branch_1').val();


                break;

                case '5':

                 url +='Adapter_FullReport&id='+$('#h_txt_adapter').val()+'&from_date='+$('#txt_date_1').val()+'&to_date='+$('#txt_date_2').val()+'&job_id='+$('#h_txt_job_id').val();


                break;

                case '6':

                 var vals= '';

                $('.project-list input').each(function(i,v){
                    vals +=$(this).val()+',';
                });

                 url +='PROJECTS_ITEMS_COLLECTIONS&TEC='+vals+'end';


                break;
                   case '7':

                 var vals= '';

                $('.project-list input').each(function(i,v){
                    vals +=$(this).val()+',';
                });

                 url +='Workorders_items&TEC='+vals+'end'+'&from_date='+$('#txt_date_1').val()+'&to_date='+$('#txt_date_2').val()+'&job_id='+$('#h_txt_job_id').val()+'&request_type='+$('#dp_request_type').val()+'&branch='+$('#dp_branch_1').val();


                break;

                 case '8':


                 url +='tec_ass_items_report&from_date='+$('#txt_date_1').val()+'&to_date='+$('#txt_date_2').val()+'&branch='+$('#dp_branch_1').val()+'&type='+$('#dp_request_type').val()+'&report_title='+$("#dp_request_type option:selected").text();


                break;
                case '9':


                 url +='ass_jobs_count&from_date='+$('#txt_date_1').val()+'&to_date='+$('#txt_date_2').val()+'&type='+$('#dp_request_type').val()+'&report_title='+$("#dp_request_type option:selected").text();


                break;


             case '10':
                 url +='technicalEfficiency&p_date_from='+$('#txt_date_1').val()+'&p_date_to='+$('#txt_date_2').val()+'&p_branch='+$('#dp_branch_1').val()+'&p_class_output_account_id='+$('#dp_project_rp_req_type').val();
                break;

             case '11':
                 url +='technicalEfficiency2&p_date_from='+$('#txt_date_1').val()+'&p_date_to='+$('#txt_date_2').val()+'&p_branch='+$('#dp_branch_1').val()+'&p_class_output_account_id='+$('#dp_project_rp_req_type').val();
                break;

             case '12':
                 url +='technicalEfficiency3&p_date_from='+$('#txt_date_1').val()+'&p_date_to='+$('#txt_date_2').val()+'&p_branch='+$('#dp_branch_1').val()+'&p_project_tec_code='+$('#dp_project_tec_type').val();
                break;

             case '13':
                 url +='tec_project_detail&p_project_tec_code='+$('#h_txt_project').val();
                break;

             case '14':
                 url +='prices_of_projects&p_id='+$('#txt_class_id').val();
                break;


        }

        _showReport(url,true);

    });
});


  function get_account_name(obj){


                get_dataWithOutLoading('{$get_id_url}',{id:$(obj).val()},function(data){

                    if(data.length > 0){

                        $('#'+(obj.attr('id').replace('h_',''))).val(data[0].ACOUNT_NAME+' ('+data[0].CURR_NAME+')');
                    }else{
                        $('#'+(obj.attr('id').replace('h_',''))).val('');
                    }
                });



    }


function select_account(id,name,curr){
    selected = id;


    $('#menuModal').modal();
}

</script>

SCRIPT;

sec_scripts($scripts);

?>

