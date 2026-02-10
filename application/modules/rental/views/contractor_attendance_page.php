<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 16/09/17
 * Time: 12:08 م
 */

$count = $offset;

?>

<style>
    .note{max-width:250px; max-height: 20px; overflow: hidden; text-align: right}
    .note2{max-width:300px;text-align: right}
</style>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>مسلسل الحركة</th>
            <th>رقم المتعاقد</th>
            <th>اسم المتعاقد</th>
            <th>رقم السيارة</th>
            <th>تاريخ  توقيع المتعاقد</th>
            <th>اليوم</th>
            <th>وقت الحضور</th>
            <th>وقت الانصراف</th>
            <th>الفترة بالساعات </th>
            <th>الاضافي  </th>
            <th>ملاحظات</th>
            <th>مصدر التوقيع</th>
            <th>تاريخ الادخال</th>
            <th>المدخل</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="16" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

        <tr ondblclick="javascript:show_row_details('<?=$row['SER']?>');" >
            <td><?=$count?></td>
            <td><?=$row['SER']?></td>
            <td><?=$row['CONTRACTOR_FILE_ID']?></td>
            <td><?=$row['CONTRACTOR_NAME']?></td>
            <td><?=$row['CAR_NUM']?></td>
            <td><?=$row['SIGNATURE_DATE']?></td>
            <td><?=$row['DAY_AR']?></td>
            <td><?=$row['SIGNATURE_TIME_IN_']?></td>
            <td><?=$row['SIGNATURE_TIME_OUT_']?></td>
            <td><?=$row['HOURS']?></td>
            <td><?=$row['OVERTIME_HOURS']?></td>
            <td><div title="انقر  لاظهار واخفاء البيان" class="note"><?=$row['NOTE']?></div></td>
            <td><?=$row['SIGNATURE_SOURCE_NAME']?></td>
            <td><?=$row['ENTRY_DATE']?></td>
            <td title="<?=$row['ENTRY_USER_NAME']?>"><?=get_short_user_name($row['ENTRY_USER_NAME'])?></td>
            <?php
            $count++;
            ?>
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
    if (typeof show_page == 'undefined'){
        document.getElementById("page_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }

    show_notes();

</script>
