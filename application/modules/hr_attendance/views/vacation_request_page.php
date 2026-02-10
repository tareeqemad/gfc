<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 19/03/18
 * Time: 11:58 ص
 */

$count = $offset;

global $page_act_2;
$page_act_2= $page_act;

function show_chk($adopt=0, $vac_type=0){
    global $page_act_2;
    $vac_arr = array(1,2,17); // عادية او طارئة   او غياب بدون اذن
    if($page_act_2=='manager' and $adopt==10 and in_array($vac_type, $vac_arr) ){
        return 1;
    }elseif($page_act_2=='move_admin' and $adopt<40 and ($adopt==1 or (in_array($vac_type, $vac_arr) and $adopt==30)) ){
        return 1;
    }else{
        return 0;
    }
}

/* OLD - 17/12/2018
function show_chk($adopt=0, $vac_type=0){
    global $page_act_2;
    if($page_act_2=='manager' and $adopt==10 and $vac_type!=3 ){
        return 1;
    }elseif($page_act_2=='move_admin' and $adopt<40 and ($adopt==30 or ($vac_type==3 and $adopt==1 ) ) ){
        return 1;
    }else{
        return 0;
    }
}
*/
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
            <th>تاريخ الاجازة</th>
            <th>تاريخ نهاية الاجازة</th>
            <th>نوع الاجازة</th>
            <th>مدة الاجازة</th>
            <th>الحالة</th>
            <th>المعتمد</th>
            <th>سنة الاجازة</th>
            <th>المقر</th>
            <th>تاريخ الادخال</th>
            <th>المدخل</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="16" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

            <tr ondblclick="javascript:show_row_details('<?=$row['SER']?>');" >
                <?php
                if($page_act=='manager' or $page_act=='move_admin'){
                    echo "<td>";
                    if(show_chk($row['ADOPT'],$row['VAC_TYPE'])){
                        echo '<input type="checkbox" class="checkboxes" value="'.$row['SER'].'" />';
                    }
                    echo "</td>";
                }
                ?>
                <td><?=$count?></td>
                <td><?=$row['SER']?></td>
                <td><?=$row['EMP_NO']?></td>
                <td><?=$row['EMP_NO_NAME']?></td>
                <td><?=$row['VAC_DATE']?></td>
                <td><?=$row['VAC_END_DATE']?></td>
                <td><?=$row['VAC_TYPE_NAME']?></td>
                <td><?=$row['VAC_DURATION']?></td>
                <td><?=$row['ADOPT_NAME']?></td>
                <td title="<?=$row['ADOPT_USER_20_NAME']?>"><?=get_short_user_name($row['ADOPT_USER_20_NAME'])?></td>
                <td><?=$row['VAC_YEAR']?></td>
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
