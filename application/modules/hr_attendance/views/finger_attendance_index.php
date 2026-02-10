<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 16/03/2022
 * Time: 10:40 ص
 */
$MODULE_NAME= 'hr_attendance';
$TB_NAME= 'finger_attendance';
if ($action == "company"){
    $back_url=base_url("$MODULE_NAME/$TB_NAME/index_company_employees");
}else if ($action == "technician"){
    $back_url=base_url("$MODULE_NAME/$TB_NAME/index_project_technician");
}
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$post_url = base_url("$MODULE_NAME/$TB_NAME/insertFinger");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/deleteFinger");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$date_attr= "data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";
echo AntiForgeryToken();
?>
<div class="row">
    <div class="toolbar">
        <div class="caption"><?=$title?></div>
        <ul>
            <?php if( HaveAccess($post_url)):  ?>
             <li><a href="<?=$create_url?>/<?=$action?>"><i class="glyphicon glyphicon-plus"></i>جديد </a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>
<div class="row">
    <div class="form-body">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">

                <div class="form-group col-sm-2">
                    <label class="control-label">الموظف</label>
                    <div>
                        <select name="emp_no" id="dp_emp_no" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($emp_no_cons as $row) :
                                if ( $action == 'company' and $row['EMP_NO'] < 5000){ ?>
                                    <option value="<?=$row['EMP_NO']?>"><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                                <?php }elseif ($action == 'technician' and $row['EMP_NO'] >= 70000){ ?>
                                    <option value="<?=$row['EMP_NO']?>"><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                                <?php } endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">نوع البصمة</label>
                    <div>
                        <select name="status" id="dp_status" class="form-control sel2" >
                            <option value="">_________</option>
                            <option value="1">حضور</option>
                            <option value="4">انصراف</option>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ البصمة </label>
                    <div>
                        <input type="text" <?=$date_attr?> name="tdate" id="txt_tdate" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">وقت البصمة</label>
                    <div>
                        <input type="text" placeholder="15:00" value="" name="ttime" id="txt_ttime" class="form-control" />
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="javascript:searchs();" class="btn btn-success"> إستعلام</button>
                <button type="button" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success">  اكسل </button>
                <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
            </div>
            <div id="msg_container"></div>
            <div id="container">

            </div>
        </form>
    </div>
</div> <!--end row -->


<?php
$scripts = <<<SCRIPT
<script>
    
    $('.sel2:not("[id^=\'s2\']")').select2();
    
    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val','');
    }
    
    $(function(){
        reBind(); 
        $('.sel2:not("[id^=\'s2\']")').select2();
    });

    function reBind(){
        ajax_pager({
            emp_no:$('#dp_emp_no').val(),status:$('#dp_status').val(),tdate:$('#txt_tdate').val(),ttime : $('#txt_ttime').val()
        });
    }

    function LoadingData(){
        ajax_pager_data('#page_tb > tbody',{
          emp_no:$('#dp_emp_no').val(),status:$('#dp_status').val(),tdate:$('#txt_tdate').val(),ttime : $('#txt_ttime').val()
        });
    }


    function searchs(){
        get_data('{$get_page_url}',
            {page: 1, emp_no:$('#dp_emp_no').val(),status:$('#dp_status').val(),tdate:$('#txt_tdate').val(),ttime : $('#txt_ttime').val()},
            function(data){
                $('#container').html(data);
                reBind();
            },'html');
    }
    
    function deleteRecord(ser,employeeNo){
      if(confirm( 'هل انت متأكد من عملية حذف السجل ؟')){
        get_data('{$delete_url}',{ser:ser,employeeNo:employeeNo},function(data){
             if(data==1 ){
                 success_msg('تم الحذف بنجاح..!');
                 $('#tr_'+ser).hide(500);
             }else{
                 danger_msg('لم يتم الحذف..!!');
                }
           });
      }
    }
    
</script>
SCRIPT;
sec_scripts($scripts);
?>
