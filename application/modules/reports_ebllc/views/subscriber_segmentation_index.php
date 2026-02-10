<?php

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 17/05/23
 * Time: 10:00 ص
 */

$MODULE_NAME= 'reports_ebllc';
$TB_NAME= 'Subscriber_segmentation';

$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$report_url = base_url("JsperReport/showreport")."?sys=Trading/dexcen";
$get_detail = base_url("$MODULE_NAME/$TB_NAME/public_get_detail");
$gfc_domain= gh_gfc_domain();
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
                    <a class="btn btn-warning"  href='<?= $gfc_domain ?>/Commercial/index_add_ded' target="_blank"></i>إقراض 50 شيكل</a>
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
                            <label>رقم الاشتراك الرئيسي</label>
                            <div>
                                <input type="text"  name="subscriber_no_main" id="txt_subscriber_no_main" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>رقم الاشتراك الفرعي</label>
                            <div>
                                <input type="text"  name="subscriber_no_sub" id="txt_subscriber_no_sub" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>نوع الفاز</label>
                            <select type="text" name="vase_type" id="dp_vase_type" class="form-control sel2" >
                                <option value="">__________</option>
                                <?php foreach ($vase_type as $row) : ?>
                                    <option value="<?=$row['VID']?>"><?=$row['VNAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>
                </form>
                <br>
                <div class="flex-shrink-0">
                    <button type="button" onclick="javascript:search();" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> إستعلام</button>
                    <button type="button" onclick="$('#subscriber_segmentation_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success"><i class="fas fa-file-excel"></i>إكسل شاشة</button>
                    <button type="button" onclick="javascript:clear_form();" class="btn btn-cyan-light"><i class="fas fa-eraser"></i>تفريغ الحقول</button>
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

    function values_search(add_page){
        var values= {page:1,branch_id:$('#dp_branch_id').val(),subscriber_no_main:$('#txt_subscriber_no_main').val() ,subscriber_no_sub:$('#txt_subscriber_no_sub').val() ,vase_type:$('#dp_vase_type').val()};
        if(add_page==0)
            delete values.page;
        return values;
    }
    
    function search(){
        var values= values_search(1);
        
        if($('#dp_branch_id').val() == '' ){
            danger_msg('رسالة','يجب تحديد المقر ..');
            return;
        }
        get_data('{$get_page_url}', values ,function(data){
            $('#container').html(data);
        },'html');
    }
    
    function LoadingData(){
        var values= values_search(0);
        ajax_pager_data('#subscriber_segmentation_tb > tbody',values);
    }
    
    function clear_form(){
        $('#dp_branch_id').select2('val',0);
    }
    
</script>

SCRIPT;
sec_scripts($scripts);

?>
