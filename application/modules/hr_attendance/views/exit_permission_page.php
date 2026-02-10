<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 10/03/18
 * Time: 11:34 ص
 */

$count = $offset;

global $page_act_2;
$page_act_2= $page_act;

function show_chk($adopt=0){
    global $page_act_2;
    if($page_act_2=='manager' and $adopt==10 ){
        return 1;
    }elseif($page_act_2=='move_admin' and $adopt==30 ){
        return 1;
    }else{
        return 0;
    }
}

if($show_sum){
    $sum_mins= number_format(array_sum(array_column($page_rows, 'MINS')));
}

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <?=($page_act=='manager' or $page_act=='move_admin')?'<th><input type="checkbox" class="group-checkable" data-set="#page_tb .checkboxes"/></th>':''?>
            <th>#</th>
            <th>رقم السند</th>
            <th>رقم الموظف</th>
            <th>اسم الموظف</th>
            <th>تاريخ الاذن</th>
            <th>اليوم </th>
            <th>ساعة الخروج</th>
            <th>ساعة العودة</th>
            <th <?=($show_sum)?" title='المجموع: {$sum_mins}' ":"" ?> >مدة الاذن بالدقيقة</th>
            <th>الحالة</th>
            <th>المعتمد</th>
            <th>نوع الاذن</th>
            <th>سنة الاذن</th>
            <th>المقر</th>
            <th>تاريخ الادخال</th>
            <th>المدخل</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="18" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

            <tr ondblclick="javascript:show_row_details('<?=$row['SER']?>');" >
                <?php
                if($page_act=='manager' or $page_act=='move_admin'){
                    echo "<td>";
                    if(show_chk($row['ADOPT'])){
                        echo '<input type="checkbox" class="checkboxes" value="'.$row['SER'].'" />';
                    }
                    echo "</td>";
                }
                ?>
                <td><?=$count?></td>
                <td><?=$row['SER']?></td>
                <td><?=$row['EMP_NO']?></td>
                <td><?=$row['EMP_NO_NAME']?></td>
                <td><?=$row['P_DATE']?></td>
                <td><?=$row['DAY_AR']?></td>
                <td><?=$row['P_EXIT_TIME_']?></td>
                <td><?=$row['P_RET_TIME_']?></td>
                <td><?=$row['MINS']?></td>
                <td><?=$row['ADOPT_NAME']?></td>
                <td title="<?=$row['ADOPT_USER_20_NAME']?>"><?=get_short_user_name($row['ADOPT_USER_20_NAME'])?></td>
                <td><?=$row['PERMI_TYPE_NAME']?></td>
                <td><?=$row['PERM_YEAR']?></td>
                <td><?=$row['BRANCH_ID_NAME']?></td>
                <td title="<?=$row['ENTRY_DATE_TIME']?>"><?=$row['ENTRY_DATE']?></td>
                <td title="<?=$row['ENTRY_USER_NAME']?>"><?=get_short_user_name($row['ENTRY_USER_NAME'])?></td>
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
</script>
