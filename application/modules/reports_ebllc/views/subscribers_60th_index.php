<?php

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 28/02/23
 * Time: 10:40 ص
 */

$MODULE_NAME= 'reports_ebllc';
$TB_NAME= 'Subscribers_60th';

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
                            <label>حالة الاستهداف</label>
                            <select type="text" name="targeting_status" id="dp_targeting_status" class="form-control sel2" >
                                <option value="">__________</option>
                                <?php foreach ($targeting_status as $row) : ?>
                                    <option value="<?=$row['VID']?>"><?=$row['VNAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>حالة العداد</label>
                            <select type="text" name="counter_status" id="dp_counter_status" class="form-control sel2" >
                                <option value="">__________</option>
                                <option value="1">مستهدف</option>
                                <option value="2">غير مستهدف</option>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label>حالة التركيب</label>
                            <select name="status" id="dp_status" class="form-control sel2">
                                <option value="">_______</option>
                                <?php foreach ($status as $row) : ?>
                                    <option value="<?=$row['VID']?>"><?=$row['VNAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>حالة التركيب(معاملة تغيير عداد)</label>
                            <select type="text" name="installation_status" id="dp_installation_status" class="form-control sel2" >
                                <option value="">__________</option>
                                <?php foreach ($installation_status as $row) : ?>
                                    <option value="<?=$row['VID']?>"><?=$row['VNAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>رقم الاشتراك</label>
                            <div>
                                <input type="text"  name="subscriber_no" id="txt_subscriber_no" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
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

                        <div class="form-group col-sm-2">
                            <label>التصنيف الرئيسي</label>
                            <select type="text" name="classification_institutions" id="dp_classification_institutions" class="form-control sel2" >
                                <option value="">__________</option>
                                <?php foreach ($classification_institutions as $row) : ?>
                                    <option value="<?=$row['VID']?>"><?=$row['VNAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>نوع الاشتراك</label>
                            <select type="text" name="bill_type" id="dp_bill_type" class="form-control sel2" >
                                <option value="">__________</option>
                                <?php foreach ($bill_type as $row) : ?>
                                    <option value="<?=$row['VID']?>"><?=$row['VNAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <div class="form-group col-md-1">
                            <label for="dl_op">النسبة</label>
                            <select class="form-control" id="txt_ratio" name="ratio">
                                <option value="">---</option>
                                <option value=">">></option>
                                <option value="<"><</option>
                                <option value=">=">>=</option>
                                <option value="=">=</option>
                                <option value="!=">!=</option>
                            </select>
                        </div>

                        <div class="form-group col-sm-1">
                            <label>المتأخرات</label>
                            <div>
                                <input type="text" value="" name="arrears" id="txt_arrears" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" />
                            </div>
                        </div>

                        <div class="form-group col-sm-1">
                            <label>الاستهلاك</label>
                            <div>
                                <input type="text" value="" name="consumption" id="txt_consumption" class="form-control"  onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                            </div>
                        </div>

                        <div class="form-group col-sm-1">
                            <label>من شهر</label>
                            <div>
                                <input type="text" name="form_month" id="txt_form_month" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group col-sm-1">
                            <label>الى شهر</label>
                            <div>
                                <input type="text" name="to_month" id="txt_to_month" class="form-control" />
                            </div>
                        </div>

                    </div>
                </form>
                <br>
                <div class="flex-shrink-0">
                    <button type="button" onclick="javascript:search();" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> إستعلام</button>
                    <button type="button" onclick="$('#subscribers_60th_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success"><i class="fas fa-file-excel"></i>إكسل شاشة</button>
<!--                    <button type="button" onclick="javascript:print_report_pdf();" class="btn btn-blue"><i class="fa fa-print"></i>طباعة التقرير التفصيلي</button>-->
<!--                    <button type="button" onclick="javascript:print_report_xls();" class="btn btn-success"><i class="fas fa-file-excel"></i>إكسل التقرير التفصيلي</button>-->
<!--                    <button type="button" onclick="javascript:print_report_total_xls();" class="btn btn-success"><i class="fas fa-file-excel"></i>إكسل التقرير الاجمالي</button>-->
<!--                    <button type="button" onclick="javascript:print_report_total_pdf();" class="btn btn-blue"><i class="fa fa-print"></i>طباعة التقرير الاجمالي</button>-->
                    <button type="button" onclick="javascript:clear_form();" class="btn btn-cyan-light"><i class="fas fa-eraser"></i>تفريغ الحقول</button>
                </div>
                <hr>
                <div id="container">

                </div>
            </div><!--end card body-->
        </div><!--end card --->
    </div><!--end col lg-12--->
</div><!--end row--->

<!-- Modal -->
<div class="modal fade" id="DetailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">التحصيلات</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="public-modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" onclick="$('#collections_tbl').tableExport({type:'excel',escape:'false'});" class="btn btn-success"><i class="fas fa-file-excel"></i>إكسل</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
            </div>
        </div>
    </div>
</div>


<?php
$scripts = <<<SCRIPT

<script type="text/javascript">
    $('.sel2').select2();

    $('#txt_form_month ,#txt_to_month').datetimepicker({
        format: 'YYYYMM',
        minViewMode: "months",
        pickTime: false
    });

    function values_search(add_page){
        var values= {page:1,branch_id:$('#dp_branch_id').val(),subscriber_no:$('#txt_subscriber_no').val() ,form_month:$('#txt_form_month').val() ,to_month:$('#txt_to_month').val() ,status:$('#dp_status').val() ,arrears:$('#txt_arrears').val() ,consumption:$('#txt_consumption').val() ,ratio:$('#txt_ratio').val(),bill_type:$('#dp_bill_type').val() ,classification_institutions:$('#dp_classification_institutions').val(),vase_type:$('#dp_vase_type').val(),counter_type:$('#dp_counter_type').val(),counter_status:$('#dp_counter_status').val(),installation_status:$('#dp_installation_status').val(),targeting_status:$('#dp_targeting_status').val()};
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
                
        if(($('#txt_arrears').val() != '' || $('#txt_consumption').val() != '') && ($('#txt_ratio').val() == '') ){
            danger_msg('رسالة','يجب تحديد النسبة ..');
            return;
        }
        get_data('{$get_page_url}', values ,function(data){
            $('#container').html(data);
        },'html');
    }
    
    function LoadingData(){
        var values= values_search(0);
        ajax_pager_data('#subscribers_60th_tb > tbody',values);
    }
    
    function clear_form(){
        $('#dp_branch_id').select2('val',0);
    }
    
    function print_report_pdf(){
        var branch_no = $('#dp_branch_id').val();
        var subscriber_no = $('#txt_subscriber_no').val();
        var form_month = $('#txt_form_month').val();
        var to_month = $('#txt_to_month').val();
        
        _showReport('{$report_url}&report_type=pdf&report=target_sub_smart_pdf&p_branch='+branch_no+'&p_sub='+subscriber_no+'&p_from_min_smart_mon='+form_month+'&p_to_min_smart_mon='+to_month);
    }
    
    function print_report_xls(){
        var branch_no = $('#dp_branch_id').val();
        var subscriber_no = $('#txt_subscriber_no').val();
        var form_month = $('#txt_form_month').val();
        var to_month = $('#txt_to_month').val();
        
        _showReport('{$report_url}&report_type=xls&report=target_sub_smart_xls&p_branch='+branch_no+'&p_sub='+subscriber_no+'&p_from_min_smart_mon='+form_month+'&p_to_min_smart_mon='+to_month);
    }
    
    function print_report_total_pdf(){
        _showReport('{$report_url}&report_type=pdf&report=target_sub_smart_totals_pdf');
    }
    
    function print_report_total_xls(){
        _showReport('{$report_url}&report_type=xls&report=target_sub_smart_totals_xls');
    }

    function show_detail_row(subscriber,from_month){
        $('#DetailModal').modal('show');
        $.ajax({
            url: '{$get_detail}',
            type: 'post',
            data: {subscriber : subscriber ,from_month : from_month },
            success: function(response){
                $('#public-modal-body').html(response);
            }
        });
    }
    
</script>

SCRIPT;
sec_scripts($scripts);

?>
