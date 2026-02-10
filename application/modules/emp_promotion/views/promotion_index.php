<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 30/05/2020
 * Time: 9:33 ص
 */
$MODULE_NAME = 'emp_promotion';
$TB_NAME = 'promotion';
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$branch_query_url = base_url("$MODULE_NAME/$TB_NAME/select_branch_query");
$date_attr = " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='" . date_format_exp() . "' data-val-regex='Error' ";
$delete_url = base_url("$MODULE_NAME/$TB_NAME/delete");
echo AntiForgeryToken();
?>
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title"><?= $title ?></h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">الترقيات</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->
<!-- Row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">استعلام</h3>
                <div class="card-options">
                    <?php if (HaveAccess($create_url)): ?>
                        <a class="btn btn-secondary" href="<?= $create_url ?>"><i class="fa fa-plus"></i>
                            جديد
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <form id="<?= $TB_NAME ?>_form">
                    <div class="row">
                        <?php if ($this->user->branch == 1) { ?>
                            <div class="form-group col-md-2">
                                <label> المقر</label>
                                <select name="branch_no" id="dp_branch_no" class="form-control sel2">
                                    <option value="">_______</option>
                                    <?php foreach ($branches as $row) : ?>
                                        <option value="<?= $row['NO'] ?>"> <?= $row['NAME'] ?> </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php } else { ?>
                            <input type="hidden" name="branch_no" id="dp_branch_no" value="<?= $this->user->branch ?>">
                        <?php } ?>
                        <div class="form-group col-md-2">
                            <label>الموظف</label>
                            <select name="emp_no" id="dp_emp_no" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($emp_no_cons as $row) : ?>
                                    <option value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ': ' . $row['EMP_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>السنة</label>
                            <div>
                                <input type="text" placeholder="السنة"
                                       name="yyears"
                                       id="txt_yyears" class="form-control" value="<?= date('Y') ?>">
                            </div>
                        </div>


                        <div class="form-group col-md-2">
                            <label>الدائرة</label>
                            <select name="head_department" id="dp_head_department" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($head_department_cons as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <div class="form-group col-md-2">
                            <label>التوصية</label>
                            <select name="status" id="dp_status" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($status_cons as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <div class="form-group col-md-2">
                            <label>حالة الاعتماد</label>
                            <select name="degree_adopt" id="dp_degree_adopt" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($adopt_cons as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="flex-shrink-0">
                            <button type="button" onclick="javascript:search();" class="btn btn-primary">
                                <i class="fa fa-search"></i>
                                إستعلام
                            </button>

                            <button type="button" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success">
                                <i class="fa fa-file-excel-o"></i>
                                إكسل
                            </button>
                        </div>
                    </div>
                </form>
                <hr>
                <div id="container">
                    <?php  echo  modules::run($get_page_url,$page); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">


      $('.sel2:not("[id^=\'s2\']")').select2();

    
     $('#txt_yyears').datetimepicker({
            format: 'YYYY',
            minViewMode: 'years',
            pickTime: false,
            
        });
    
     
    $(function(){
        reBind();
    });
    
     function reBind(){
      ajax_pager({
                branch_no:$('#dp_branch_no').val(),
                yyears:$('#txt_yyears').val(),
                emp_no:$('#dp_emp_no').val(),
                head_department:$('#dp_head_department').val(),
                degree_adopt :$('#dp_degree_adopt').val(),
                status:$('#dp_status').val()   
      });
    }

    function LoadingData(){
        ajax_pager_data('#page_tb > tbody',{
               branch_no:$('#dp_branch_no').val(),
                yyears:$('#txt_yyears').val(),
                emp_no:$('#dp_emp_no').val(),
                head_department:$('#dp_head_department').val(),
                degree_adopt :$('#dp_degree_adopt').val(),
                status:$('#dp_status').val()
        });
    }

     function search(){
        get_data('{$get_page_url}',{page: 1,branch_no:$('#dp_branch_no').val(),yyears : $('#txt_yyears').val(),emp_no:$('#dp_emp_no').val(),head_department:$('#dp_head_department').val(),degree_adopt:$('#dp_degree_adopt').val(),status:$('#dp_status').val()},function(data){
            $('#container').html(data);
            reBind();
        },'html');
    }
     function delete_prototype(a,ser){
        if(confirm('هل متأكد من عملية حذف النموذج ؟')){
           get_data('{$delete_url}',{ser:ser},function(data){
                $('#container').html(data);
                if(data == '1'){
                    success_msg('رسالة','تم الحذف  بنجاح ..');
                    $(a).closest('tr').remove();
                }
            },'html');
        }
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>

