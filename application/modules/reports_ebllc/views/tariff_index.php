<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 11/07/23
 * Time: 13:30 م
 */

$MODULE_NAME= 'reports_ebllc';
$TB_NAME= 'Tariff';

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
                            <label>اسم المشترك</label>
                            <div>
                                <input type="text"  name="subscriber_name" id="txt_subscriber_name" class="form-control"/>
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

                        <div class="form-group col-md-2">
                            <label for="dl_op">تعرفة KW/ تعرفة الهولي</label>
                            <select class="form-control" id="txt_ratio" name="ratio">
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
                    <button type="button" onclick="$('#tariff_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success"><i class="fas fa-file-excel"></i>إكسل</button>
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
    
    $(function(){
        reBind();
    });
    
    function reBind(){
    ajax_pager({
            branch_id:$('#dp_branch_id').val(),subscriber_no:$('#txt_subscriber_no').val(),subscriber_name:$('#txt_subscriber_name').val(),bill_type:$('#dp_bill_type').val(),ratio:$('#txt_ratio').val()
        });
    }
    
    function LoadingData(){
        ajax_pager_data('#tariff_tb > tbody',{
            branch_id:$('#dp_branch_id').val(),subscriber_no:$('#txt_subscriber_no').val(),subscriber_name:$('#txt_subscriber_name').val(),bill_type:$('#dp_bill_type').val(),ratio:$('#txt_ratio').val()
        });
    }
    
    function search(){
        get_data('{$get_page_url}',{page: 1 ,branch_id:$('#dp_branch_id').val(),subscriber_no:$('#txt_subscriber_no').val(),subscriber_name:$('#txt_subscriber_name').val(),bill_type:$('#dp_bill_type').val(),ratio:$('#txt_ratio').val()},function(data){
            $('#container').html(data);
            reBind();
        },'html');
    }

    function clear_form(){
        $('#txt_subscriber_no,#txt_subscriber_name').val('');
        $('#dp_branch_id,#dp_bill_type').select2('val',0);
    }

</script>

SCRIPT;
sec_scripts($scripts);

?>
