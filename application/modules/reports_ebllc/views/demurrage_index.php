<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 11/07/23
 * Time: 13:30 م
 */

$MODULE_NAME= 'reports_ebllc';
$TB_NAME= 'Demurrage';

$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
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
                    <?= $title ?>
                </div>
                <div class="flex-shrink-0">
                </div>
            </div><!-- end card header -->
            <div class="card-body">

                <form class="form-vertical" id="<?=$TB_NAME?>_form" >
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
                                <input type="text"  name="subscriber_no" id="txt_subscriber_no" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                            </div>
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

                        <div class="form-group col-sm-2">
                            <label>المتأخرات</label>
                            <div>
                                <input type="text"  name="remainder" id="txt_remainder" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>مبلغ الغرامة</label>
                            <div>
                                <input type="text"  name="delay" id="txt_delay" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                            </div>
                        </div>

                        <div class="form-group col-md-1">
                            <label for="dl_op">المتأخرات / الغرامة</label>
                            <select class="form-control" id="txt_operation" name="operation">
                                <option value="">---</option>
                                <option value=">">></option>
                                <option value="<"><</option>
                                <option value=">=">>=</option>
                                <option value="=">=</option>
                                <option value="!=">!=</option>
                            </select>
                        </div>

                        <div class="form-group col-md-1">
                            <label for="dl_op">المبلغ المطلوب / الغرامة</label>
                            <select class="form-control" id="txt_op_net_to_pay" name="op_net_to_pay">
                                <option value="">---</option>
                                <option value=">">></option>
                                <option value="<"><</option>
                                <option value=">=">>=</option>
                                <option value="=">=</option>
                                <option value="!=">!=</option>
                            </select>
                        </div>

                    </div>
                </form>
                <br>
                <div class="flex-shrink-0">
                    <button type="button" onclick="javascript:search();" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> إستعلام</button>
                    <button type="button" onclick="$('#demurrage_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success"><i class="fas fa-file-excel"></i>إكسل</button>
                    <button type="button" onclick="javascript:clear_form();"  class="btn btn-cyan-light"><i class="fas fa-eraser"></i>تفريغ الحقول</button>
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
        var values= { page:1,branch_id:$('#dp_branch_id').val(), subscriber_no:$('#txt_subscriber_no').val(), bill_type:$('#dp_bill_type').val(),ratio:$('#txt_ratio').val(),remainder:$('#txt_remainder').val(),delay:$('#txt_delay').val(),operation:$('#txt_operation').val(),op_net_to_pay:$('#txt_op_net_to_pay').val()};
        if(add_page==0)
            delete values.page;
        return values;
    }

    function search(){
        var values= values_search(1);
                
        if(($('#txt_remainder').val() != '' || $('#txt_delay').val() != '') && ($('#txt_ratio').val() == '') ){
            danger_msg('رسالة','يجب تحديد النسبة ..');
        return;
        }
        
        get_data('{$get_page_url}', values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
        var values= values_search(0);
        ajax_pager_data('#demurrage_tb > tbody',values);
    }

    function clear_form(){
        $('#txt_subscriber_no').val('');
        $('#dp_branch_id,#dp_bill_type').select2('val',0);
    }

</script>

SCRIPT;
sec_scripts($scripts);

?>
