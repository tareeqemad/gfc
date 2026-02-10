<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
$count = $offset;

?>
<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>

            <th>#</th>
            <th>اسم المورد</th>
            <th>اسلوب تعامل المورد مع موظفي الشركة</th>
            <th>مدى التزام المورد بجودة المواد الموردة بالمواصفات المطلوبة</th>
            <th>مدى توفر المواد والوسائل اللازمة لعملية النقل وسرعة توريدها</th>
            <th>مدى التزام المورد بالقوانين وشروط الشركة</th>
            <th>الخيارات البديلة وسهولة التوصل</th>
            <th>اسعار المواد الموردة كمقارنة بأسعار السوق</th>
            <th>المجموع</th>


        </tr>
        </thead>
        <tbody>
        <?php if ($page > 1): ?>
            <tr>
                <td colspan="10" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach ($page_rows as $row) :
            $total = round($row['1_AVG'] / $row['CNTR'], 0) + round($row['5_AVG'] / $row['CNTR'], 0) + round($row['9_AVG'] / $row['CNTR'], 0) + round($row['13_AVG'] / $row['CNTR'], 0) + round($row['17_AVG'] / $row['CNTR'], 0) + round($row['20_AVG'] / $row['CNTR'], 0)
            ?>
            <?php if ($type == '1') { ?>
            <tr ondblclick="show_row_details('<?= $row['SER'] ?>');">
        <?php } else {
            ?>
            <tr>
            <?php
        }
            ?>
            <td><?= $count ?></td>
            <td><?= $row['CUSTOMER_ID_NAME'] ?></td>
            <td><?= round($row['1_AVG'] / $row['CNTR'], 0) ?></td>
            <td><?= round($row['5_AVG'] / $row['CNTR'], 0) ?></td>
            <td><?= round($row['9_AVG'] / $row['CNTR'], 0) ?></td>
            <td><?= round($row['13_AVG'] / $row['CNTR'], 0) ?></td>
            <td><?= round($row['17_AVG'] / $row['CNTR'], 0) ?></td>
            <td><?= round($row['20_AVG'] / $row['CNTR'], 0) ?></td>
            <td><?= $total ?></td>

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
