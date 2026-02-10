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
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page_hr");
$branch_query_url = base_url("$MODULE_NAME/$TB_NAME/select_branch_query");
$date_attr = " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='" . date_format_exp() . "' data-val-regex='Error' ";
$delete_url = base_url("$MODULE_NAME/$TB_NAME/delete");
$adopts_all_url = base_url("$MODULE_NAME/$TB_NAME/public_AdoptFinal");
echo AntiForgeryToken();
?>
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">طلبات تحتاج اعتماد ادارة الموارد البشرية</h1>
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
                    <h3 class="card-title">استعلام | ادارة الموارد البشرية</h3>
                    <div class="card-options">

                    </div>
                </div>
                <div class="card-body">
                    <form id="<?= $TB_NAME ?>_form">
                        <div class="row">
                            <div class="form-group col-sm-2">
                                <label class="control-label"> المقر</label>
                                <select name="branch_no" id="dp_branch_no" class="form-control sel2">
                                    <option value="">_______</option>
                                    <?php foreach ($branches as $row) : ?>
                                        <option value="<?= $row['NO'] ?>"> <?= $row['NAME'] ?> </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-sm-2">
                                <label class="control-label">السنة</label>
                                <div>
                                    <input type="text" placeholder="السنة"
                                           name="yyears"
                                           id="txt_yyears" class="form-control" value="<?= date('Y') ?>">
                                </div>
                            </div>
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
                            <div class="form-group col-sm-2">
                                <label class="control-label">الدائرة</label>
                                <div>
                                    <select name="head_department" id="dp_head_department" class="form-control sel2">
                                        <option value="">_________</option>
                                        <?php foreach ($head_department_cons as $row) : ?>
                                            <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-2">
                                <label class="control-label">التوصية</label>
                                <div>
                                    <select name="status" id="dp_status" class="form-control sel2">
                                        <option value="">_________</option>
                                        <?php foreach ($status_cons as $row) : ?>
                                            <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-2">
                                <label class="control-label">حالة الاعتماد</label>
                                <div>
                                    <select name="degree_adopt" id="dp_degree_adopt" class="form-control sel2">
                                        <option value="">_________</option>
                                        <?php foreach ($adopt_cons as $row) { ?>
                                            <option <?= $row['CON_NO'] == 40 ? 'selected' : '' ?>
                                                    value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <button type="button" onclick="javascript:search();" class="btn btn-primary me-2">
                                <i class="fa fa-search"></i>
                                إستعلام
                            </button>
                            <button type="button" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});"
                                    class="btn btn-success me-2">
                                <i class="fa fa-file-excel-o"></i>
                                إكسل
                            </button>
                            <button onclick="javascript:adoptAllModal();" type="button"
                                    class="btn btn-secondary me-2" id="btn_adopt">
                                <i class="fa fa-check"></i>
                                اعتماد المحدد
                            </button>
                        </div>
                    </form>
                    <hr>
                    <div id="container">
                        <?php echo modules::run($get_page_url, $page); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="public_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">اعتماد</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group col-md-4">
                        <label>التوصية</label>
                        <select name="status_modal" id="dp_status_modal" class="form-control">
                            <?php foreach ($status_cons as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="txt_notes">الملاحظة</label>
                        <textarea class="form-control" id="txt_notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="javascript:adoptAll();" type="button"
                            class="btn btn-secondary me-2" id="btn_insert_adopt">
                        <i class="fa fa-check"></i>
                        اعتماد المحدد
                    </button>
                    <button class="btn btn-default" data-bs-dismiss="modal">اغلاق</button>
                </div>
            </div>
        </div>
    </div>
<?php
$scripts = <<<SCRIPT

<script>

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
         status:$('#dp_status').val(),    
         degree_adopt:$('#dp_degree_adopt').val()   
      });
    }
    function LoadingData(){
        ajax_pager_data('#page_tb > tbody',{
           branch_no:$('#dp_branch_no').val(),
           yyears:$('#txt_yyears').val(),
           emp_no:$('#dp_emp_no').val(),
           head_department:$('#dp_head_department').val(),
           status:$('#dp_status').val(),
           degree_adopt:$('#dp_degree_adopt').val()     
        });
    }


   function search(){
        get_data('{$get_page_url}',{page: 1,branch_no:$('#dp_branch_no').val(),yyears : $('#txt_yyears').val(),emp_no:$('#dp_emp_no').val(),head_department:$('#dp_head_department').val(),status:$('#dp_status').val(),degree_adopt:$('#dp_degree_adopt').val()},function(data){
            $('#container').html(data);
            reBind();
        },'html');
    }
    
    function adoptAllModal(){
        var val = [];
        $('#page_tb .checkboxes:checked').each(function(i){
            val[i] = $(this).val();
        });
        if(val.length > 0){
                $('#public_modal').modal('show');
        }else{
             danger_msg('يرجى تحديد السجلات');
            return  -1;
        }
    }
    
     function adoptAll(){
       var action_desc= 'اعتماد';
       var val = [];
        $('#page_tb .checkboxes:checked').each(function(i){
            val[i] = $(this).val();
        });
        if(val.length > 0){
            if(confirm('هل تريد بالتأكيد '+action_desc+' '+val.length+' بنود')){
                get_data('{$adopts_all_url}', {ser:val,notes:$('#txt_notes').val(),status:$('#dp_status_modal').val()} , function(res){
                    if(parseInt(res) >= 1){
                        success_msg('رسالة','تمت عملية الاعتماد بنجاح ');
                        search();
                        $('#public_modal').modal('hide');
                    }else{
                        danger_msg('تحذير..',res);
                    }
              
            }, 'html');
                  return false;
          }
       }else{
            danger_msg('يرجى تحديد السجلات');
            return  -1;
       }
    }  
</script>
SCRIPT;
sec_scripts($scripts);
?>