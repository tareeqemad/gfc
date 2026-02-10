<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*$MODULE_NAME = 'purchases';
$TB_NAME = 'Extract';
$get_url = base_url("$MODULE_NAME/$TB_NAME/get_record_id");
$edit_url = base_url("$MODULE_NAME/$TB_NAME/edit");
$count = 1;
$case = 1;*/
$count = $offset;
function class_name($case, $class_input_case)
{
    if ($class_input_case == 0) return 'case_2';
    else if ($case == 1) return 'case_0';
    else    return 'case_1';
}

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>-</th>
            <th>م</th>
            <th>مسلسل الإدخال</th>
            <th>تاريخ السند</th>
            <th> رقم أمر التوريد</th>
            <th> اسم المخزن</th>
            <th> رقم المورد</th>
            <th>اسم المورد</th>
            <th>محول لفاتورة أو قيد ؟</th>
            <th>حالة السند</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($page > 1): ?>
            <tr>
                <td colspan="10" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach ($page_rows as $row) : ?>

            <tr class="<?= class_name($row['IS_CONVERT'], $row['CLASS_INPUT_CASE']) ?>"
                ondblclick="javascript:show_row_details('<?= $row['CLASS_INPUT_ID'] ?>');">
                <td><input type="checkbox" class="checkboxes" value="<?=$row['CLASS_INPUT_ID'] ?>" data-val="<?=$row['ORDER_ID_SER'] ?>" /></td>
                <td><?= $count ?></td>
                <td><?= $row['INPUT_SEQ_T'] ?></td>
                <td><?= $row['CLASS_INPUT_DATE'] ?></td>
                <td><?= $row['ORDER_ID'] ?></td>
                <td><?= $row['STORE_NAME'] ?></td>
                <td><?= $row['CUSTOMER_RESOURCE_ID'] ?></td>
                <td><?= $row['CUST_NAME'] ?></td>
                <td><?php if ($row['IS_CONVERT'] == 1) echo "نعم"; else  echo "لا"; ?></td>
                <td><?= $row['CLASS_INPUT_CASE_NAME'] ?></td>
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
