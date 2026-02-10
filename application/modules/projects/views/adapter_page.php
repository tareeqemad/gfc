<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/28/14
 * Time: 8:46 AM
 */

function adapter_case_color($case)
{
    if ($case == 'ON')
        return '#009900';
    elseif ($case == 'OFF')
        return '#E50000';
    else
        return '';
}

$count = $offset;
?>
<div class="tbl_container">
    <table class="table" id="adapterTbl" data-container="container">
        <thead>
        <tr>
            <th><input type="checkbox" class="group-checkable" data-set="#adapterTbl .checkboxes"/></th>
            <th>#</th>
            <th>اسم المحول</th>
            <th>قدرة المحول KVA</th>
            <th> نوع المحول</th>

            <th style="width: 250px">المقر</th>
            <th style="width: 120px">حالة المحول</th>
            <th style="width: 120px">نسبة التحميل</th>
            <th style="width: 120px">نسبة متوسط التحميل </th>

        </tr>
        </thead>
        <tbody>
        <?php if ($page > 1): ?>
            <tr>
                <td colspan="11" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>

            </tr>
        <?php endif; ?>
        <?php foreach ($adapters as $row) : ?>
            <tr <?= (!$show_only) ? "ondblclick=\"javascript:get_to_link('" . base_url("projects/adapter/get_id/{$row['ADAPTER_SERIAL']}") . "');\" " : "" ?>  >
                <td><input type="checkbox" class="checkboxes" value="<?= $row['ADAPTER_SERIAL'] ?>"/></td>
                <td><?= $count ?></td>
                <td><?= $row['ADAPTER_NAME'] ?></td>
                <td><?= $row['POWER_ADAPTER'] ?></td>
                <td><?= $row['POWER_ADAPTER_SC'] ?></td>

                <td><?= $row['BRANCH_NAME'] ?></td>
                <td style=" font-weight: bold; color: <?= adapter_case_color($row['ADAPTER_CASE_BY_TIME']) ?>"><?= $row['ADAPTER_CASE_BY_TIME'] ?></td>
                <td><?= $row['ADAPTER_LOAD_PERCENT'] ?></td>
                <td><?= $row['ADAPTER_LOAD_PERCENT_AVG'] ?></td>
                <?php $count++ ?>
            </tr>
        <?php endforeach; ?>
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