<?php
/**
 * Created by PhpStorm.
 * User: TELBAWAB
 * Date: 07/01/22
 * Time: 09:37 ص
 */
$MODULE_NAME= 'stores';
$TB_NAME= 'receipt_class_input';
//$TB_NAME2= 'constant_details';
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_record_id");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
//$get_details_url =base_url("$MODULE_NAME/$TB_NAME2/get_page");
$count = 1;
$case=1;
?>
<table class="table" id="receipt_class_input_tb" data-container="container">
    <thead>
    <tr>

        <th>م</th>
        <th>رقم محضر الفحص و الاستلام</th>
        <th> تاريخ الاستلام </th>
        <th>رقم أمر التوريد</th>
        <th> اسم المخزن </th>
        <th>اسم المورد </th>
        <th>رقم إرسالية المورد</th>
        <th>اعتماد المحضر؟</th>
    </tr>
    </thead>
    <tbody>
    <?php  foreach($get_all as $row) {  $count++; ?>
        <tr ondblclick="javascript:get_to_link('<?= base_url('stores/receipt_class_input/get_record_id/').'/'.$row['RECEIPT_CLASS_INPUT_ID'].'/'.( isset($action)?$action.'/':'').('1').'/'.($type) ?>');"  class="case_<?= $row['RECEIPT_CLASS_INPUT_ID'] ?>">
            <td><?=$count?></td>
            <td><?= $row['RECEORD_ID_TEXT'] ?></td>
            <td><?= $row['RECEIPT_CLASS_INPUT_DATE'] ?></td>
            <td><?= $row['ORDER_ID'] ?></td>
            <td><?= $row['STORE_NAME'] ?></td>
            <td><?= $row['CUST_NAME'] ?></td>
            <td><?= $row['SEND_ID'] ?></td>
            <td><?= $row['RECORD_CASE_NAME']?></td>
        </tr>
    <?php } ?>
    </tbody>

</table>
