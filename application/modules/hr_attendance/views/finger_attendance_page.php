<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 16/03/2022
 * Time: 11:35 ص
 */

$MODULE_NAME= 'hr_attendance';
$TB_NAME= 'finger_attendance';
$delete_url =base_url("$MODULE_NAME/$TB_NAME/deleteFinger");
$count = $offset;
?>
<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم السند</th>
            <th>رقم الموظف</th>
            <th>اسم الموظف</th>
            <th>نوع البصمة</th>
            <th>تاريخ البصمة</th>
            <th>وقت البصمة</th>
            <th>تاريخ الادخال</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="9" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>
            <tr id="tr_<?=$row['NO']?>">
                <td><?=$count?></td>
                <td><?=$row['NO']?></td>
                <td><?=$row['EMPLOYEENO']?></td>
                <td><?=$row['EMP_NAME']?></td>
                <td><?=$row['STATUS_NAME']?></td>
                <td><?=$row['ATTENDANCE_DATE']?></td>
                <td><?=$row['ATTENDANCE_TIME']?></td>
                <td><?=$row['ENTRY_DATE_TIME']?></td>
                <td>
                    <?php if (HaveAccess($delete_url) && $row['ENTRY_USER'] == $this->user->id){?>
                        <a href="javascript:;" onclick="javascript:deleteRecord('<?=$row['NO']?>','<?=$row['EMPLOYEENO']?>');">
                            <i class="icon icon-trash delete-action"></i> </a>
                    <?php }?>
                </td>
                <?php $count++; ?>
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
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }
</script>