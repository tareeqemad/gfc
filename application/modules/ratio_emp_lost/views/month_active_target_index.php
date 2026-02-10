<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 20/02/23
 * Time: 12:40 م
 */

$MODULE_NAME= 'ratio_emp_lost';
$TB_NAME= 'Month_active_target';

$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");

?>
<script> var show_page=true; </script>

<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title"><?= $title ?></h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">مشروع الفاقد</a></li>
            <li class="breadcrumb-item active" aria-current="page">المستهدف الشهري للأنشطة</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->
<!-- Row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex py-3">
                <div class="mb-0 flex-grow-1 card-title">
                    <?= $title ?>
                </div>
                <div class="flex-shrink-0">
                    <?php if ( HaveAccess($create_url)  ) : ?>
                        <a class="btn btn-info" href="<?= $create_url ?>"><i class='glyphicon glyphicon-plus'></i>جديد </a>
                    <?php endif; ?>
                </div>
            </div><!-- end card header -->
            <div class="card-body">

                <form class="form-vertical" id="<?=$TB_NAME?>_form" >
                    <div class="row">

                        <div class="form-group col-sm-1">
                            <label>رقم الطلب</label>
                            <input type="text" id="txt_target_no"  name="target_no" class="form-control" />
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label"> المقر</label>
                            <select name="branch_no" id="dp_branch_no" class="form-control sel2">
                                <option value="">_______</option>
                                <?php foreach($branches as $row) :?>
                                    <option value="<?= $row['NO'] ?>" > <?= $row['NAME'] ?> </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>النشاط</label>
                            <div>
                                <select type="text" name="activity_no" id="dp_activity_no" class="form-control sel2" >
                                    <option value="">__________</option>
                                    <?php foreach ($activity_no as $row) : ?>
                                        <option  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-1">
                            <label class="control-label">الشهر</label>
                            <div>
                                <input type="text" value="<?= date('Ym') ?>" name="the_month" id="txt_the_month" class="form-control">
                            </div>
                        </div>

                    </div>
                </form>
                <br>
                <div class="flex-shrink-0">
                    <button type="button" onclick="javascript:search();" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> إستعلام</button>
                    <button type="button" onclick="$('#month_active_target_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success"><i class="fas fa-file-excel"></i>إكسل</button>
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
    
    $('#txt_the_month').datetimepicker({
        format: 'YYYYMM',
        minViewMode: "months",
        pickTime: false
    });
    
    function values_search(add_page){
        var values= {page:1,target_no:$('#txt_target_no').val(),branch_no:$('#dp_branch_no').val(),activity_no:$('#dp_activity_no').val(),the_month:$('#txt_the_month').val(),monthly_target:$('#txt_monthly_target').val(),discount_value:$('#txt_discount_value').val()};
        if(add_page==0)
            delete values.page;
        return values;
    }

    function search(){
        var values= values_search(1);
        if ( $('#txt_the_month').val() == '' ) {
            danger_msg('رسالة','يجب اختيار الشهر  ..');
            return;
        }
        
        get_data('{$get_page_url}', values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
        var values= values_search(0);
        ajax_pager_data('#month_active_target_tb > tbody',values);
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('#dp_activity_no ,#dp_branch_no').select2('val',0);
    }
    
    function show_row_details(id){
        get_to_link('{$get_url}/'+id);
    }
    
</script>

SCRIPT;
sec_scripts($scripts);

?>
