<?php

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 26/12/22
 * Time: 12:30 ص
 */

$MODULE_NAME= 'reports_ebllc';
$TB_NAME= 'Dues_emp_gaza';

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
            <li class="breadcrumb-item tx-15"><a href="javascript:void(0);">احصائيات</a></li>
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
                                    <option value="<?= $row['VID'] ?>"><?= $row['VNAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>رقم الاشتراك</label>
                            <div>
                                <input type="text" value="" name="subscriber_no" id="txt_subscriber_no" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" />
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>اسم المشترك</label>
                            <div>
                                <input type="text" value="" name="subscriber_name" id="txt_subscriber_name" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>رقم الهوية </label>
                            <div>
                                <input type="text" value="" name="emp_id" id="txt_emp_id" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>نوع الوظيفة</label>
                            <select type="text" name="type_job" id="dp_type_job" class="form-control sel2" >
                                <option value="">__________</option>
                                <?php foreach ($type_job as $row) : ?>
                                    <option value="<?=$row['VID']?>"><?=$row['VNAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>نوع المستحقات</label>
                            <select type="text" name="dues_type" id="dp_dues_type" class="form-control sel2" >
                                <option value="">__________</option>
                                <?php foreach ($dues_type as $row) : ?>
                                    <option value="<?=$row['VID']?>"><?=$row['VNAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>حالة التسديد</label>
                            <select type="text" name="payment_status" id="dp_payment_status" class="form-control sel2" >
                                <option value="">__________</option>
                                <?php foreach ($payment_status as $row) : ?>
                                    <option value="<?=$row['VID']?>"><?=$row['VNAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-sm-1">
                            <label>شهر السداد</label>
                            <div>
                                <input type="text" name="pposting_month" id="txt_pposting_month" class="form-control" />
                            </div>
                        </div>

                    </div>
                </form>
                <br>
                <div class="flex-shrink-0">
                    <button type="button" onclick="javascript:search();" class="btn btn-primary"><i
                            class="glyphicon glyphicon-search"></i> إستعلام
                    </button>
                    <button type="button" onclick="$('#dues_emp_gaza_tb').tableExport({type:'excel',escape:'false'});"
                            class="btn btn-success"><i class="fas fa-file-excel"></i>إكسل
                    </button>
                    <button type="button" onclick="javascript:clear_form();" class="btn btn-cyan-light"><i
                            class="fas fa-eraser"></i>تفريغ الحقول
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
    
    $('#txt_pposting_month').datetimepicker({
        format: 'YYYYMM',
        minViewMode: "months",
        pickTime: false
    });
    
    function values_search(add_page){
        var values= {page:1,branch_id:$('#dp_branch_id').val(),subscriber_no:$('#txt_subscriber_no').val(),subscriber_name:$('#txt_subscriber_name').val(),emp_id:$('#txt_emp_id').val(),payment_status:$('#dp_payment_status').val(),pposting_month:$('#txt_pposting_month').val(),type_job:$('#dp_type_job').val(),dues_type:$('#dp_dues_type').val()};
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
        ajax_pager_data('#dues_emp_gaza_tb > tbody',values);
    }
    
    function clear_form(){
        $('#txt_subscriber_no,#txt_subscriber_name,#txt_emp_id,#txt_pposting_month').val('');
        $('#dp_payment_status,#dp_type_job,#dp_branch_id,#dp_dues_type').select2('val',0);
    }
    
</script>

SCRIPT;
sec_scripts($scripts);

?>
