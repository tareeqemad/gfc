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

            <th style="width: 20px" >#</th>
            <th style="width: 70px" >رقم الطلب</th>
            <th style="width: 200px">نوع الطلب</th>
            <th style="width: 150px">المواطن</th>
            <th>البيان </th>
            <th>الإجراءات</th>
            <th style="width: 110px">التاريخ</th>
            <th style="width: 120px"> المدخل</th>

        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="11" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>

            </tr>
        <?php endif; ?>
        <?php foreach($rows as $row) :?>
            <tr  ondblclick="javascript:select_request(<?= $row['REQUEST_ID'] ?>,'<?= $row['REQUEST_CODE'] ?>');"    >

                <td><?= $count ?></td>
                <td><?= $row['REQUEST_CODE'] ?></td>
                <td><?= $row['REQUEST_TYPE_NAME'] ?></td>
                <td><?= $row['CITIZEN_NAME'] ?></td>
                <td class="align-right orange"><?= $row['PURPOSE_DESCRIPTION'] ?></td>
                <td class="align-right"><?= $row['ACTION_HINTS'] ?></td>
                <td><?= $row['REQUEST_DATE_H'] ?></td>
                <td><?=  get_short_user_name($row['USER_ENTRY_NAME']) ?></td>


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