<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 26/07/20
 * Time: 10:23 ص
 */

$MODULE_NAME= 'training';
$TB_NAME= 'Paidtraining';
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt_sal_supervision");
$count = 1;
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
            <th >مدة التدريب الكلي حتى تاريخه - شهر</th>
            <th>أيام الحضور</th>
            <th>عدد أيام الخصم</th>
            <th>قيمة الخصم</th>
            <th>قيمة الاضافة</th>
            <th>القيمة الكلية قبل الخصم والاضافة</th>
            <th>المبلغ المصروف عن مدة شهر كامل</th>
            <th>اسم المعتمد / مالياً</th>
            <th>تاريخ الاعتماد</th>
        </tr>



        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="12" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach ($rows as $row) : ?>
            <tr <?php if($row['ADOPT'] == 2){?> style="background-color: #d0e9c6" <?php } ?> id="tr_<?=$row['SER']?>" >
                <?php if($row['ADOPT'] == 1){?>
                    <td>
                        <?php if( HaveAccess($adopt_url)):  ?>
                            <input type='checkbox' class='checkbox' value='<?=$row['SER']?>'>
                        <?php endif; ?>
                    </td>
                <?php }else{ ?>
                    <td></td>
                <?php } ?>
                <td><?=$count?></td>
                <td><?=$row['ID']?></td>
                <td><?=$row['NAME']?></td>
                <td><?=$row['CURR_PERIOD']?></td>
                <td><?=$row['CURR_START_DATE']?></td>
                <td><?=$row['CURRR_END_DATE']?></td>
                <td><?=$row['INCENTIVE_VAL']?></td>
                <td><?=$row['BRANCH_ID_NAME']?></td>
                <td><?=$row['MANAGE_NAME']?></td>
                <td><?=$row['START_DATE']?></td>
                <td><?=$row['ALL_PERIOD']?></td>
                <td><?=$row['ATTENDANCE_DAYS']?></td>
                <td ><?=$row['DISCOUNT_DAYS']?></td>
                <td  style="background-color: #ffbac4"><?=$row['DISCOUNT_VALUE']?></td>
                <td  style="background-color: #9eff88"><?=$row['ADD_VALUE']?></td>
                <td style="background-color: #ffc7a3"><?=$row['SALARY']?></td>
                <td  style="background-color: #cfecff"><?=$row['NET_SALARY']?></td>
                <td><?=$row['ENTRY_USER_NAME']?></td>
                <td><?=$row['ENTRY_DATE']?></td>

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



