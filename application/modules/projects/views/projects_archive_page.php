<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * project: Ahmed Barakat
 * Date: 8/28/14
 * Time: 8:46 AM
 */

$count = $offset;
?>
<div class="tbl_container">
    <table class="table" id="projectTbl" data-container="container">
        <thead>
        <tr>

            <th  >#</th>
            <th>رقم المشروع</th>
            <th>الرقم الفني</th>
            <th>المستفيد</th>
            <th>اسم المشروع</th>
            <th>التاريخ</th>

            <th>نوع المشروع</th>
            <th>التصنيف الفني</th>
            <th>الفرع</th>

        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="11" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>

            </tr>
        <?php endif; ?>
        <?php foreach($rows as $row) :?>
            <tr ondblclick="javascript:get_to_link('<?= $action =='accounts'? base_url("projects/projects/edit_accounts/{$row['PROJECT_SERIAL']}"): base_url("projects/projects/get/{$row['PROJECT_SERIAL']}/{$action}")  ?>');">

                <td><?= $count ?></td>
                <td><?= $row['PROJECT_SERIAL'] ?></td>
                <td><?= $row['PROJECT_TEC_CODE'] ?></td>
                <td><?= $row['CUSTOMER_ID_NAME'] ?></td>
                <td><?= $row['PROJECT_NAME'] ?></td>
                <td><?= $row['PROJECT_DATE'] ?></td>

                <td><?= $row['PROJECT_TYPE_NAME'] ?></td>
                <td><?= $row['PROJECT_TEC_TYPE_NAME'] ?></td>
                <td><?= $row['BRANCH_NAME'] ?></td>

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