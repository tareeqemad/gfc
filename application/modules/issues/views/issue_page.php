<?php

$MODULE_NAME= 'issues';
$TB_NAME= 'issues';
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$count = $offset;
?>



<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم الاشتراك</th>
            <th>اسم المشترك</th>
            <th>رقم هوية المدعى عليه</th>
            <th>رقم القضية</th>
            <th>حالة القضية</th>
            <th>نوع القضية</th>
            <th>حالة الاعتماد</th>
            <th>الفرع</th>
            <th>تحرير</th>
            <!--<th>حذف</th>-->
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
                <td><?=$row['SUB_NO']?></td>
                <td><?=$row['SUB_NAME']?></td>
                <td><?=$row['D_ID']?></td>
                <td><?=$row['ISSUE_NO']?> / <?=$row['ISSUE_YEAR']?></td>
                <td><?=$row['STATUS_NAME']?></td>
               <td><?=$row['ISSUE_TYPE_NAME']?></td>
                <td><?=$row['ADOPT_NAME']?></td>
                <td><?=$row['ISSUE_BRANCH_NAME']?></td>
                <!--<td><button type="button" onclick="javascript:status_('');" class=" btn-danger"> حذف</button></td>-->
                <?php
                $count++;
                if($row['ISSUE_TYPE_NAME'] == 'قضية تنفذية تابعة لقضية حقوقية'){
                ?>
                <td> <a href="<?=base_url("issues/issues/get_exec_issue_info/{$row['SER']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>
                <?php } else{ ?>
                <td> <a href="<?=base_url("issues/issues/get_issue_info/{$row['SER']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>
                <?php } ?>

            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links();?>
<script>

</script>
