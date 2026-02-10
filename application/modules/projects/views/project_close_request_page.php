<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

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

            <th style="width: 20px;">#</th>
            <th style="width: 80px;">التاريخ</th>
            <th style="width: 100px;">رقم المشروع</th>
            <th> البيان</th>
            <th style="width: 80px;"> نوع الاغلاق</th>
            <th style="width: 120px;">الحالة</th>
            <th style="width: 200px;">المدخل</th>
            <th style="width: 80px;"></th>

        </tr>
        </thead>
        <tbody>
        <?php if ($page > 1): ?>
            <tr>
                <td colspan="11" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>

            </tr>
        <?php endif; ?>
        <?php foreach ($rows as $row) : ?>
            <tr
                ondblclick="javascript:get_to_link('<?= base_url("projects/project_close_request/get/{$row['SER']}/{$action}") ?>');">

                <td><?= $count ?></td>
                <td><?= $row['REQUEST_DATE'] ?></td>
                <td><?= $row['TEC_CODE'] ?></td>
                <td><?= $row['TITLES'] ?></td>

                <td><?= $row['CLOSE_TYPE_NAME'] ?></td>
                <td> <span class="btn btn-xs <?= ($row['PROJECT_CLOSE_CASE'] == 1 or $row['PROJECT_CLOSE_CASE'] == -1) ? 'yellow' : ($row['PROJECT_CLOSE_CASE'] == 2 ? 'green' : ($row['PROJECT_CLOSE_CASE'] == 3) ? 'blue' : 'red') ?>"><?= $row['PROJECT_CLOSE_CASE_NAME'] ?></span></td>
                <td><?= $row['ENTRY_USER_NAME'] ?></td>
                <td>
                    <?php /*if ($row['ENTRY_USER'] == get_curr_user()->id && $row['PROJECT_CLOSE_CASE'] < 1 && HaveAccess(base_url('projects/projects/delete'))) : */?><!--
                        <a href="javascript:;" onclick="javascript:delete_project(this,<?/*= $row['SER'] */?>);"><i
                                class="icon icon-trash delete-action"></i> </a>
                    --><?php /*endif; */?>
                </td>
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