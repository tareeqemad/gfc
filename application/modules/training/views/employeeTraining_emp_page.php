<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 13/07/20
 * Time: 11:44 ص
 */



$MODULE_NAME= 'training';
$TB_NAME= 'employeeTraining';
$count = 1;
?>



<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم الدورة</th>
            <th>اسم الدورة/ عربية</th>
            <th>اسم الدورة/ الانجليزية</th>
            <th>تاريخ بداية الدورة</th>
            <th>عدد ساعات الدورة</th>
            <?php
            $count++;
            ?>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr >
                <td colspan="12" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach ($rows as $row) : ?>
            <tr id="tr_<?=$row['SER']?>" >
                <td><?=$count?></td>
                <td><?=$row['COURSE_NO']?></td>
                <td><?=$row['COURSE_NAME']?></td>
                <td><?=$row['COURSE_NAME_ENG']?></td>
                <td><?=$row['COURSE_DATE']?></td>
                <td><?=$row['COURSE_HOUR']?></td>

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



