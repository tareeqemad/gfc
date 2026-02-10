<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 30/05/2020
 * Time: 13:24 pm
 */

$MODULE_NAME = 'emp_promotion';
$TB_NAME = 'promotion';
$count = $offset;
$delete_url = base_url("$MODULE_NAME/$TB_NAME/delete");
?>
<div class="table-responsive">
    <table class="table table-bordered" id="page_tb" data-container="container">
        <thead class="table-light">
        <tr>
            <th>م</th>
            <th>المقر</th>
            <th> الموظف</th>
            <th>الادارة</th>
            <th>الدرجة وقت الطلب</th>
            <th>التوصية</th>
            <th>درجة الترقية</th>
            <th>سنة الترقية</th>
            <th>حالة الاعتماد</th>
            <?php if (HaveAccess($delete_url)): ?>
                <th>الاجراءات</th>
            <?php endif; ?>
        </tr>
        </thead>
        <tbody>
        <?php if ($page > 1): ?>
            <tr>
                <td colspan="10" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php
        foreach ($page_rows as $row) :?>
            <tr id="tr_<?= $row['EMP_NO'] ?>"
                ondblclick="javascript:get_to_link('<?= base_url("$MODULE_NAME/$TB_NAME/get/{$row['SER']}") ?>');"
                title="<?= $row['EMP_NAME'] ?>">
                <td><?= $count ?></td>
                <td><?= $row['BRANCH_NAME'] ?></td>
                <td><?= $row['EMP_NO'].'-'.$row['EMP_NAME'] ?></td>
                <td><?= $row['HEAD_DEPARTMENT_NAME'] ?></td>
                <td><?= $row['DEGREE_NAME'] ?></td>
                <td>
                    <?php if ($row['STATUS'] == 0) { ?>
                        <span class="badge bg-warning">لا يوجد توصية </span>
                    <?php } elseif ($row['STATUS'] == 1) { ?>
                        <span class="badge bg-success"><?= $row['STATUS_NAME'] ?></span>
                    <?php } elseif ($row['STATUS'] == 2) { ?>
                        <span class="badge bg-danger"><?= $row['STATUS_NAME'] ?></span>
                    <?php } ?>
                </td>
                <td>
                    <?= $row['DEGREE_ADOPT_NAME'] ?>
                </td>
                <td><?= $row['YYEARS'] ?></td>
                <td>
                    <?= $row['ADOPT_NAME'] ?>
                </td>

                <td>
                    <?php if ($row['ADOPT'] == 1 && HaveAccess($delete_url)) { ?>
                        <a href="javascript:;" onclick="javascript:delete_prototype(this,<?= $row['SER'] ?>);"><i
                                    class="fa fa-trash"></i> </a>
                    <?php } ?>
                </td>

                <?php
                $count++; ?>
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