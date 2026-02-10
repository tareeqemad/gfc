<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 20/04/21
 * Time: 11:56 ص
 */

$MODULE_NAME= 'Covid';
$TB_NAME= 'Covid';
$count = $offset;
?>



<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم الهوية</th>
            <th>تاريخ الزيارة</th>
            <th>وقت الزيارة</th>
            <th>رقم المحمول</th>
            <th>الحالة</th>
            <th>المدخل</th>
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
                <td><?=$row['ID']?></td>
                <td><?=$row['VISIT_DATE']?></td>
                <td><?=$row['VISIT_TIME']?></td>
                <td><?=$row['MOBILE']?></td>
                <td><?php if($row['STATUS'] == 1 ){ ?>
                        <span class="badge badge-4"><?= $row['STATUS_NAME'] ?></span>
                    <?php }else{ ?>
                        <span class="badge badge-2"><?= $row['STATUS_NAME'] ?></span>
                    <?php }?>
                </td>
				 <td><?=$row['ENTRY_USER_NAME']?></td>

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
