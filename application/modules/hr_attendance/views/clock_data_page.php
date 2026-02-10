<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 07/08/18
 * Time: 10:34 ص
 */

$count = $offset;
$trans_no_entry_url = base_url("hr_attendance/clock_data/trans_no_entry");
$edit_status_url = base_url("hr_attendance/clock_data/edit_status");
$trans_no_entry = HaveAccess($trans_no_entry_url);

$can_edit_status = $CAN_EDIT_STATUS;

// NEW after 26/5/2019
function time_color($delay_start,$early_end,$over_time){
    if($delay_start==1){
        return 'background-color: #FFEDE6; color: #BF0000; font-weight: bold';
    }elseif($early_end==1){
        return 'background-color: #FFFAE6; color: #BF0000; font-weight: bold';
    }elseif($over_time==1){
        return 'background-color: #E6FFFA; color: #38761D; font-weight: bold';
    }else{
        return '';
    }
}

/* OLD before 26/5/2019
//  time_color($row['FUNCTION_KEY'],$row['ENTRY_TIME'],$row['DAY_EN'])
function time_color($fun,$time,$day){
    if($fun==1 and $time > '08:15'){
        return 'background-color: #FFEDE6; color: #BF0000; font-weight: bold';
    }elseif($fun==4 and $time < '13:55' and $day!='THURSDAY'){
        return 'background-color: #FFFAE6; color: #BF0000; font-weight: bold';
    }elseif($fun==4 and $time < '12:55' and $day=='THURSDAY'){
        return 'background-color: #FFFAE6; color: #BF0000; font-weight: bold';
    }elseif($fun==4 and $time > '14:55' and $day!='THURSDAY'){
        return 'background-color: #E6FFFA; color: #38761D; font-weight: bold';
    }elseif($fun==4 and $time > '13:55' and $day=='THURSDAY'){
        return 'background-color: #E6FFFA; color: #38761D; font-weight: bold';
    }else{
        return '';
    }
}

*/

?>


<?php
    if ($show_delay_month){

        $date_arr= array_column($page_rows, 'ENTRY_DATE'); // all dates
        $month_arr= array_map(  function($val) { return substr($val,3); }, $date_arr  ); // 02/04/2021 = > 04/2021
        $month_arr= array_count_values( $month_arr ); // cnt days for all months
        $delay_month_arr= array_filter( $month_arr, function($val) { if($val > 3) return $val; }  ); // months days > 3

?>

        <table id="tb_delay_month" title="ايام التأخير فوق مدة السماح" class="table" style="width: 300px; margin-right: 100px">
            <thead style="cursor: n-resize">
            <tr>
                <th style="width: 150px">الشهر</th>
                <th style="width: 150px">ايام التأخير</th>
            </tr>

            </thead>
            <tbody>
            <?php
            foreach ($delay_month_arr as $key => $val) {
                echo "
                <tr>
                    <td>{$key}</td>
                    <td>{$val}</td>
                </tr>
                ";
            }

            ?>
            </tbody>
        </table>

<?php
    } // if show_delay_month
?>


<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم السند</th>
            <th>رقم الموظف</th>
            <th>اسم الموظف</th>
            <th>التاريخ</th>
            <th>اليوم </th>
            <th>الوقت</th>
            <th>الحركة</th>
            <?php
            if($can_edit_status){
                echo '<th>تعديل</th>';
            }
            ?>
            <th>المقر</th>
            <th>مكان البصمة</th>
            <th>ترحيل </th>
    </tr>
    </thead>
    <tbody>
    <?php if($page > 1): ?>
        <tr>
            <td colspan="12" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
        </tr>
    <?php endif; ?>
    <?php foreach($page_rows as $row) :?>

    <tr ondblclick="javascript:show_row_details('<?=$row['SER']?>');" >
        <td><?=$count?></td>
        <td><?=$row['SER']?></td>
        <td><?=$row['EMP_NO']?></td>
        <td><?=$row['EMP_NO_NAME']?></td>
        <td><?=$row['ENTRY_DATE']?></td>
        <td><?=$row['DAY_AR']?></td>
        <td style="<?=time_color($row['DELAY_START'],$row['EARLY_END'],$row['OVER_TIME'])?>"><?=$row['ENTRY_TIME']?></td>
        <td><?=$row['FUNCTION_KEY_NAME']?></td>
        <?php
        if($can_edit_status){
        ?>
        <td>
            <select id="dp_function_key_<?=$count?>" data-emp_no="<?=$row['EMP_NO']?>" data-ser="<?=$row['SER']?>" class="form-control function_key_edit" >
            <?php foreach($function_key_edit as $r) :?>
                <option <?=1?($row['FUNCTION_KEY']==$r['CON_NO']?'selected':''):''?> value="<?=$r['CON_NO']?>"><?=$r['CON_NAME']?></option>
            <?php endforeach; ?>
            </select>
        </td>
        <?php } ?>
        <td><?=$row['BRANCH_ID_NAME']?></td>
        <td><?=$row['BRANCH_CLOCK']?></td>
        <td id="td_ser_<?=$row['SER']?>">
        <?php if($trans_no_entry and $no_entry==1){
            if($row['ATTENDACE_NO_ENTRY_LEAVE_CHECK']==1){
                echo "<i class='glyphicon glyphicon glyphicon-ok' title='مرحل' style='color: #0a8800;font-size: large'></i>";
            }else {
                echo "<i class='glyphicon glyphicon-circle-arrow-left' style='color: #F47B0E; font-size: large' type='button' onclick=\"javascript:trans_no_entry( {$row['SER']}, {$row['EMP_NO']} , '{$row['ENTRY_DATE']}' );\" ></i> ";
            }
        }elseif (0){
            echo "";
        }else{
            echo "";
        } ?>
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

    $('#tb_delay_month thead').click(function(){
        $("#tb_delay_month tbody").toggle();
    });

    $('.function_key_edit').change(function(){

        var this_select= $(this);
        this_select.attr('disabled','disabled');
        var ser= this_select.attr('data-ser');
        var emp_no= this_select.attr('data-emp_no');
        var function_key= this_select.val();

        get_data('<?=$edit_status_url?>', {ser:ser, emp_no:emp_no, function_key:function_key} ,function(data){
            if(data==1){
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            }else{
                danger_msg('تحذير..',data);
            }
        },'html');

        setTimeout(function(){
            this_select.removeAttr('disabled');
        }, 3000);

    });

</script>
