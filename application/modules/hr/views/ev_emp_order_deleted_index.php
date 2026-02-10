<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 01/02/22
 * Time: 10:20 ص
 */

$MODULE_NAME= 'hr';
$TB_NAME= 'Ev_emp_order_deleted';

$check_url =base_url("$MODULE_NAME/$TB_NAME/check_ev_order");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");

?>

    <div class="row">
        <div class="toolbar">
            <div class="caption"><?= $title ?></div>
            <ul>
            </ul>
        </div>

        <div class="form-body">
            <form class="form-vertical" id="<?=$TB_NAME?>_form" >

                <div class="modal-body inline_form">

                    <div class="form-group col-sm-2">
                        <label class="control-label">الموظف</label>
                        <div>
                            <select name="emp_no" id="dp_emp_no" class="form-control sel2" required >
                                <option value="">_________</option>
                                <?php foreach($emp_no_cons as $row) :?>
                                    <option value="<?=$row['EMP_NO']?>" ><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-3">
                        <label class="control-label">ملاحظات</label>
                        <div>
                            <input type="text" name="notes"  id="txt_notes"  class="form-control" minlength="10" maxlength="200" required/>
                        </div>
                    </div>

                    <div class="form-group col-sm-2" style="margin-top: 25px">
                        <button type="button" onclick='javascript:check_ev_order();' class="btn btn-success">فحص التقييم</button>
                        <?php if ( HaveAccess($delete_url) ) {  ?>
                            <button type="submit" data-action="submit" id="submit" class="btn btn-danger">حذف التقييم</button>
                        <?php } ?>
                    </div>

                </div>
            </form>
        </div>

    </div>


<?php
$scripts = <<<SCRIPT
<script>

    $('.sel2').select2();
    $('#submit').attr('disabled','disabled');
    
    $('#dp_emp_no').change(function(){
        $('#submit').attr('disabled','disabled');
    });
    
    function check_ev_order(){
        
        if ( $('#dp_emp_no').val()== '' ) {
            $('#submit').attr('disabled','disabled');
            danger_msg('رسالة','الرجاء  ادخال اسم الموظف ..');
            return;
        }

        var emp_no = $('#dp_emp_no').val();
        
        get_data('{$check_url}', {emp_no : emp_no} , function(ret){
            if( ret==1 || ret==2 ){
                $('#submit').removeAttr('disabled');
                info_msg('يمكن حذف التقييم #'+ret);
            }else if (ret==0){
                $('#submit').attr('disabled','disabled');
                danger_msg('تحذير..','لا يوجد تقييم للموظف ');
            }else {
                $('#submit').attr('disabled','disabled');
                danger_msg('تحذير..','خطأ في تحديد التقييمات ');
            }
        }, 'html');
    }

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var msg= 'سيتم حذف التقييم بشكل نهائي، هل تريد المتابعة؟';
    
    if(confirm(msg)){
        
        if ( $('#dp_emp_no').val()== '' ) {
            $('#submit').attr('disabled','disabled');
            danger_msg('رسالة','الرجاء  ادخال اسم الموظف ..');
            return;
        }
                
        if ( $('#txt_notes').val().length  == 0  ) {
            danger_msg('رسالة','الرجاء ادخال الملاحظات ..');
            return;
        }
                
        if ( $('#txt_notes').val().length  < 10 || $('#txt_notes').val().length  > 200 ) {
            warning_msg('رسالة','  يجب ان تكون الملاحظات  من 10 الى 200 حرف');
            return;
        }
        
        var msg_2= 'لا يمكن التراجع عن العملية، هل تريد التأكيد؟';
        if(confirm(msg_2)){
            
        emp_no = $('#dp_emp_no').val();
        var notes = $('#txt_notes').val();

        $(this).attr('disabled','disabled');
        get_data('{$delete_url}', {emp_no : emp_no , notes : notes} , function(ret){
            if(ret==1){
                success_msg('رسالة','تمت العملية بنجاح..');
            }else{
                $('#submit').removeAttr('disabled');
                danger_msg('تحذير..','فشل في العملية ');
            }
        }, 'html');
        }
    }
    
    });

    </script>
SCRIPT;
sec_scripts($scripts);
?>