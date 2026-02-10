<?php

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 29/12/22
 * Time: 12:30 ص
 */

$MODULE_NAME= 'reports_ebllc';
$TB_NAME= 'Payment_status';

$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$public_get_detail = base_url("$MODULE_NAME/$TB_NAME/public_get_detail");

$date_attr = " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='" . date_format_exp() . "' data-val-regex='Error' ";
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
                                <option value="0">_______</option>
                                <?php foreach ($branches as $row) : ?>
                                    <option value="<?= $row['VID'] ?>"><?= $row['VNAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>نوع التسديد</label>
                            <select type="text" name="payment_type" id="dp_payment_type" class="form-control sel2" >
                                <option value="0">__________</option>
                                <?php foreach ($payment_type as $row) : ?>
                                    <option value="<?=$row['VID']?>"><?=$row['VNAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-sm-1">
                            <label>الشهر</label>
                            <div>
                                <input type="text" value="<?=date('Ym',strtotime("-1 month"));?>" name="the_month" id="txt_the_month" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group col-md-2">
                            <label>من تاريخ </label>
                            <input type="text" <?= $date_attr ?> value="<?=date('d/m/Y',strtotime("first day of"));?>"  name="from_date" id="txt_from_date" class="form-control">
                        </div>

                        <div class="form-group col-md-2">
                            <label>الى تاريخ </label>
                            <input type="text" <?= $date_attr ?> value="<?=date('d/m/Y',strtotime("last day of"));?>" name="to_date" id="txt_to_date" class="form-control">
                        </div>

                    </div>
                </form>
                <br>
                <div class="flex-shrink-0">
                    <button type="button" onclick="javascript:search();" class="btn btn-primary"><i
                            class="glyphicon glyphicon-search"></i> إستعلام
                    </button>
                    <button type="button" onclick="$('#payment_status_tb').tableExport({type:'excel',escape:'false'});"
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

<!-- Modal -->
<div class="modal fade" id="DetailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تفاصيل البنوك</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="public-modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" onclick="$('#banks_tb').tableExport({type:'excel',escape:'false'});"
                        class="btn btn-success"><i class="fas fa-file-excel"></i>إكسل
                </button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
            </div>
        </div>
    </div>
</div>

<?php
$scripts = <<<SCRIPT

<script type="text/javascript">
    $('.sel2').select2();
    
    $('#txt_the_month').datetimepicker({
        format: 'YYYYMM',
        minViewMode: "months",
        pickTime: false
    });
    
    function values_search(add_page){
        var values= {page:1,branch_id:$('#dp_branch_id').val(),payment_type:$('#dp_payment_type').val(),the_month:$('#txt_the_month').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val()};
        if(add_page==0)
            delete values.page;
        return values;
    }   
    
    function search(){
        var values= values_search(1);
    
        if ($('#txt_the_month').val() == ''){
            danger_msg('رسالة','يجب ادخال الشهر ..');
            return;
        }else if ($('#txt_from_date').val() == '' || $('#txt_to_date').val() == ''){
            danger_msg('رسالة','يجب ادخال الفترة من تاريخ الى تاريخ ..');
            return;
        }else{
            get_data('{$get_page_url}', values ,function(data){
                $('#container').html(data);
            },'html');
        }
    }
    
    function LoadingData(){
        var values= values_search(0);
        ajax_pager_data('#payment_status_tb > tbody',values);
    }
    
    function clear_form(){
        $('#txt_the_month,#txt_from_date,#txt_to_date').val('');
        $('#dp_payment_type,#dp_branch_id').select2('val',0);
    }
    
    function show_detail_row(bank_type){
        $('#DetailModal').modal('show');
        $.ajax({
            url: '{$public_get_detail}',
            type: 'post',
            data: {bank_type : bank_type , branch_id:$('#dp_branch_id').val() ,the_month:$('#txt_the_month').val() ,from_date:$('#txt_from_date').val() ,to_date :$('#txt_to_date').val() ,payment_type :$('#dp_payment_type').val()},
            success: function(response){
                $('#public-modal-body').html(response);
            }
        });
    }
</script>

SCRIPT;
sec_scripts($scripts);

?>
