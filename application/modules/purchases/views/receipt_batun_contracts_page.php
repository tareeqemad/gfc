<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
$get_eval_url = base_url("supplier_evaluation/supplier_evaluation_marks/get");
$prepare_payment_url = base_url("payment/payment_cover/create");
$get_prepare_payment_url = base_url("payment/payment_cover/get");
$count = $offset;
function class_name($adopt)
{

    if ($adopt == '') {
        return 'case_1';
    }
    if ($adopt == 0) {
        return 'case_0';
    }
    if ($adopt == 1) {
        return 'case_2';
    }
    if ($adopt == 10) {
        return 'case_4';
    }
    if ($adopt == 20) {
        return 'case_5';
    }
    if ($adopt == 30) {
        return 'case_6';
    }
}

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>-</th>
            <th>م</th>
            <th>رقم محضر الفحص و الإستلام</th>
            <th>مسلسل أمر التوريد</th>
            <th>رقم أمر التوريد (s)</th>
            <th>رقم أمر التوريد الفعلي</th>
            <th>اسم المورد</th>
            <th>تصنيف الأعمال</th>
            <th>حالة المحضر</th>


        </tr>
        </thead>
        <tbody>
        <?php if ($page > 1): ?>
            <tr>
                <td colspan="10" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach ($page_rows as $row) : ?>

            <tr ondblclick="show_row_details('<?= $row['RECEIPT_CLASS_INPUT_ID'] ?>');" class="<?= class_name($row['ADOPT']) ?>">
                <td><?php if($row['ADOPT']==20 and $row['SER_ADOPT']==''){?><input type="checkbox" class="checkboxes" value="<?=$row['RECEIPT_CLASS_INPUT_ID'] ?>" data-val="<?=$row['ORDER_ID'] ?>"  /><?php  }else if($row['SER_ADOPT']!='') echo 'مرحل للإعتماد'?></td>
                <td><?= $count ?></td>
                <td><?= $row['RECEIPT_CLASS_INPUT_ID'] ?></td>
                <td><?= $row['ORDER_ID'] ?></td>
                <td><?= $row['ORDER_ID_TEXT'] ?></td>
                <td><?= $row['REAL_ORDER_ID'] ?></td>
                <td><?= $row['CUSTOMER_RESOURCE_ID_NAME'] ?></td>
                <td><?= $row['CLASS_TYPE_NAME'] ?></td>
                <td><?= $row['ADOPT_NAME'] ?></td>

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
