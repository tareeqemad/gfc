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
            <th>رقم هوية المشترك</th>
            <th>اسم المدعي عليه</th>
            <th>عليه</th>
            <th>حالة الانتهاء</th>
            <th>حالة الاعتماد</th>
            <th>عرض</th>
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
        <?php foreach($page_rows as $row) :
if ($row['LAW_TYPE'] == 8)
{
$new_status=$row['STATUS_NAME'];
}
else
{
               if($row['ADOPT'] == 1){
                   $new_status='غير منتهي';

               }
            else{
                $new_status='منتهية';

            }
}
            ?>

            <tr id="tr_<?=$row['SER']?>" >
                <td><?=$count?></td>
                <td><?=$row['SUB_NO']?></td>
                <td><?=$row['SUB_NAME']?></td>
                <td><?=$row['ID']?></td>
                <td><?=$row['D_NAME']?></td>
                <td><?=$row['LAW_TYPE_NAME']?></td>
                <td><?=$new_status;?></td>
                <td><?=$row['ADOPT_NAME']?></td>
                <!--<td><button type="button" onclick="javascript:status_('');" class=" btn-danger"> حذف</button></td>-->
                <?php
                $count++;
                ?>
                <td><a href="<?=base_url($row['URL'])?>" target="_blank"><i class='glyphicon glyphicon-share'></i></a></td>

            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<?php echo ''/*$this->pagination->create_links()*/;?>
<script>

</script>
