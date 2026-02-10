<?php

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 06/03/23
 * Time: 09:30 ص
 */

$MODULE_NAME= 'reports_ebllc';
$TB_NAME= 'All_services_master';

$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$get_id = base_url("$MODULE_NAME/$TB_NAME/public_get_id");

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


                        <div class="form-group col-sm-1">
                            <label>الشهر</label>
                            <div>
                                <input type="text" name="for_month" id="txt_for_month" value="<?=date('Ym',strtotime("-1 month"));?>" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group col-md-2">
                            <label> آلية السداد</label>
                            <select name="is_aly" id="dp_is_aly" class="form-control sel2">
                                <option value="">_______</option>
                                <?php foreach ($is_aly as $row) : ?>
                                    <option value="<?= $row['VID'] ?>"><?= $row['VNAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label> الحالة</label>
                            <select name="status" id="dp_status" class="form-control sel2">
                                <option value="">_______</option>
                                <?php foreach ($status as $row) : ?>
                                    <option value="<?= $row['VID'] ?>"><?= $row['VNAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-sm-2">
                            <label> نوع الحالة</label>
                            <select type="text" name="status_type" id="dp_status_type" class="form-control sel2"  >
                                <option value="">__________</option>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label> نوع الاشتراك</label>
                            <select name="bill_type[]" id="dp_bill_type" class="form-control sel2" multiple>
                                <option value="">_______</option>
                                <?php foreach ($bill_type as $row) : ?>
                                    <option value="<?= $row['VID'] ?>"><?= $row['VNAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>
                </form>
                <br>
                <div class="flex-shrink-0">
                    <button type="button" onclick="javascript:search();" class="btn btn-primary"><i
                            class="glyphicon glyphicon-search"></i> إستعلام
                    </button>
                    <button type="button" onclick="$('#all_services_master_tb').tableExport({type:'excel',escape:'false'});"
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
    
    $('#txt_for_month').datetimepicker({
        format: 'YYYYMM',
        minViewMode: "months",
        pickTime: false
    });
    
    //change_region
    $("#dp_status").change(function(){
        var No = $(this).val();

        if(No == 0 ) {
            $('#dp_status_type option').remove();
            $('#dp_status_type').append($('<option/>').attr("value", '0').text('---اختر---'));
        }else {
            $.ajax({
                url: '{$get_id}/'+No,
                type: 'POST',
                datatype: 'json',
                success: function (data) {
                    console.log(data);
                    if(data.length == 0){
                        $('#dp_status_type option').remove();
                        $('#dp_status_type').append($('<option/>').attr("value", '').text('---اختر---'));
                        $('#dp_status_type').prop("selected",true);
                    }else{
                        $('#dp_status_type option').remove();
                        $('#dp_status_type').append($('<option/>').attr("value", '').text('---اختر---'));
                        $.each(JSON.parse(data),function (i,option) {
                            $('#dp_status_type').append($('<option/>').attr("value", option.NO).text(option.NAME));
                        })
                        $("#dp_status_type").change();
                    }
                    $("#dp_status_type").select2();
                },
                error: function (jqXHR, textStatus, errorThrown) {  }
            });
        }
    });
    
    function values_search(add_page){
        var values= {page:1,for_month:$('#txt_for_month').val(),is_aly:$('#dp_is_aly').val(),status:$('#dp_status').val(),status_type:$('#dp_status_type').val(),bill_type:$('#dp_bill_type').val(),branch_id:$('#dp_branch_id').val()};
   
        if(add_page==0)
            delete values.page;
        return values;
    }
    
    function search(){
        var values= values_search(1);
        if ($('#txt_for_month').val() == ''){
            danger_msg('رسالة','يجب ادخال الشهر ..');
            return;
        }else if ($('#dp_branch_id').val() == ''){
            danger_msg('رسالة','يجب اختيار المقر ..');
            return;
        }else {
            get_data('{$get_page_url}', values ,function(data){
                $('#container').html(data);
            },'html');
        }

    }
    
    function LoadingData(){
        var values= values_search(0);
        ajax_pager_data('#all_services_master_tb > tbody',values);
    }
    
    function clear_form(){
        $('#dp_is_aly ,#dp_status ,#dp_status_type ,#dp_bill_type').select2('val',0);
    }
    
</script>

SCRIPT;
sec_scripts($scripts);

?>
