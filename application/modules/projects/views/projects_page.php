<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * project: Ahmed Barakat
 * Date: 8/28/14
 * Time: 8:46 AM
 */

$count = $offset;

/*echo $action =='accounts'? base_url("projects/projects/edit_accounts/{$row['PROJECT_SERIAL']}"):
    ($action == 'archive_last')? base_url("projects/projects/get_last/{$row['PROJECT_SERIAL']}/{$action}"):
        base_url("projects/projects/get/{$row['PROJECT_SERIAL']}/{$action}");*/

?>
<!--
<a href="javascript:;" onclick="javascript:$('#notes_pageModal').modal();" class="icon-btn">
    <i class="icon icon-comments"></i>
    <div>
عدد المشاريع
    </div>
												<span class="badge badge-danger">
												<?/*= $row_count */?> </span>
</a>
-->

<div class="tbl_container">
    <table class="table" id="projectTbl" data-container="container">
        <thead>
        <tr>

            <th  >#</th>
            <th>رقم المشروع</th>
            <th>الرقم الفني</th>
            <th>الرقم الفني القديم</th>
            <th>المستفيد</th>
            <th>اسم المشروع</th>
            <th>التاريخ</th>
            <th class="price">التكلفة</th>
            <th>نوع المشروع</th>
            <th>التصنيف الفني</th>
            <th>الفرع</th>
            <th>المرحلة</th>
            <th></th>

        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="11" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>

            </tr>
        <?php endif; ?>
        <?php foreach($rows as $row) :?>
            <tr class="<?= $action =='accounts'? 'ACCOUTS_'.$row['HAVE_ACCOUNT']: '' ?>" ondblclick="javascript:get_to_link('<?=  $action =='accounts'? base_url("projects/projects/edit_accounts/{$row['PROJECT_SERIAL']}"):(
            ($action == 'archive_last')? base_url("projects/projects/get_last/{$row['PROJECT_SERIAL']}/{$action}"):
                base_url("projects/projects/get/{$row['PROJECT_SERIAL']}/{$action}"))  ?>');">

                <td><?= $count ?></td>
                <td><?= $row['PROJECT_SERIAL'] ?></td>
                <td><?= $row['PROJECT_TEC_CODE'] ?></td>
                <td><?= $row['OLD_PROJECT_TEC_CODE'] ?></td>

                <td><?= $row['CUSTOMER_ID'] ?></td>
                <td><?= $row['PROJECT_NAME'] ?></td>
                <td><?= $row['PROJECT_DATE'] ?></td>
                <td class="price"><?= $row['TOTAL'] == 0? '' :n_format($row['TOTAL']) ?></td>
                <td><?= $row['PROJECT_TYPE_NAME'] ?></td>
                <td><?= $row['PROJECT_TEC_TYPE_NAME'] ?></td>
                <td><?= $row['BRANCH_NAME'] ?></td>
                <td class="project_<?= $row['PROJECT_CASE'] < 0? 1 : $row['PROJECT_CASE'] ?>"><?= project_case($row['PROJECT_CASE'],$row['PROJECT_TEC_CODE']) ?></td>
                <td>
                    <?php if ( $row['ENTRY_USER']== get_curr_user()->id && $row['PROJECT_CASE'] < 1 && HaveAccess(base_url('projects/projects/delete'))) : ?>
                        <a href="javascript:;" onclick="javascript:delete_project(this,<?= $row['PROJECT_SERIAL'] ?>);"><i class="icon icon-trash delete-action"></i> </a>
                    <?php endif; ?>

                    <?php if ( $row['PROJECT_CASE'] >= 11 && $row['PROJECT_TYPE'] != 3 && HaveAccess(base_url('projects/projects/customeritem'))) : ?>
                        <a href="<?= base_url('projects/projects/customeritem/'.$row['PROJECT_SERIAL'] ) ?>"
                           class="btn btn-xs btn-danger"
                        > مواد المواطنين</a>
                    <?php endif; ?>

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