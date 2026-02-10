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
            <th style="width: 100px" >الفرع</th>
            <th style="width: 70px" >رقم الطلب</th>
            <th style="width: 200px">نوع الطلب</th>
            <th style="width: 150px">المواطن</th>
            <th>العنوان</th>
            <th>البيان </th>
            <th>الإجراءات</th>
            <th style="width: 110px">التاريخ</th>
            <th style="width: 120px"> المدخل</th>
            <th style="width: 70px">الحالة</th>
            <th style="width: 50px">  </th>
            <th style="width: 80px"></th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="11" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>

            </tr>
        <?php endif; ?>
        <?php foreach($rows as $row) :?>
            <tr  ondblclick="javascript:get_to_link('<?=  base_url("technical/requests/get/{$row['REQUEST_ID']}/{$action}")  ?>');"   class="<?= $row['IMPORTANT_DEGREE'] == 3 ? 'case_0' : '' ?>" >

                <td><?= $count ?></td>
                <td><?= $row['BRANCH_ID_NAME'] ?></td>
                <td><?= $row['REQUEST_CODE'] ?></td>
                <td><?= $row['REQUEST_TYPE_NAME'] ?></td>
                <td><?= $row['CITIZEN_NAME'] ?></td>
                <td><?= $row['ADDRRSS'] ?></td>
                <td class="align-right orange"><?= $row['PURPOSE_DESCRIPTION'] ?></td>
                <td class="align-right"><?= $row['ACTION_HINTS'] ?></td>
                <td><?= $row['REQUEST_DATE_H'] ?></td>
                <td><?=  get_short_user_name($row['USER_ENTRY_NAME']) ?></td>
                <td> <h3 class="label <?= $row['REQUEST_CASE'] == 1?'label-warning':($row['REQUEST_CASE'] ==  2? 'label-primary' :'label-success') ?>"> <?= $row['REQUEST_CASE_NAME']  ?></h3></td>
                <td>
                    <?php if($row['REQUEST_CASE'] ==  3): ?>
                        <button type="button" data-hints="<?= $row['ACTION_HINTS'] ?>" class="btn btn-xs btn-info">
                            <i class="icon icon-bullhorn"></i>
                        </button>
                    <?php endif; ?>
                </td>
                <td>
                    <a class="btn-xs btn-default" href="javascript:;" onclick="javascript:_showReport('<?= base_url("JsperReport/showreport?sys=technical&report=tec_requests_report&id={$row['REQUEST_ID']}") ?>');"> طباعة
                    </a>

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