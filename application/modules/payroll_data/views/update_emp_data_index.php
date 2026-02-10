<?php

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 11/12/22
 * Time: 10:00 ص
 */

$MODULE_NAME= 'payroll_data';
$TB_NAME= 'Update_emp_data';

$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");

$date_attr = " data-type='date' data-date-format='YYYYMM' data-val='true' data-val-regex-pattern='" . date_format_exp() . "' data-val-regex='Error' ";
?>

<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="left-content">
        <span class="main-content-title mg-b-0 mg-b-lg-1"><?=$title?></span>
    </div>
    <div class="justify-content-center mt-2">
        <ol class="breadcrumb">
            <li class="breadcrumb-item tx-15"><a href="javascript:void(0);">الحركات</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
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
                    <?=$title?>
                </div>
                <div class="flex-shrink-0">
                    <?php if (HaveAccess($create_url)) : ?>
                        <a class="btn btn-info" href="<?= $create_url ?>"><i class='glyphicon glyphicon-plus'></i>جديد</a>
                    <?php endif; ?>
                </div>
            </div><!-- end card header -->
            <div class="card-body">

                <form class="form-vertical" id="<?= $TB_NAME ?>_form">
                    <div class="row">

                        <div class="form-group col-md-2">
                            <label> المقر</label>
                            <select name="branch_id" id="dp_branch_id" class="form-control sel2">
                                <option value="">_______</option>
                                <?php foreach ($branches as $row) : ?>
                                    <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <div class="form-group col-sm-2">
                            <label>الموظف</label>
                            <div>
                                <select name="emp_no" id="dp_emp_no" class="form-control sel2">
                                    <option value="">_________</option>
                                    <?php foreach ($emp_no_cons as $row) : ?>
                                        <option value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ': ' . $row['EMP_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>رقم هوية الموظف</label>
                            <div>
                                <input type="text" value="" name="emp_id" id="txt_emp_id" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                            </div>
                        </div>


                    </div>
                </form>
                <br>
                <div class="flex-shrink-0">
                    <button type="button" onclick="javascript:search();" class="btn btn-primary"><i
                            class="glyphicon glyphicon-search"></i> إستعلام
                    </button>
                    <button type="button" onclick="$('#update_emp_data_tb').tableExport({type:'excel',escape:'false'});"
                            class="btn btn-success"><i class="fa fa-file-excel-o"></i>إكسل
                    </button>
                    <button type="button" onclick="javascript:clear_form();" class="btn btn-cyan-light"><i
                            class="fa fa-eraser"></i>تفريغ الحقول
                    </button>
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
    
    $('#txt_for_month').datetimepicker({
        format: 'YYYYMM',
        minViewMode: "months",
        pickTime: false
    });
    
    function values_search(add_page){
        var values= {page:1,emp_id:$('#txt_emp_id').val(),emp_no:$('#dp_emp_no').val(),branch_id:$('#dp_branch_id').val()};
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
        ajax_pager_data('#update_emp_data_tb > tbody',values);
    }
    
    function clear_form(){
        $('#txt_emp_id').val('');
        $('#dp_branch_id,#dp_emp_no').select2('val',0);
    }

</script>

SCRIPT;
sec_scripts($scripts);

?>
