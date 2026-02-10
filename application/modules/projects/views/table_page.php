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
            <th>رقم الحساب</th>
            <th>  الحساب</th>
            <th>الحساب المتبوع</th>
            <th style="min-width: 100px;" >نوع الحساب</th>
            <th style="min-width: 100px;" class="price">الرصيد</th>

            <th>  </th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="11" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>

            </tr>
        <?php endif; ?>
        <?php foreach($rows as $row) :?>
            <tr  ondblclick="javascript:select_account('<?= $row['PROJECT_ID'] ?>','<?= $row['PROJECT_ACCOUNT_NAME'] ?>','');">

                <td><?= $count ?></td>
                <td><?= $row['PROJECT_SERIAL'] ?></td>
                <td><?= $row['PROJECT_TEC_CODE'] ?></td>
                <td><?= $row['PROJECT_ID'] ?></td>
                <td class="align-right"><?= $row['PROJECT_ACCOUNT_NAME'] ?></td>
                <td class="align-right"><?= $row['PROJECT_ACCOUNT_ID_NAME'] ?></td>
                <td><?= $row['PROJECT_ACCOUNT_TYPE_NAME'] ?></td>
                <td class="price"><?= check_credit($row['TOTAL']) ?></td>
                <td>
                </td>
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