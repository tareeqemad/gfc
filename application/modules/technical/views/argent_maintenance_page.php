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

            <th>وصف المشكلة</th>
            <th>اسم المواطن           </th>
            <th> الجوال  </th>
            <th> الهاتف</th>

            <th> العنوان  </th>
            <th>تاريخ ووقت تنفيذ المهمة</th>
            <th>نوع العطل</th>

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
            <tr  ondblclick="javascript:get_to_link('<?=  base_url("technical/Argent_Maintenance/get/{$row['ARGENT_MAINTENANCE_ID']}")  ?>');">

                <td><?= $count ?></td>

                <td><?= $row['PROBLEM_DESCRIPTION'] ?></td>
                <td><?= $row['CUSTOMER_NAME'] ?></td>
                <td><?= $row['MOBILE'] ?></td>
                <td><?= $row['TEL'] ?></td>
                <td><?= $row['ADDRESS'] ?></td>
                <td><?= $row['MISSION_PROCESS_DATE'] ?></td>
                <td><?= $row['PROBLEM_TYPE_NAME'] ?></td>
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