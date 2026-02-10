<?php

$MODULE_NAME= 'hr';
$TB_NAME= 'Emps_punishment_ev';

$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$can_edit= (HaveAccess($edit_url))?1:0;
$count=1;
?>

<div class="tbl_container">
    <table class="table" id="emps_punishment_ev_tb" data-container="container">

        <thead>
        <tr>
            <th>#</th>
            <th>رقم الطلب</th>
            <th>الموظف</th>
            <th>نوع العقوبة</th>
            <th>السنة</th>
            <th>قيمة الخصم في التقييم</th>
            <th>ملاحظات</th>
            <th>الحالة</th>
            <th>تاريخ الادخال</th>
            <th> المدخل</th>
            <th></th>
        </tr>
        </thead>

        <tbody>
        <?php foreach($page_rows as $row) :?>
            <tr data-ser="<?=$row['SER']?>" data-adopt="<?=$row['ADOPT']?>" >
                <td style="width:2%"><?=$count?></td>
                <td style="width:5%"><?=$row['SER']?></td>
                <td style="width:15%"><?=$row['EMP_NO_NAME']?></td>
                <td style="width:10%"><?=$row['PUNISHMENT_TYPE_NAME']?></td>
                <td style="width:5%"><?=$row['PUNISHMENT_YEAR']?></td>
                <td style="width:10%"><?=$row['EVALUATION_DISCOUNT']?></td>
                <td style="width:20%"><?=$row['NOTES']?></td>
                <td style="width:5%"><?=$row['ADOPT_NAME']?></td>
                <td style="width:7%"><?=$row['ENTRY_DATE']?></td>
                <td style="width:15%"><?=$row['ENTRY_USER_NAME']?></td>

                <td style="width:12%">
                    <button type="button" class="btn btn-primary btn-xs btn_show">عرض</button>
                    <?php if ( $row['ADOPT'] == 1 and HaveAccess($adopt_url.'10') ) :  ?>
                        <button type="button"  class="btn btn-success btn-xs  btn_adopt" id="btn_adopt_<?=$row['SER']?>">اعتماد</button>
                    <?php endif; ?>
                </td>

                <?php
                $count++;
                ?>
            </tr>
        <?php endforeach;?>
        </tbody>

    </table>
</div>

<script type="text/javascript">

    var ser;
    $(".btn_adopt").click(function(){
        var tr = $(this).closest('tr');
        ser = tr.attr("data-ser");
        $('#h_ser').val(ser);
        adopt_(ser, 10 );
    });

    $(".btn_show").click(function(){
        var tr = $(this).closest('tr');
        ser = tr.attr("data-ser");
        $('#h_ser').val(ser);
        var adopt = tr.attr("data-adopt");
        get(ser , adopt);
    });


    function adopt_(ser, no){

        if(no==10 ) var msg= 'هل تريد اعتماد الطلب ؟!';

        if(confirm(msg)){
            var values= {ser: ser };
            get_data('<?=$adopt_url?>'+no, values, function(ret){
                if(ret==1){
                    $('#btn_adopt_'+ser).hide();
                    success_msg('رسالة','تمت العملية بنجاح..');

                    setTimeout(function(){
                    },1000);
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }
    }

    function get(ser , adopt){
        var values= {ser: ser };

        get_data('<?=$get_url?>',values,function(data){
            $.each(data, function(i,item) {

                $('#myModal #h_ser').val(item.SER);
                $('#myModal #dp_emp').select2('val', item.EMP_NO);
                $('#myModal #txt_punishment_year').val(item.PUNISHMENT_YEAR);
                $('#myModal #txt_evaluation_discount').val(item.EVALUATION_DISCOUNT);
                $('#myModal #txt_notes').val(item.NOTES);
                $('#myModal #dp_punishment_type_no').select2('val', item.PUNISHMENT_TYPE);
                $('#myModal #h_adopt').val(item.ADOPT);
                $('#myModal').modal();
                $('#Emps_punishment_ev_form_modal').attr('action', '<?=$edit_url?>');

                if (adopt > 1 || '<?=$can_edit?>'=='0' ) {
                    $('#submit').hide();
                }else {
                    $('#submit').show();
                }
            });
        });

    }

</script>

