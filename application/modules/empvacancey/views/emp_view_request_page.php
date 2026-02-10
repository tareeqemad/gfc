<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 30/01/2019
 * Time: 11:20 ص
 */
$count = $offset;
$MODULE_NAME = 'empvacancey';
$TB_NAME = "vacancey";
$get_detail_url = base_url("$MODULE_NAME/$TB_NAME/status_create");
$delete_request_url = base_url("$MODULE_NAME/$TB_NAME/delete_request");
?>
<div class="table-responsive">
    <table class="table table-bordered" id="page_tb">
        <thead class="table-light">
        <tr>
            <th>م</th>
            <th>رقم الطلب</th>
            <th>رقم الموظف</th>
            <th>الفرع</th>
            <th>اسم الموظف</th>
            <th>رقم الهوية</th>
            <th>المسمى الوظيفي</th>
            <th>ملاحظات</th>
            <th>الاعتماد</th>
            <th>اسم المدخل</th>
            <th>تاريخ الادخال</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($page > 1): ?>
            <tr>
                <td colspan="12" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach ($page_rows as $row) : ?>
            <tr ondblclick="javascript:get_to_link('<?= $get_detail_url . '/' . $row['ID_VACANCY'] ?>')">
                <td><?= $count ?></td>
                <td><?= $row['ID_VACANCY'] ?></td>
                <td><?= $row['EMP_NO'] ?></td>
                <td><?= $row['BRANCH_NAME'] ?></td>
                <td><?= $row['EMP_NAME'] ?></td>
                <td><?= $row['EMP_ID'] ?></td>
                <td><?= $row['EMP_JOB'] ?></td>
                <td><?= $row['V_NOTE'] ?></td>
                <td><?= $row['ADOPT_NAME'] ?></td>
                <td><?= $row['ENTRY_USER_NAME'] ?></td>
                <td><?= $row['ENTRY_DATE'] ?></td>
                <td>

                    <a href="<?= $get_detail_url . '/' . $row['ID_VACANCY'] ?>">
                        <span class="glyphicon glyphicon-search"></span>
                    </a> |

                    <?php if ($row['ADOPT'] >= 10 ) {?>
                        <a href="javascript:;" class="prints"  onclick="javascript:print_report(<?= $row['ID_VACANCY'] ?>);">
                            <span class="glyphicon glyphicon-print" style="color: #0a8800"></span>
                        </a> |
                    <?php } ?>
                    <?php if ( HaveAccess($delete_request_url) && $row['ADOPT'] == 1 ) : ?>
                        <a href="javascript:;" title="حذف" onclick="javascript:delete_vacancy(this,<?= $row['ID_VACANCY'] ?>);"><i class="glyphicon glyphicon-trash"  style="color: #a43540"></i> </a>
                    <?php endif; ?>

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

