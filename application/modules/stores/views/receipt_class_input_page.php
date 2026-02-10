<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 20/11/14
 * Time: 09:37 ص
 */


$MODULE_NAME = 'stores';
$TB_NAME = 'receipt_class_input';
//$TB_NAME2= 'constant_details';
$get_url = base_url("$MODULE_NAME/$TB_NAME/get_id");
$edit_url = base_url("$MODULE_NAME/$TB_NAME/edit");
//$get_details_url =base_url("$MODULE_NAME/$TB_NAME2/get_page");
$transform_url = base_url("$MODULE_NAME/$TB_NAME/transform");
$count = 1;
$print_url = 'https://itdev.gedco.ps/gfc.aspx?data=' . get_report_folder() . '&';

function class_name($record_case)
{
    if ($record_case == 3) return 'case_6';
    else if ($record_case == 2) return 'case_0';
    else if ($record_case == 1) return 'case_3';
    else    return 'case_1';
}

?>
<style>
    .table tr.case_6 td {
        background-color: #B6DBFF;
    }
</style>

<table class="table" id="receipt_class_input_tb" data-container="container">
    <thead>
    <tr>
        <th>#</th>
        <th>م</th>
        <th>تاريخ استلام المواد</th>
        <th>تاريخ الإدخال</th>
        <th>رقم أمر التوريد</th>
        <th>رقم أمر التوريد الفعلي</th>
        <th> اسم المخزن</th>
        <th>اسم المورد</th>
        <th>رقم إرسالية المورد</th>
        <th>اعتماد الإدخال؟</th>
        <th>طباعة</th>
        <th>رقم محضر الفحص و الاستلام</th>
        <th>اعتماد المحضر؟</th>
        <th> محول كسند مخزني؟</th>
        <!--    <th> تنفيذ إدخال مخزني</th>-->
    </tr>
    </thead>

    <tbody>
    <?php foreach ($get_all as $row) {
        $count++; ?>
        <tr class="<?= class_name($row['RECORD_CASE']) ?>"
            ondblclick="javascript:get_to_link('<?= base_url('stores/receipt_class_input/get_id/') . '/' . $row['RECEIPT_CLASS_INPUT_ID'] . '/' . (isset($action) ? $action . '/' : '') . (isset($case) ? $case : '') ?>');"
            class="case_<?= $row['RECEIPT_CLASS_INPUT_ID'] ?>">

            <td><input type="checkbox" class="checkboxes" value="<?= $row['RECEIPT_CLASS_INPUT_ID'] ?>"/></td>
            <td><?= $count ?></td>
            <td><?= $row['RECEIPT_CLASS_INPUT_DATE'] ?></td>
            <td><?= $row['ENTERY_DATE'] ?></td>
            <td><?= $row['ORDER_ID'] ?></td>
            <td><?= $row['REAL_ORDER_ID'] ?></td>
            <td><?= $row['STORE_NAME'] ?></td>

            <td><?= $row['CUST_NAME'] ?></td>
            <td><?= $row['SEND_ID'] ?></td>
            <td><?php if ($row['SEND_CASE'] == 2) echo "معتمد"; else echo "غير معتمد"; ?></td>

            <td><i onclick="javascript:print('<?php echo $row['RECEIPT_CLASS_INPUT_ID'] ?>');"
                   class="glyphicon glyphicon-print"></i></td>
            <td><?= $row['RECEORD_ID_TEXT'] ?></td>
            <td>
                <?php if ($row['RECORD_CASE'] == 1) echo "محول";
                else if ($row['RECORD_CASE'] == 2) echo "اعتماد  اللجنة ";
                else if ($row['RECORD_CASE'] == 3) echo "اعتماد المدير العام "; else echo "غير محول"; ?>
            </td>

            <td><?php if ($row['IS_CONVERT'] == 1) echo "محول"; else echo "غير محول"; ?></td>

            <!--   <td><?php if ($row['RECORD_CASE'] == 2 && $row['IS_CONVERT'] == 0) { ?>
            <i class="glyphicon glyphicon-plus" onclick="transform('<?= $row['RECEIPT_CLASS_INPUT_ID'] ?>');"></i>
             <?php } ?></td>-->
        </tr>
    <?php } ?>


    </tbody>

</table>

<script type="text/javascript">

    function print(id) {
        _showReport('<?php echo $print_url;?>report=RECPIT_CLASS_INPUT&params[]=' + id);
    }

    function transform(idx) {
        if (confirm('هل تريد إتمام العملية ؟')) {
            var h = '';

            get_data('<?php echo $transform_url;?>', {id: idx}, function (data) {
                if (data)
                    console.log(data);
                ///  success_msg('رسالة','تمت العملية بنجاح');
                setTimeout(function () {
                    //http://localhost/test/stores/stores_class_input/get_id/210/edit/1
                    get_to_link('<?= base_url('stores/stores_class_input/get_id')?>/' + data + '/edit/1');

                }, 1000);

            }, 'html');
        }
    }

    if (typeof initFunctions == 'function') {
        initFunctions();
    }

</script>
