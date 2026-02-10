<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 09/07/23
 * Time: 12:20 م
 */

$MODULE_NAME= 'salary';
$TB_NAME= 'Paid_fonts';

$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$date_attr= " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";

?>
<script> var show_page=true; </script>

<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title"><?= $title ?></h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">الرواتب</a></li>
            <li class="breadcrumb-item active" aria-current="page">بيانات الخطوط المدفوعة</li>
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
                    <?php if (HaveAccess($create_url)) : ?>
                        <a class="btn btn-info" href="<?= $create_url ?>"><i class='glyphicon glyphicon-plus'></i>جديد </a>
                    <?php endif; ?>
                </div>
            </div><!-- end card header -->
            <div class="card-body">

                <form class="form-vertical" id="<?=$TB_NAME?>_form" >
                    <div class="row">

                        <div class="form-group col-sm-2">
                            <label class="control-label">الموظف</label>
                            <div>
                                <select name="emp_no" id="dp_emp_no" class="form-control sel2">
                                    <option value="">_________</option>
                                    <?php foreach ($emp_no_cons as $row) : ?>
                                        <option value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ': ' . $row['EMP_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-2">
                            <label> المقر</label>
                            <select name="branch_id" id="dp_branch_id" class="form-control sel2">
                                <option value="">_______</option>
                                <?php foreach ($branches as $row) : ?>
                                    <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>نوع الشريحة</label>
                            <div>
                                <select name="slide_type" id="dp_slide_type" class="form-control sel2">
                                    <option value="">_________</option>
                                    <?php foreach ($slide_type as $row) : ?>
                                        <option  value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>الجهة المستفيدة</label>
                            <div>
                                <select name="beneficiary" id="dp_beneficiary" class="form-control sel2">
                                    <option value="">_________</option>
                                    <?php foreach ($beneficiary as $row) : ?>
                                        <option  value="<?=$row['ST_ID']?>"><?=$row['ST_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>حالة الخط</label>
                            <div>
                                <select name="line_status" id="dp_line_status" class="form-control sel2">
                                    <option value="">_________</option>
                                    <?php foreach ($line_status as $row) : ?>
                                        <option  value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                    </div>
                </form>
                <br>
                <div class="flex-shrink-0">
                    <button type="button" onclick="javascript:search();" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> إستعلام</button>
                    <button type="button" onclick="$('#paid_fonts_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success"><i class="fas fa-file-excel"></i>إكسل</button>
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
        var values= {page:1,emp_no:$('#dp_emp_no').val(),branch_id:$('#dp_branch_id').val(),slide_type:$('#dp_slide_type').val(),line_status:$('#dp_line_status').val(),beneficiary:$('#dp_beneficiary').val()};
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
        ajax_pager_data('#paid_fonts_tb > tbody',values);
    }

    function clear_form(){
        $('#dp_emp_no ,#dp_branch_id ,#dp_slide_type ,#dp_line_status').select2('val',0);
    }
    
    function show_row_details(id){
        get_to_link('{$get_url}/'+id);
    }
    
</script>

SCRIPT;
sec_scripts($scripts);

?>
