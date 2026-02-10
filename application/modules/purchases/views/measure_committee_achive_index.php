<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 21/09/22
 * Time: 12:15 ص
 */

$MODULE_NAME = 'purchases';
$TB_NAME = 'Measure_committee_achive';

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
            <li class="breadcrumb-item tx-15"><a href="javascript:void(0);">المشتريات</a></li>
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
                            <label class="control-label">البيان</label>
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
                            <label>معالجة المعاملات</label>
                            <select name="branch_id" id="dp_branch_id" class="form-control sel2">
                                <option value="">_______</option>
                                <?php foreach ($branches as $row) : ?>
                                    <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label>حالة المعاملات</label>
                            <select name="branch_id" id="dp_branch_id" class="form-control sel2">
                                <option value="">_______</option>
                                <?php foreach ($branches as $row) : ?>
                                    <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label>التقدير</label>
                            <select name="branch_id" id="dp_branch_id" class="form-control sel2">
                                <option value="">_______</option>
                                <?php foreach ($branches as $row) : ?>
                                    <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label>المكافأة</label>
                            <select name="branch_id" id="dp_branch_id" class="form-control sel2">
                                <option value="">_______</option>
                                <?php foreach ($branches as $row) : ?>
                                    <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label> من الشهر </label>
                            <input type="text" placeholder="من الشهر" name="from_month" id="txt_from_month"
                                   class="form-control" value="<?= date('Ym', strtotime('last month')) ?>">
                        </div>

                        <div class="form-group col-md-2">
                            <label> الى الشهر </label>
                            <input type="text" placeholder="الى الشهر" name="to_month" id="txt_to_month"
                                   class="form-control" value="">
                        </div>

                    </div>
                </form>
                <br>
                <div class="row">
                    <div class="flex-shrink-0 py-4">
                        <button type="button" onclick="javascript:search();" class="btn btn-primary"><i
                                    class="glyphicon glyphicon-search"></i> إستعلام
                        </button>
                        <button type="button" onclick="$('#insurance_and_pensions_tb').tableExport({type:'excel',escape:'false'});"
                                class="btn btn-success"><i class="fas fa-file-excel"></i>إكسل
                        </button>
                        <button type="button" onclick="javascript:clear_form();" class="btn btn-cyan-light"><i
                                    class="fas fa-eraser"></i>تفريغ الحقول
                        </button>
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
                        <button type="button" onclick="javascript:print_rep();" class="btn btn-secondary">
                            <i class="fa fa-print"></i>
                            طباعة التقرير
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
        var values= {page:1,from_month:$('#txt_from_month').val(), to_month:$('#txt_to_month').val(), emp_no:$('#dp_emp_no').val() ,branch_id:$('#dp_branch_id').val() };
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
        ajax_pager_data('#insurance_and_pensions_tb > tbody',values);
    }

    function clear_form(){
        $('#txt_from_month,#txt_to_month').val('');
        $('#dp_emp_no,#dp_branch_id').select2('val',0);
    }
    
    function print_rep() {

        var rep_name_pdf = 'insurance_pensions';
        var rep_name_xls = 'insurance_pensionsxls';
        var emp_no = $('#dp_emp_no').val();
        var from_month = $('#txt_from_month').val();
        var to_month = $('#txt_to_month').val();
        var branch_id = $('#dp_branch_id').val();
        var rep_type = $('input[name=type_rep]:checked').val();

        if(to_month == ''){
            danger_msg('يجيب ادخال الفترة من شهر الى شهر');
            return 0;
        }
        
        var rep_url = '{$report_url}&report_type='+rep_type+'&report='+rep_name_pdf+'&p_emp_no='+emp_no+'&p_month_from='+from_month
        +'&p_month_to='+to_month+'&p_branch='+branch_id;
        /*
         if(rep_type == 'xls'){
            var rep_url = '{$report_url}&report_type='+rep_type+'&report='+rep_name_xls+'&p_emp_no='+emp_no+'&p_month_from='+from_month
            +'&p_month_to='+to_month+'&p_branch='+branch_id;

         }else{
            var rep_url = '{$report_url}&report_type='+rep_type+'&report='+rep_name_pdf+'&p_emp_no='+emp_no+'&p_month_from='+from_month
            +'&p_month_to='+to_month+'&p_branch='+branch_id;
         }*/
        _showReport(rep_url); 
    }

</script>

SCRIPT;
sec_scripts($scripts);

?>
