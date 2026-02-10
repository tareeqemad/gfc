<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 26/07/20
 * Time: 11:57 ص
 */


$MODULE_NAME= 'training';
$TB_NAME= 'Paidtraining';
$count = 1;
$sum_salary = 0;
?>


<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr >
            <th style="background-color: #2e72af" colspan="6">حوالات مستحقات متدربي مدفوع الأجر في أقسام الشركة عن شهر <?=$month?> الفترة من  26/<?=$month-1 ?> حتى   25/<?= $month?> من البنك</th>

        </tr>
        <tr>
            <th>#</th>
            <th>رقم الهوية</th>
            <th>الاسم</th>
            <th>المقر</th>
            <th>فرع البنك</th>
            <th>المبلغ المصروف عن مدة شهر كامل</th>
        </tr>

        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="12" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach ($rows as $row) : ?>
            <tr id="tr_<?=$row['SER']?>" >
                <td><?=$count?></td>
                <td><?=$row['ID']?></td>
                <td><?=$row['NAME']?></td>
                <td><?=$row['BRANCH_ID_NAME']?></td>
                <td><?=$row['BANK_NAME']?></td>
                <td><?=$row['NET_SALARY']?></td>
                <?php
                $sum_salary += $row['NET_SALARY'];
                $count++; ?>
            </tr>
        <?php endforeach;?>
        <tr >
            <th style="background-color: #91b3d1" colspan="5">اجمالي اجور متدربي الشركة </th>
            <th style="background-color: #91b3d1" ><span class="badge badge-3"> <?=$sum_salary?> </span></th>

        </tr>
        </tbody>
    </table>
</div>

<?php echo $this->pagination->create_links(); ?>
<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>



