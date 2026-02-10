<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 19/09/22
 * Time: 12:15 ص
 */

$MODULE_NAME = 'payroll_statement';
$TB_NAME = 'Bank_statements';

$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$report_url = base_url("JsperReport/showreport?sys=hr/salaries");
$date_attr = " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='" . date_format_exp() . "' data-val-regex='Error' ";

?>

<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="left-content">
        <span class="main-content-title mg-b-0 mg-b-lg-1"><?= $title ?></span>
    </div>
    <div class="justify-content-center mt-2">
        <ol class="breadcrumb">
            <li class="breadcrumb-item tx-15"><a href="javascript:void(0);">الرواتب</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
        </ol>
    </div>
</div>
<!-- /breadcrumb -->
<!-- Row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex py-3">
                <div class="mb-0 flex-grow-1 card-title">
                    <?= $title ?>
                </div>
                <div class="flex-shrink-0">

                </div>
            </div><!-- end card header -->
            <div class="card-body">

                <form class="form-vertical" id="<?= $TB_NAME ?>_form">
                    <div class="row">

                        <div class="form-group col-sm-2">
                            <label class="control-label">الموظف</label>
                            <div>
                                <select name="emp_no" id="dp_emp_no" class="form-control sel2">
                                    <option value="">_________</option>
                                    <?php foreach ($emp_no_cons as $row) : ?>
                                        <option value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ': ' . $row['EMP_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-2">
                            <label>رقم الهوية</label>
                            <input type="text" placeholder="رقم الهوية" name="emp_id" id="txt_emp_id"
                                   class="form-control" value="">
                        </div>

                        <div class="form-group col-md-2">
                            <label> من الشهر </label>
                            <input type="text" placeholder="من الشهر" name="from_month" id="txt_from_month" class="form-control" value="<?= date('Ym', strtotime('last month')) ?>">
                        </div>

                        <div class="form-group col-md-2">
                            <label> الى الشهر </label>
                            <input type="text" placeholder="الى الشهر" name="to_month" id="txt_to_month" class="form-control" value="">
                        </div>

                        <div class="form-group col-sm-2">
                            <label>البنك الرئيسي</label>

                            <select type="text" name="master_bank" id="dp_master_bank" class="form-control sel2">
                                <option value="">__________</option>
                                <?php foreach ($master_banks as $row) : ?>
                                    <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>فروع البنك</label>

                            <select type="text" name="bank_names[]" id="dp_bank_names" class="form-control sel2"
                                    multiple="multiple">
                                <option value="0">__________</option>
                                <?php foreach ($bank_names as $row) : ?>
                                    <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label> المقر</label>
                            <select name="branch_id" id="dp_branch_id" class="form-control sel2">
                                <option value="">_______</option>
                                <?php foreach ($branches as $row) : ?>
                                    <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>
                </form>
                <br>
                <div class="row">
                    <div class="flex-shrink-0 py-4">
                        <button type="button" onclick="javascript:search();" class="btn btn-primary"><i
                                    class="glyphicon glyphicon-search"></i> إستعلام
                        </button>
                        <button type="button" onclick="$('#bank_statements_tb').tableExport({type:'excel',escape:'false'});"
                                class="btn btn-success"><i class="fas fa-file-excel"></i>إكسل
                        </button>
                        <button type="button" onclick="javascript:clear_form();" class="btn btn-cyan-light"><i
                                    class="fas fa-eraser"></i>تفريغ الحقول
                        </button>
                    </div>
                    <div class="flex-shrink-0 py-4">
                        <div class="custom-radio custom-control">
                            <input type="radio" class="custom-control-input" id="group_by_master" name="group_by" checked value="1"/>
                            <label class="custom-control-label" for="group_by_master">تجميع حسب البنك</label>
                        </div>
                        <div class="custom-radio custom-control">
                            <input type="radio" class="custom-control-input" id="group_by_branch" name="group_by" value="2"/>
                            <label class="custom-control-label" for="group_by_branch">تجميع حسب الفرع</label>
                        </div>
                    </div>
                    <div class="flex-shrink-0 py-4">
                        <div class="custom-radio custom-control">
                            <input type="radio" class="custom-control-input" id="radio_pdf" name="type_rep" checked value="pdf"/>
                            <label class="custom-control-label" for="radio_pdf">PDF</label>
                        </div>
                        <div class="custom-radio custom-control">
                            <input type="radio" class="custom-control-input" id="radio_xlsx" name="type_rep" value="xls"/>
                            <label class="custom-control-label" for="radio_xlsx">XLSX</label>
                        </div>
                    </div>
                    <div class="flex-shrink-0 py-4">
                        <button type="button" onclick="javascript:print_rep(<?=$this->user->id?>);" class="btn btn-success">
                            <i class="fa fa-print"></i>
                            طباعة التقرير 1
                        </button>

                        <button type="button" onclick="javascript:print_rep_extra(<?=$this->user->id?>);" class="btn btn-secondary">
                            <i class="fa fa-print"></i>
                             طباعة التقرير 2
                        </button>
                    </div>

                </div>
                <hr>
                <div id="container">

                </div>
            </div><!--end card body-->
        </div><!--end card --->
    </div><!--end col lg-12--->
</div><!--end row--->

<?php
$scripts = <<<SCRIPT

<script type="text/javascript">

    $('.sel2').select2();
    
    $('#txt_from_month ,#txt_to_month').datetimepicker({
        format: 'YYYYMM',
        minViewMode: 'months',
        pickTime: false,
    
    });
    
    function values_search(add_page){
        var values= {page:1,from_month:$('#txt_from_month').val(), to_month:$('#txt_to_month').val(), bank_names:$('#dp_bank_names').val() ,branch_id:$('#dp_branch_id').val() ,emp_no:$('#dp_emp_no').val() ,emp_id:$('#txt_emp_id').val() ,master_bank:$('#dp_master_bank').val()};
        if(add_page==0)
            delete values.page;
        return values;
    }

    function search(){
        var values= values_search(1);
        get_data('{$get_page_url}', values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
        var values= values_search(0);
        ajax_pager_data('#bank_statements_tb > tbody',values);
    }

    function clear_form(){
        $('#txt_from_month,#txt_to_month,#txt_emp_id').val('');
        $('#dp_bank_names,#dp_branch_id,#dp_emp_no,#dp_master_bank,#dp_emp_no').select2('val',0);
    }

    
    function print_rep(user_session) {
        var rep_name = 'bank_statements';
 
        var emp_no = $('#dp_emp_no').val();
        var emp_id = $('#txt_emp_id').val();
        var from_month = $('#txt_from_month').val();
        var to_month = $('#txt_to_month').val();
        var master_bank = $('#dp_master_bank').val();
        var branch_bank = $('#dp_bank_names').val();
        var branch_id = $('#dp_branch_id').val();
        var rep_type = $('input[name=type_rep]:checked').val();
        var group_by = $('input[name=group_by]').val();
        
        var group_by_bank ,group_by_bank_branch;
        if (group_by == 1){
            group_by_bank = 1; group_by_bank_branch = '';
        }else if (group_by == 2){
            group_by_bank = ''; group_by_bank_branch = 1;  
        }else {
            group_by_bank = ''; group_by_bank_branch = '';  
        }

        if(to_month == ''){
            danger_msg('يجيب ادخال الفترة من شهر الى شهر');
            return 0;
        }
        
        var rep_url = '{$report_url}&report_type='+rep_type+'&report='+rep_name+'&p_emp_no='+emp_no+'&p_emp_id='+emp_id+'&p_from_month='+from_month
        +'&p_to_month='+to_month+'&p_main_bank='+master_bank+'&p_branch_bank='+branch_bank+'&p_branch='+branch_id+'&p_group_by_bank='+group_by_bank+'&p_group_by_bank_branch='+group_by_bank_branch+'&p_user_session='+user_session;
        _showReport(rep_url); 
    }
    
    function print_rep_extra(user_session) {
        var rep_name = 'bank_statements_extra';
    
        var emp_no = $('#dp_emp_no').val();
        var emp_id = $('#txt_emp_id').val();
        var from_month = $('#txt_from_month').val();
        var to_month = $('#txt_to_month').val();
        var master_bank = $('#dp_master_bank').val();
        var branch_bank = $('#dp_bank_names').val();
        var branch_id = $('#dp_branch_id').val();
        var rep_type = $('input[name=type_rep]:checked').val();
        var group_by = $('input[name=group_by]:checked').val();
    
        var group_by_bank ,group_by_bank_branch;
        if (group_by == 1){
            group_by_bank = 1; group_by_bank_branch = '';
        }else if (group_by == 2){
            group_by_bank = ''; group_by_bank_branch = 1;  
        }else {
            group_by_bank = ''; group_by_bank_branch = '';  
        }

        if(to_month == ''){
            danger_msg('يجيب ادخال الفترة من شهر الى شهر');
            return 0;
        }
        
        var rep_url = '{$report_url}&report_type='+rep_type+'&report='+rep_name+'&p_emp_no='+emp_no+'&p_emp_id='+emp_id+'&p_from_month='+from_month
        +'&p_to_month='+to_month+'&p_main_bank='+master_bank+'&p_branch_bank='+branch_bank+'&p_branch='+branch_id+'&p_group_by_bank='+group_by_bank+'&p_group_by_bank_branch='+group_by_bank_branch+'&p_user_session='+user_session;
        _showReport(rep_url); 
    }    
    
</script>

SCRIPT;
sec_scripts($scripts);

?>
