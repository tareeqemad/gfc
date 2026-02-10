<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 29/09/16
 * Time: 11:44 ص
 */

$count = $offset;

$show_obj_report_url= base_url("hr/evaluation_order_archives/show_obj_report");
$show_res_report_url= base_url("hr/evaluation_order_archives/show_result_report");
$level_active = ($admin_manager==0)?$page_rows[0]['LEVEL_ACTIVE']:0;

?>

<div class="tbl_container000">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>أمر التقييم</th>
            <th>السنة</th>
            <th>نوع التقييم</th>
            <th>الرقم الوظيفي</th>
            <th>الموظف</th>
            <th>المدير المباشر</th>
    <?php if($admin_manager==0){ ?>
            <th>مدير الإدارة / المقر</th>
            <th>النتيجة الاساسية</th>
            <th>النتيجة بعد التدقيق</th>
            <th>النتيجة بعد التظلمات</th>
            <th>التقدير</th>
            <th>النتيجة النهائية</th>
            <th>التقدير</th>
            <?php if(HaveAccess($show_obj_report_url) and $level_active==5) : ?>
                <th>تقرير التظلّم</th>
            <?php endif; ?>
            <?php if(HaveAccess($show_res_report_url) and ($level_active==4 or $level_active>=6)) : ?>
                <th>تقرير النتيجة</th>
            <?php endif; ?>
    <?php } ?>

        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="16" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif;?>
        <?php foreach($page_rows as $row) :?>
            <tr class="" ondblclick="javascript:show_row_details('<?=$row['EVALUATION_ORDER_SERIAL']?>');">
                <td><?=$count?></td>
                <td><?=$row['EVALUATION_ORDER_ID']?></td>
                <td><?=$row['EVALUATION_ORDER_ID_YEAR']?></td>
                <td><?=$row['EVALUATION_ORDER_ID_TYPE']?></td>
                <td><?=$row['EMP_NO']?></td>
                <td><?=$row['EMP_NO_NAME']?></td>
                <td><?=$row['EMP_MANAGER_ID_NAME']?></td>
        <?php if($admin_manager==0){ ?>
                <td><?=$row['MANAGEMENT_MANAGER_NAME']?></td>
                <td><?=$row['FINAL_MARK_BEFORE_AUDIT']?></td>
                <td><?=$row['FINAL_MARK_BEFORE_OBJECTION']?></td>
                <td><?=$row['FINAL_MARK_AFTER_OBJECTION']?></td>
                <td><?=$row['MARK_AFTER_OBJECTION_DEGREE']?></td>
                <td><?=$row['FINAL_MARK']?></td>
                <td><?=$row['DEGREE_NAME']?></td>
                <?php if(HaveAccess($show_obj_report_url) and $level_active==5) : ?>
                    <td><li><a onclick="javascript:print_report('obj',<?=$row['EVALUATION_ORDER_SERIAL']?>,<?=$row['GRANDSON_ORDER_SERIAL']?>,<?=$row['EVALUATION_ORDER_ID']?>,<?=$row['EMP_NO']?>);" href="javascript:;"><i class="glyphicon glyphicon-print" style="font-size: 15px; color:coral"></i></a></td>
                <?php endif; ?>
                <?php if(HaveAccess($show_res_report_url) and ($level_active==4 or $level_active>=6)) : ?>
                    <td><li><a onclick="javascript:print_report('res',<?=$row['EVALUATION_ORDER_SERIAL']?>,<?=$row['GRANDSON_ORDER_SERIAL']?>,<?=$row['EVALUATION_ORDER_ID']?>,<?=$row['EMP_NO']?>);" href="javascript:;"><i class="glyphicon glyphicon-print" style="font-size: 15px; color:coral"></i></a></td>
                <?php endif; ?>
            </tr>
        <?php } ?>
            <?php
            $count++;
        endforeach;
        ?>
        </tbody>
    </table>
</div>
<?=($is_paging)?$this->pagination->create_links():''; ?>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }
    if (typeof show_page == 'undefined'){
        document.getElementById("page_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }
</script>