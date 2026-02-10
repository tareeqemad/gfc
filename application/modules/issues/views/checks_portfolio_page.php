<?php

$MODULE_NAME= 'issues';
$TB_NAME= 'checks';
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$count = $offset;
?>



<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم الشيك</th>
            <th>رقم الشكوى</th>
            <th>حالة الاعتماد</th>
            <th>الفرع</th>
            <th>تحرير</th>
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
        <?php foreach($page_rows as $row) :?>
            <tr id="tr_<?=$row['SER']?>" >
                <td><?=$count?></td>
                <td><?=$row['CHECK_NO']?></td>
                <td><?=$row['COMPLAINT_NO']?> / <?=$row['COMPLAINT_YEAR']?></td>
                <td><?=$row['ADOPT_NAME']?></td>
                <td><?=$row['BRANCH_NAME']?></td>
                <!--<td><button type="button" onclick="javascript:status_('');" class=" btn-danger"> حذف</button></td>-->
                <?php
                $count++;
                ?>
                <td> <a target="_blank" href="<?=base_url("issues/checks/get_check_info/{$row['SER']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>

            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links();?>
<script>

</script>
