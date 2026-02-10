<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 24/01/15
 * Time: 01:37 م
 */ $count = $offset;
?>
<div class="tbl_container">
    <table class="table" id="accountsTbl" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم المشروع</th>
            <th>الرقم القديم</th>
            <th>المشروع</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="11" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>

            </tr>
        <?php endif; ?>
        <?php foreach($rows as $row) :?>
            <tr ondblclick="javascript:account_select('<?= $row['PROJECT_NAME'] ?>','<?= $row['PROJECT_TEC_CODE'] ?>',<?= $row['PROJECT_SERIAL'] ?>);">

                <td><?= $count ?></td>

                <td><?= $row['PROJECT_TEC_CODE'] ?></td>
                <td><?= $row['OLD_ACCOUNT_ID'] ?></td>
                <td><?= $row['PROJECT_NAME'] ?></td>



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