<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 04/01/16
 * Time: 10:02 ص
 */

$count = $offset;
?>
<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم المجموعة</th>
            <th>مسلسل المجموعة</th>
            <th>اسم المجموعة</th>
            <th>الفرع</th>
            <th>الخط المغذي</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="7" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>
            <tr ondblclick="javascript:group_select('<?= $row['GROUP_ID'] ?>','<?= $row['GROUP_NAME'] ?>');">
                <td><?=$count?></td>
                <td><?=$row['GROUP_ID']?></td>
                <td><?=$row['GROUP_SER']?></td>
                <td><?=$row['GROUP_NAME']?></td>
                <td><?=$row['BRANCH_NAME']?></td>
                <td><?=$row['LINE_FEEDER_NAME']?></td>
                <?php $count++ ?>
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
