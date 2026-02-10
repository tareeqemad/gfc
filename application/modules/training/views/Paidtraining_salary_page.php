<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 18/07/20
 * Time: 01:33 م
 */

$MODULE_NAME= 'training';
$TB_NAME= 'Paidtraining';
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt_sal");
$count = 1;

//var_dump($rows); die;

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>
                <?php if( HaveAccess($adopt_url)):  ?>
                    <input type='checkbox' class='checkboxes'  id="select_all" >
                <?php endif; ?>
            </th>
            <th>#</th>
            <th>رقم الهوية</th>
            <th>الاسم</th>
            <th>مدة العقد الحالي بالأشهر</th>
            <th>تاريخ بداية العقد</th>
            <th>تاريخ انتهاء العقد</th>
            <th>مبلغ العقد/ شيكل</th>
            <th>المقر</th>
            <th>الادارة</th>
            <th>بداية التعاقد</th>
            <th>أيام الحضور</th>
            <th>عدد أيام الخصم</th>
            <th>قيمة الخصم</th>
            <th>قيمة الاضافة</th>
            <th>القيمة الكلية قبل الخصم والاضافة</th>
            <th>المبلغ المصروف عن مدة شهر كامل</th>
            <th>حالة الاعتماد</th>
            <th></th>
        </tr>



        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="12" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach ($rows as $row) : ?>
            <tr <?php if($row['ADOPT_FIN'] == 1){ ?> style="background-color: #daf0c7" <?php } elseif($row['ADOPT_FIN'] == 2){ ?>
                style="background-color: #f0dfb9"
            <?php } ?>
                      id="tr_<?=$row['SER']?>" >
                <?php if($row['ADOPT_FIN'] == 3){?>
                <td>
                    <?php if( HaveAccess($adopt_url)):  ?>
                    <input type='checkbox' class='checkbox' value='<?=$row['SER']?>/<?=$row['CON_SER']?>'>
                    <?php endif; ?>
                </td>
                <?php }else{ ?>
                    <td></td>
                <?php } ?>
                <td><?=$count?></td>
                <td><?=$row['ID']?></td>
                <td><?=$row['NAME']?></td>
                <td><?=$row['TRAINING_PER']?></td>
                <td><?=$row['START_DAT']?></td>
                <td><?=$row['END_DATEE']?></td>
                <td><?=$row['INCENTIVE_VAL']?></td>
                <td><?=$row['BRANCH_NAME']?></td>
                <td><?=$row['MANAGE_NAME']?></td>
                <td><?=$row['START_DATE']?></td>
                <td><?=$row['ATTENDANCE_DAYS']?></td>
                <td><?=$row['DISCOUNT_DAYS']?></td>
                <td  style="background-color: #ffbac4"><?=$row['DED_VALUE']?></td>
                <td  style="background-color: #9eff88"><?=$row['ADD_VALUE']?></td>
                <td><?=$row['SALARY']?></td>
                <td  style="background-color: #cfecff"><?=$row['NET_SALARY_']?></td>
                <td><span class="badge badge-<?=$row['ADOPT_FIN']+1?>"><?=$row['ADOPT_FIN_NAME']?></span></td>
                <td><?php if($row['ADOPT_FIN'] == 3 ){ ?>
                        <a name="manageAttendance_btn" id="addedDiscounts_btn" data-id="<?=$row['SER']?>"
                        data-con="<?=$row['CON_SER']?>"
                        style="background-color: #9d12ff; color:#FFFFFF "
                        onclick="addedDiscounts(this)" class="btn btn-xs">الاضافات / الخصم</a>
                    <?php } ?>
                </td>
                <?php
                $count++; ?>
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
</script>



