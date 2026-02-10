<?php

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 13/12/22
 * Time: 10:00 ص
 */

$MODULE_NAME= 'reports_ebllc';
$TB_NAME= 'Billing_data';

$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");

$date_attr = " data-type='date' data-date-format='YYYYMM' data-val='true' data-val-regex-pattern='" . date_format_exp() . "' data-val-regex='Error' ";
$get_id_twoDet = base_url("$MODULE_NAME/$TB_NAME/public_get_twoDet");
$get_id_two = base_url("$MODULE_NAME/$TB_NAME/public_get_two");
$get_id_two_region = base_url("$MODULE_NAME/$TB_NAME/public_get_two_region");
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
                                <input type="text" name="for_month" id="txt_for_month" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                            </div>
                        </div>

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
                            <label>رقم الاشتراك</label>
                            <div>
                                <input type="text" value="" name="subscriber_no" id="txt_subscriber_no" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" />
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>تصنيف المؤسسات</label>
                            <select type="text" name="classification_institutions" id="dp_classification_institutions" class="form-control sel2" >
                                <option value="">__________</option>
                                <?php foreach ($classification_institutions as $row) : ?>
                                    <option value="<?=$row['VID']?>"><?=$row['VNAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>المؤسسات</label>
                            <select type="text" name="institutions" id="dp_institutions" class="form-control sel2" >
                                <option value="">__________</option>
                                <?php foreach ($institutions as $row) : ?>
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

                        <div class="form-group col-sm-2">
                            <label>المنطقة</label>
                            <select type="text" name="region" id="dp_region" class="form-control sel2" >
                                <option value="">__________</option>

                            </select>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>مفتاح المؤسسة</label>
                            <select type="text" name="enterprise_key" id="dp_enterprise_key" class="form-control sel2" >
                                <option value="">__________</option>
                                <?php foreach ($enterprise_key as $row) : ?>
                                    <option value="<?=$row['VID']?>"><?=$row['VNAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>البلدية</label>
                            <select type="text" name="municipal" id="dp_municipal" class="form-control" >
                                <option value="">__________</option>
                            </select>
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

                        <div class="form-group col-sm-2">
                            <label>المتأخرات</label>
                            <div>
                                <input type="text" value="" name="arrears" id="txt_arrears" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" />
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>المطلوب</label>
                            <div>
                                <input type="text" value="" name="required" id="txt_required" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" />
                            </div>
                        </div>

                        <div class="form-group col-sm-1">
                            <label>الاستهلاك</label>
                            <div>
                                <input type="text" value="" name="consumption" id="txt_consumption" class="form-control"  onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                            </div>
                        </div>

                    </div>
                </form>
                <br>
                <div class="flex-shrink-0">
                    <button type="button" onclick="javascript:search();" class="btn btn-primary"><i
                            class="glyphicon glyphicon-search"></i> إستعلام
                    </button>
                    <button type="button" onclick="$('#billing_data_tb').tableExport({type:'excel',escape:'false'});"
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
    
     ///change_municipal
    $("#dp_branch_id").change(function(){
        var No = $(this).val();
        if(No == 0 ) {
            $('#dp_municipal option').remove();
            $('#dp_municipal').append($('<option/>').attr("value", '0').text('---اختر---'));
        }else {
            $.ajax({
                url: '{$get_id_twoDet}/'+No,
                type: 'POST',
                datatype: 'json',
                success: function (data) {
                    if(data.length == 0){
                        $('#dp_municipal option').remove();
                        $('#dp_municipal').append($('<option/>').attr("value", '').text('---اختر---'));
                        $('#dp_municipal').prop("selected",true);
                    }else{
                        $('#dp_municipal option').remove();
                        $('#dp_municipal').append($('<option/>').attr("value", '').text('---اختر---'));
                        $.each(JSON.parse(data),function (i,option) {
                            $('#dp_municipal').append($('<option/>').attr("value", option.VID).text(option.VNAME));
                        })
                        $("#dp_municipal").change();
                    }
                    $("#dp_municipal").select2();
                },
                error: function (jqXHR, textStatus, errorThrown) {  }
            });

        } //end if function
    }); //end of change_municipal

    ///change_region
    $("#dp_branch_id").change(function(){
        var No = $(this).val();
        if(No == 0 ) {
            $('#dp_region option').remove();
            $('#dp_region').append($('<option/>').attr("value", '0').text('---اختر---'));
        }else {
            $.ajax({
                url: '{$get_id_two_region}/'+No,
                type: 'POST',
                datatype: 'json',
                success: function (data) {
                    if(data.length == 0){
                        $('#dp_region option').remove();
                        $('#dp_region').append($('<option/>').attr("value", '').text('---اختر---'));
                        $('#dp_region').prop("selected",true);
                    }else{
                        $('#dp_region option').remove();
                        $('#dp_region').append($('<option/>').attr("value", '').text('---اختر---'));
                        $.each(JSON.parse(data),function (i,option) {
                            $('#dp_region').append($('<option/>').attr("value", option.VID).text(option.VNAME));
                        })
                        $("#dp_region").change();
                    }
                    $("#dp_region").select2();
                },
                error: function (jqXHR, textStatus, errorThrown) {  }
            });

        } //end if function
    }); //end of change_region
    
    
        ///change_institutions
    $("#dp_classification_institutions").change(function(){
        var No = $(this).val();
        if(No == 0 ) {
            $('#dp_institutions option').remove();
            $('#dp_institutions').append($('<option/>').attr("value", '0').text('---اختر---'));
        }else {
            $.ajax({
                url: '{$get_id_two}/'+No,
                type: 'POST',
                datatype: 'json',
                success: function (data) {
                    if(data.length == 0){
                        $('#dp_institutions option').remove();
                        $('#dp_institutions').append($('<option/>').attr("value", '').text('---اختر---'));
                        $('#dp_institutions').prop("selected",true);
                    }else{
                        $('#dp_institutions option').remove();
                        $('#dp_institutions').append($('<option/>').attr("value", '').text('---اختر---'));
                        $.each(JSON.parse(data),function (i,option) {
                            $('#dp_institutions').append($('<option/>').attr("value", option.VID).text(option.VNAME));
                        })
                        $("#dp_institutions").change();
                    }
                    $("#dp_institutions").select2();
                },
                error: function (jqXHR, textStatus, errorThrown) {  }
            });

        } //end if function
    }); //end of change_institutions
    
        
    function values_search(add_page){
        var values= {page:1,for_month:$('#txt_for_month').val(),branch_id:$('#dp_branch_id').val(),subscriber_no:$('#txt_subscriber_no').val(),payment_status:$('#dp_payment_status').val(),region:$('#dp_region').val(),enterprise_key:$('#dp_enterprise_key').val()
        ,municipal:$('#dp_municipal').val(),vase_type:$('#dp_vase_type').val(),consumption_price:$('#txt_consumption_price').val(),arrears:$('#txt_arrears').val(),required:$('#txt_required').val(),ratio:$('#txt_ratio').val(),consumption:$('#txt_consumption').val(),institutions:$('#dp_institutions').val(),classification_institutions:$('#dp_classification_institutions').val()};
        if(add_page==0)
            delete values.page;
        return values;
    }
    
    function search(){
        var values= values_search(1);
        
        if ($('#txt_for_month').val() == '' ) {
            danger_msg('رسالة','يجب ادخال الشهر ..');
            return;
        }else if(($('#txt_arrears').val() != '' ||  $('#txt_required').val() != '' || $('#txt_consumption').val() != '') && ($('#txt_ratio').val() == '') ){
            danger_msg('رسالة','يجب تحديد النسبة ..');
            return;
        }
        
        get_data('{$get_page_url}', values ,function(data){
            $('#container').html(data);
        },'html');
    }
    
    function LoadingData(){
        var values= values_search(0);
        ajax_pager_data('#billing_data_tb > tbody',values);
    }
    
    function clear_form(){
        $('#txt_for_month,#txt_subscriber_no,#txt_consumption_price,#txt_arrears,#txt_required,#txt_ratio,#txt_consumption').val('');
        $('#dp_branch_id,#dp_payment_status,#dp_region,#dp_enterprise_key,#dp_municipal,#dp_vase_type,#dp_institutions,#classification_institutions').select2('val',0);
    }

</script>

SCRIPT;
sec_scripts($scripts);

?>
