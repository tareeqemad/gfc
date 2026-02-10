<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * project: Ahmed Barakat
 * Date: 8/28/14
 * Time: 8:46 AM
 */
$adopt_url= base_url("budget/projects/adopt");
$adopt4_url= base_url("budget/projects/specialist_admin");
$adoptp_url= base_url("budget/projects/public_adopt4");
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
												<? /*= $row_count */ ?> </span>
</a>
-->

<div class="tbl_container">
    <table class="table" id="projectTbl" data-container="container">
        <thead>
        <tr>
            <th title="تحديد الكل"> <input type="checkbox" id="select-all" onclick="checkAll(this);" name="select-all"/> </th>
            <th>#</th>
            <th>رقم المشروع</th>


            <th>اسم المشروع</th>
            <th>التاريخ</th>
            <th>نوع المشروع</th>
            <th>التصنيف الفني</th>
            <th>الفصل </th>
            <th>الفرع</th>
            <th>المرحلة</th>
            <th>إجمالي المبلغ</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($page > 1): ?>
            <tr>
                <td colspan="11" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>

            </tr>
        <?php endif; ?>
        <?php foreach ($rows as $row) : ?>
            <tr ondblclick="javascript:get_to_link('<?= base_url("budget/projects/get/{$row['PROJECT_SERIAL']}/{$action}") ?>');">
                <td>
                    <?php  if ( (($case != 6) &&($row['PROJECT_CASE']== 1)&& (HaveAccess($adopt_url))) || (($case != 6) &&($row['PROJECT_CASE']== 3) && (HaveAccess($adopt4_url)))) {
                        $check= "<input name='adopt_no[]' type='checkbox' class='checkboxes' value='{$row['PROJECT_SERIAL']}' />";
                    }else{
                        $check= '';
                    } echo $check; ?>
                </td>
                <td><?= $count ?>

                </td>
                <td><?= $row['PROJECT_SERIAL'] ?></td>


                <td><?= $row['PROJECT_NAME'] ?></td>
                <td><?= $row['ENTRY_DATE'] ?></td>
                <td><?= $row['PROJECT_TYPE_NAME'] ?></td>
                <td><?= $row['PROJECT_TEC_TYPE_NAME'] ?></td>
                <td><?= $row['SECTION_NAME'] ?></td>
                <td><?= $row['BRANCH_NAME'] ?></td>
                <td><?= $row['PROJECT_TOTAL'] ?></td>
                <td class="project_<?= $row['PROJECT_CASE'] < 0 ? 1 : $row['PROJECT_CASE'] ?>"><?= budget_project_case($row['PROJECT_CASE']) ?></td>
                <td>


                    <?php if ($row['ENTRY_USER'] == get_curr_user()->id && $row['PROJECT_CASE'] < 1 && HaveAccess(base_url('budget/Projects/delete'))) : ?>
                        <a href="javascript:;" onclick="javascript:delete_project(this,<?= $row['PROJECT_SERIAL'] ?>);"><i
                                class="icon icon-trash delete-action"></i> </a>
                    <?php endif; ?>

                    <?php if ($row['PROJECT_CASE'] >= 4) : ?>

                        <?php if ($row['ACTIVITIES_PLAN_TB'] == 1) : ?>
                            <a href="<?= base_url('planning/planning/create/' . $row['PROJECT_SERIAL']) ?>"
                               class="btn btn-xs btn-success">ترحيل
                                للتخطيط</a>
                        <?php else : ?>
                            <a href="<?= base_url('planning/planning/get/' . $row['ACTIVITIES_PLAN_TB_SEQ']) ?>"
                               class="btn btn-xs btn-success"></a>
                        <?php endif; ?>

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



    function checkAll(obj){

        var chk= false;
        if(obj.checked) {
            chk= true;
        }
        $('.checkboxes:checkbox').each(function() {
            this.checked = chk;
        });
    }
</script>