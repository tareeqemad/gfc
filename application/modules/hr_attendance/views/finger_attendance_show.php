<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 16/03/2022
 * Time: 10:54 ص
 */
$MODULE_NAME= 'hr_attendance';
$TB_NAME= 'finger_attendance';
if ($action == "company"){
    $back_url=base_url("$MODULE_NAME/$TB_NAME/index_company_employees");
}else if ($action == "technician"){
    $back_url=base_url("$MODULE_NAME/$TB_NAME/index_project_technician");
}
$post_url =base_url("$MODULE_NAME/$TB_NAME/insertFinger");
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$date_attr= "data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";
//$ser=isset($rs['SER'])? $rs['SER'] :'';
?>
<div class="row">
    <div class="toolbar">
        <div class="caption"><?=$title?></div>
        <ul>
            <?php if( HaveAccess($back_url)):  ?>
                <li><a href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a></li>
            <?php  endif; ?>
        </ul>
    </div>
</div>
<div class="row">
    <div class="form-body">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
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
                        <input type="text" placeholder="15:00" value="" name="ttime" id="txt_ttime" autocomplete="off" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-5">
                    <label class="control-label">ملاحظة</label>
                    <div>
                        <input type="text" placeholder="ملاحظة" value="" name="reason" id="txt_reason" autocomplete="off" class="form-control" />
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <?php if ( HaveAccess($post_url)) : ?>
                <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php  endif;  ?>
            </div>
        </form>
    </div>
</div> <!--end row -->
<?php
$scripts = <<<SCRIPT
<script>

      $('.sel2:not("[id^=\'s2\']")').select2();
    
    $('#txt_ttime').change(function(){
        if($(this).val() < '08:00'){
            alert('الساعة يتم ادخالها بنظام 24، الساعة المدخلة حاليا قبل بداية الدوام');
        }
    });
      
   $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var msg= 'هل تريد حفظ الطلب ؟!';
        if(confirm(msg)){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>=1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link('{$back_url}');
                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
    });
</script>
SCRIPT;
sec_scripts($scripts);
?>
