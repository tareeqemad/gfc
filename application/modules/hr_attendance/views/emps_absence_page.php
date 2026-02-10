<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 08/10/18
 * Time: 10:22 ص
 */

$count = $offset;

$save_reason_url = base_url("hr_attendance/emps_absence/save_reason");
$can_save_reason= $CAN_SAVE_REASON;

function chk_vacation($v,$vt,$e,$f,$o){
    if($v==1){
        return 'اجازة - '.$vt;
    }elseif($o==1){
        return 'اجازة رسمية';
    }elseif($e==1){
        return 'اذن';
    }elseif($f>=1){
        return 'له بصمة';
    }elseif($v==0 and $e==0 and $f==0 and $o==0){
        return '<div style="background-color: #ffb0b3; color: #ffb0b3; margin: -2px;">_</div>';
    }else{
        return $v.'-'.$e.'-'.$f.'-'.$o;
    }
}

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم الموظف</th>
            <th>اسم الموظف</th>
            <th>التاريخ</th>
            <th>اليوم</th>
            <th>المقر</th>
            <th>بيان</th>
            <th>السبب</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="9" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

            <tr ondblclick="javascript:show_row_details('');" >
                <td><?=$count?></td>
                <td><?=$row['EMP_NO']?></td>
                <td><?=$row['EMP_NO_NAME']?></td>
                <td><?=$row['DT']?></td>
                <td><?=$row['DAY_AR']?></td>
                <td><?=$row['BRANCH_ID_NAME']?></td>
                <td><?=chk_vacation($row['IS_VACATION'],$row['VAC_TYPE_NAME'],$row['IS_EXIT'],$row['HAVE_FINGERPRINT'],$row['IS_OFFICIAL_VACATION'])?></td>
                <td>
                    <select <?=($can_save_reason)?'':'disabled'?> id="dp_reason_no_<?=$count?>" data-Rser="<?=$row['REASON_SER']?>" data-emp_no="<?=$row['EMP_NO']?>" data-dt="<?=$row['DT']?>" class="form-control reason_no_edit" >
                        <option value=""></option>
                        <?php foreach($reason_con as $r) :?>
                            <option <?=1?($row['REASON_NO']==$r['CON_NO']?'selected':''):''?> value="<?=$r['CON_NO']?>"><?=$r['CON_NAME']?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <?php
                $count++;
                ?>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links(); ?>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }
    if (typeof show_page == 'undefined'){
        document.getElementById("page_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }

    setTimeout(function(){
    $('.reason_no_edit').change(function(){
        if(!<?=$can_save_reason?>){
            return 0;
        }

        var this_select= $(this);
        this_select.attr('disabled','disabled');
        var Rser= this_select.attr('data-Rser');
        var emp_no= this_select.attr('data-emp_no');
        var dt= this_select.attr('data-dt');
        var reason_no= this_select.val();

        get_data('<?=$save_reason_url?>', {reason_ser:Rser, emp_no:emp_no, dt1:dt, reason_no:reason_no} ,function(data){
            if(data==1){
                success_msg('رسالة','تم تعديل البيانات بنجاح ..');
            }else if(data>1){
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                this_select.attr('data-Rser',data);
            }else{
                danger_msg('تحذير..',data);
            }
        },'html');

        setTimeout(function(){
            this_select.removeAttr('disabled');
        }, 3000);

    });
    }, 2000);

</script>
