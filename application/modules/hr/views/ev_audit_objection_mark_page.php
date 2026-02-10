<?php

$MODEL_NAME= 'hr';
$TB_NAME ='ev_audit_objection_mark';
$count=1;
$create_url = base_url("$MODEL_NAME/$TB_NAME/create");
$edit_url = base_url("$MODEL_NAME/$TB_NAME/edit");

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>

            <th>#</th>
            <th>رقم امر التقييم</th>
            <th>رقم الموظف</th>
            <th>اسم الموظف</th>
            <th>الدرجة</th>
            <th>الملاحظات</th>
            <th>حفظ</th>

        </tr>
        </thead>
        <tbody>

        <?php foreach($page_rows as $row) :?>

            <tr data-ser="<?=$row['AUDIT_OBJECTION_SER']?>" data-ev_order_serial="<?=$row['EVALUATION_ORDER_SERIAL']?>" data-emp_no="<?=$row['EMP_NO']?>"  >
                <td><?=$count?></td>
                <td><?=$row['EVALUATION_ORDER_ID']?></td>
                <td><?=$row['EMP_NO']?></td>
                <td><?=$row['EMP_NO_NAME']?></td>
                <td class="col-sm-1">
                    <input type="text" value="<?=$row['AUDIT_OBJECTION_MARK']?>" class="form-control txt_emp_mark" style="text-align: center " onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" />
                </td>

                <td><input type="text" value="<?=$row['AUDIT_OBJECTION_NOTES']?>"  class="form-control txt_notes" style="text-align: center " ></td>

                <td>
                    <?php if (HaveAccess($create_url) or HaveAccess($edit_url)) { ?>
                        <button type="button"  class="btn btn-primary btn_save" >حفظ</button>
                    <?php } ?>
                </td>

                <?php
                $count++;
                ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>


<script type="text/javascript">
    
    $(document).ready(function() {
        $(".btn_save").click(function(){

            var tr= $(this).closest("tr");

            var evaluation_order_id = $('#h_evaluation_order_id').val();
            var order_level = $('#h_order_level').val();

            var ser = tr.attr("data-ser");
            var emp_no = tr.attr("data-emp_no");
            var ev_order_serial = tr.attr("data-ev_order_serial");

            var emp_mark = $('.txt_emp_mark', tr).val();
            var notes = tr.find('.txt_notes').val();

            var msg= 'هل تريد حفظ البيانات ؟!';

            if(confirm(msg)){

                if ( parseInt(ser) > 0){

                    get_data('<?=$edit_url?>', {ser:ser, emp_no:emp_no , emp_mark:emp_mark , notes:notes} ,function(data){
                        var len = data.length;
                        if(len > 0){
                            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                        }else{
                            danger_msg('الرجاء ادخال البيانات..',data);
                        }
                    },'html');

                } else {

                    get_data('<?=$create_url?>', {ser:ser, evaluation_order_id:evaluation_order_id , order_level:order_level , emp_no:emp_no , emp_mark:emp_mark , notes:notes ,ev_order_serial:ev_order_serial } ,function(data){
                        if(data > 0){
                            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                            tr.attr("data-ser",data);

                        }else{
                            danger_msg('الرجاء ادخال البيانات..',data);
                        }
                    },'html');

                }
            }

        });
    });

</script>
