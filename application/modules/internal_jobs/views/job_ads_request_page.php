<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 19/10/2022
 * Time: 12:30 PM
 */
$count = $offset;
$MODULE_NAME = 'internal_jobs';
$TB_NAME = 'job_ads_request';
$edit_status_url = base_url("$MODULE_NAME/$TB_NAME/edit_status");//تعديل الحالة
$show_achieve_url = base_url("$MODULE_NAME/$TB_NAME/show_achieve");//صلاحية عرض الانجازات للجنة
$show_plan_url = base_url("$MODULE_NAME/$TB_NAME/show_plan");
$today_date = '13/12/2022';
function get_color($id)
{
    if ($id == 2) {
        return 'text-danger';
    } else if ($id == 3) {
        return 'text-success';
    } else {
        return 'text-primary';
    }
}

?>
<div class="row">
    <div class="table-responsive">
        <table class="table  table-bordered" id="page_tb">
            <thead class="table-light">
            <tr>
                <th>م</th>
                <th>المقر</th>
                <th>الرقم الوظيفي</th>
                <th>الموظف</th>
                <th>رقم الجوال</th>
                <th>الهوية</th>
                <th>الوظيفة المقدم عليها</th>
                <th>تاريخ التقديم</th>
                <th>الحالة</th>
                <th>السبب</th>
                <th>اجراءت</th>
                <th>الانجازات</th>
                <th>الخطة</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($page > 1): ?>
                <tr>
                    <td colspan="8" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
                </tr>
            <?php endif; ?>
            <?php
            foreach ($page_rows as $row) :?>
                <?php if($row['STATUS'] == 0) {?>
                    <tr id="tr_<?= $row['EMP_NO'] ?>" style="background-color: #ffecec;">
                <?php } else {?>
                <tr id="tr_<?= $row['EMP_NO'] ?>">
                <?php } ?>
                    <td><?= $count ?></td>
                    <td><?= $row['BRANCH_NAME'] ?></td>
                    <td><?= $row['EMP_NO'] ?></td>
                    <td><?= $row['EMP_NAME'] ?></td>
                    <td><?= $row['JAWAL_NO'] ?></td>
                    <td><?= $row['ID_NO'] ?></td>
                    <td><?= $row['ADS_NAME'] ?></td>
                    <td><?= $row['ENTRY_DATE_TIME'] ?></td>
                    <td>
                       <span class="<?= get_color($row['STATUS']) ?>"><?= $row['STATUS_NAME'] ?></span>
                    </td>
                    <td>
                        <?= $row['NOTES'] ?>
                    </td>

                    <td class="text-center">
                        <?= modules::run("$MODULE_NAME/$TB_NAME/indexInlineAction", $row['SER'], 'JOB_ADS_REQUEST_TB_' . $row['SER'],1); ?>
                        |
                        <?php if ($this->user->emp_no == $row['EMP_NO']  ){?>
                          <a href="javascript:;" onclick="javascript:entry_grievance(<?= $row['SER'] ?>)"
                               title="ادخال تظلم">
                                <i class="fa fa-edit" style="color: red;"> </i>
                            </a>
                       <?php }?>
                        <?php if (HaveAccess($edit_status_url)){?>
                            <a href="javascript:;" onclick="javascript:update_detail(<?= $row['SER'] ?>)"
                               title="تعديل السبب">
                                <i class="fa fa-edit" style="color: #0048ff;"> </i>
                            </a>
                        <?php }?>
                    </td>
                    <td class="text-center">
                        <?php  if (($this->user->emp_no == $row['EMP_NO']) /*&& ($row['ADS_SER'] != 32 ||  $row['ADS_SER'] != 26 ||$row['ADS_SER'] !=  23) */|| HaveAccess($show_achieve_url)){?>
                            <?= modules::run("$MODULE_NAME/$TB_NAME/indexInlineAction", $row['SER'], 'JOB_ADS_ACHIEVEMENTS_' . $row['SER'].'_'.$row['EMP_NO'],2); ?>
                        <?php }?>
                    </td>
                    <td class="text-center">
                        <?php if (($this->user->emp_no == $row['EMP_NO']) /*&& ($row['ADS_SER'] != 32 &&  $row['ADS_SER'] != 26 && $row['ADS_SER'] !=  23) && date('d/m/Y') <= $today_date*/ || HaveAccess($show_plan_url)){?>
                            <?= modules::run("$MODULE_NAME/$TB_NAME/indexInlineAction", $row['SER'], 'JOB_ADS_PLAN_' . $row['SER'],3); ?>
                        <?php }?>
                    </td>
                    <?php
                    $count++; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
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




