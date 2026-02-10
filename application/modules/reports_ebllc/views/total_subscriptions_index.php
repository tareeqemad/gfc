<?php

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 01/12/22
 * Time: 10:00 ص
 */

$MODULE_NAME= 'reports_ebllc';
$TB_NAME= 'Total_subscriptions';

$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
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

                        <div class="form-group col-sm-1">
                            <label>الشهر</label>
                            <div>
                                <input type="text" value="<?=date('Ym',strtotime("-1 month"));?>" name="the_month" id="txt_the_month" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group col-md-2">
                            <label>من تاريخ </label>
                            <input type="text" <?= $date_attr ?> value="<?=date('d/m/Y',strtotime("first day of"));?>"  name="from_the_date" id="txt_from_the_date" class="form-control">
                        </div>

                        <div class="form-group col-md-2">
                            <label>الى تاريخ </label>
                            <input type="text" <?= $date_attr ?> value="<?=date('d/m/Y',strtotime("last day of"));?>" name="to_the_date" id="txt_to_the_date" class="form-control">
                        </div>

                        <div class="form-group col-md-2">
                            <label>نوع الاشتراك</label>
                            <select name="subscriber_type" id="dp_subscriber_type" class="form-control sel2">
                                <option value="">_______</option>
                                <?php foreach ($subscriber_type as $row) : ?>
                                    <option value="<?=$row['VID']?>"><?=$row['VNAME']?></option>
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
                    <button type="button" onclick="$('#total_subscriptions_tb').tableExport({type:'excel',escape:'false'});"
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
       
    $('#txt_the_month').datetimepicker({
        format: 'YYYYMM',
        minViewMode: "months",
        pickTime: false
    });
    
    function values_search(add_page){
        var values= {page:1,the_month:$('#txt_the_month').val(),from_the_date:$('#txt_from_the_date').val(),to_the_date:$('#txt_to_the_date').val(),subscriber_type:$('#dp_subscriber_type').val()};
        if(add_page==0)
            delete values.page;
        return values;
    }
    
    function search(){
        var values= values_search(1);
                
        if ($('#txt_the_month').val() == '' ) {
            danger_msg('رسالة','يجب ادخال الشهر ..');
            return;
        }else if ($('#txt_from_the_date').val() == '' || $('#txt_to_the_date').val() == ''){
            danger_msg('رسالة','يجب ادخال الفترة من تاريخ الى تاريخ ..');
            return;
        } else{
            get_data('{$get_page_url}', values ,function(data){
                $('#container').html(data);
            },'html');
        }
    }
    
    function LoadingData(){
        var values= values_search(0);
        ajax_pager_data('#total_subscriptions_tb > tbody',values);
    }
    
    function clear_form(){
        $('#txt_the_month,#txt_from_the_date,#txt_to_the_date').val('');
        $('#dp_subscriber_type').select2('val',0);
    }
    
</script>

SCRIPT;
sec_scripts($scripts);

?>
