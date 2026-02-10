<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 23/03/23
 * Time: 10:40 ص
 */

$MODULE_NAME= 'salary';
$TB_NAME= 'Exceptional_credit';

$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$get_team = base_url("$MODULE_NAME/$TB_NAME/public_get_team");
?>
<script> var show_page=true; </script>

<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title"><?= $title ?></h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">بدل الاتصال</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
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
                    <?php if (HaveAccess($create_url) ) : ?>
                        <a class="btn btn-info" href="<?= $create_url ?>"><i class='glyphicon glyphicon-plus'></i>جديد </a>
                    <?php endif; ?>
                </div>
            </div><!-- end card header -->
            <div class="card-body">

                <form class="form-vertical" id="<?=$TB_NAME?>_form" >
                    <div class="row">

                        <div class="form-group col-sm-1">
                            <label>الرقم التسلسلي</label>
                            <input type="text" name="ser" id="txt_ser" class="form-control" />
                        </div>

                        <div class="form-group col-md-2">
                            <label> المقر</label>
                            <select name="branch_no" id="dp_branch_no" class="form-control sel2">
                                <option value="">_______</option>
                                <?php foreach ($branches as $row) : ?>
                                    <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>الشهر</label>
                            <div>
                                <input type="text"  value="<?= $current_date ?>" name="the_month" id="txt_the_month" class="form-control" >
                            </div>
                        </div>

                        <div class="form-group col-md-2">
                            <label>فئة الاتصال</label>
                            <select name="category" id="dp_category" class="form-control">
                                <option value="">_______</option>
                                <?php foreach ($category as $row) : ?>
                                    <option  value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>
                </form>
                <br>
                <div class="flex-shrink-0">
                    <button type="button" onclick="javascript:search();" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> إستعلام</button>
                    <button type="button" onclick="$('#exceptional_credit_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success"><i class="fas fa-file-excel"></i>إكسل</button>
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
        var values= {page:1,ser:$('#txt_ser').val(),branch_no:$('#dp_branch_no').val(),the_month:$('#txt_the_month').val(),category:$('#dp_category').val()};
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
        ajax_pager_data('#exceptional_credit_tb > tbody',values);
    }
    
    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('#dp_work ,#dp_team').select2('val',0);
    }
    
    function show_row_details(id){
        get_to_link('{$get_url}/'+id);
    }
    
</script>

SCRIPT;
sec_scripts($scripts);

?>
