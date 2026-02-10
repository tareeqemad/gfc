<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 05/08/19
 * Time: 09:39 ص
 */

$MODULE_NAME= 'payment';
$TB_NAME= 'carRequest';
$count = $offset;
?>



<div class="tbl_container">
    <br>
    <table class="table" id="carRequest_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>م</th>
            <th>رقم السند</th>
            <th>رقم الموظف</th>
            <th>اسم الموظف</th>
            <th>اليوم</th>
            <th>الوقت المقترح</th>
            <th>من</th>
            <th>الى</th>
            <th>المقر</th>
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
                <td><input type='checkbox' class='checkboxes' value='<?=$row['SER']?>'></td>
                <td><?php echo $count; ?></td>
                <td><?=$row['SER']?></td>
                <td><?=$row['EMP_NO']?></td>
                <td><?=$row['EMP_NO_NAME']?></td>
                <td><?=$row['ASS_START_DATE']?></td>
                <td><?=$row['DAY_AR']?></td>
                <td><?=$row['FROM_ADDRESS']?></td>
                <td><?=$row['TO_ADDRESS']?></td>
                <td><?=$row['BRANCH_ID_NAME']?></td>

                <?php
                $count++;
                ?>


            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links();?>
<script>

</script>












