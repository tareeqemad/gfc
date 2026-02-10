<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 12/12/19
 * Time: 09:37 ص
 */


$MODULE_NAME= 'collection_offices';
$TB_NAME= 'Branchs_adopt';
$count = 1;

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>

            <th>#</th>
            <th>رقم الكشف</th>
            <th>تاريخ فتح الكشف</th>
            <th>تاريخ اغلاق الكشف</th>
            <th>عدد الاشتراكات</th>
            <th>حالة الكشف</th>
            <th>حالة الاعتماد</th>
            <th></th>
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
        <?php
            $exp_date = str_replace('/', '-', $row['CLOSE_DATE']);
            $datetime2 = date_create($exp_date);
            $datetime1 = date_create(date('d-m-Y'));
            $interval = date_diff($datetime1, $datetime2);
            $check_date = $interval->format('%R%a');

            if($check_date >= 0){ ?>
            <tr  id="tr_<?=$row['SER']?>" >
        <?php }else { ?>
            <tr style="background-color: #f4ddb3" id="tr_<?=$row['SER']?>" >
        <?php   } ?>

                <td ><?=$count-1?></td>
                <td><?=$row['SER']?></td>
                <td><?=$row['OPEN_DATE']?></td>
                <td><?=$row['CLOSE_DATE']?></td>
                <td><?=$row['RECORDS_NUM']?></td>
                <?php if($check_date >= 0){ ?>
                <td><span class="badge badge-1">مفتوح</span></td>
                <?php }else{ ?>
                <td><span class="badge badge-3">مغلق</span></td>
                <?php } ?>

                <?php if($row['ADOPT'] == 2 ){ ?>
                    <td><span class="badge badge-2">معتمد</span></td>
                <?php }else{ ?>
                    <td><span class="badge badge-4">غير معتمد</span></td>
                <?php } ?>

                <?php

                if($check_date >= 0 && $row['ADOPT'] == 1 ){ ?>
                    <td>
                        <button type="button" onclick="javascript:details_('<?=$row['SER']?>');" class="btn btn-success btn-xs" href='javascript:;'><i class='glyphicon glyphicon-share'></i>الاشتراكات</button>
                        <button type="button" onclick="javascript:changeStatus_(0,'<?=$row['SER']?>');" class="btn btn-primary btn-xs" href='javascript:;'><i class='glyphicon glyphicon-check'></i>اعتماد الكشف</button></td>

                <?php }else{ ?>
				<td>
                </td>
				
				<?php } ?>



                <?php
                $count++; ?>

            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>


<?php echo $this->pagination->create_links(); ?>


<script>

</script>

