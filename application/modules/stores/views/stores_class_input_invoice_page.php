<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 14/12/14
 * Time: 09:31 ص
 */

$MODULE_NAME= 'stores';
$TB_NAME= 'stores_class_input';
//$TB_NAME2= 'constant_details';
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$invoice_url =base_url("$MODULE_NAME/$TB_NAME/get_invoice_id");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit"); //
//$get_details_url =base_url("$MODULE_NAME/$TB_NAME2/get_page");
$count = 1;
?>

<table class="table" id="stores_class_input_tb" data-container="container">
    <thead>
    <tr>
        <th  >#</th>
        <th>م</th>
        <th>تاريخ السند </th>
        <th>رقم  فاتورة الشراء</th>
        <th>رقم أمر التوريد</th>
        <th> اسم المخزن </th>
        <th>اسم المورد </th>

        <? if(HaveAccess($invoice_url)) { ?>    <th>إدخال فاتورة الشراء</th> <? }?>
    </tr>
    </thead>

    <tbody>
    <?php  foreach($get_all as $row) {  $count++; ?>
        <tr   class="case_<?= $row['CLASS_INPUT_ID'] ?>" >

            <td><input type="checkbox" class="checkboxes" value="<?=$row['CLASS_INPUT_ID'] ?>" /></td>
            <td><?=$count?></td>
            <td><?= $row['CLASS_INPUT_DATE'] ?></td>
            <td><?= $row['INVOICE_ID'] ?></td>
            <td><?= $row['ORDER_ID'] ?></td>
            <td><?= $row['STORE_NAME'] ?></td>

            <td><?= $row['CUST_NAME'] ?></td>


            <? if(HaveAccess($invoice_url)) { ?>      <td><i onclick="javascript:get_to_link('<?= base_url('stores/stores_class_input/get_invoice_id/').'/'.$row['CLASS_INPUT_ID'].'/'.( isset($action)?$action.'/':'').('2') ?>');" class="icon-align-center icon-arrows-h "></i></td> <? }?>

        </tr>
    <?php } ?>




    </tbody>

</table>

<script type="text/javascript">
    $(document).ready(function() {
        $('#stores_class_input_tb').dataTable({
            "lengthMenu": [ [10,20,30,40,50,100, -1], [10,20,30,40,50,100, "الكل"] ],
            "sPaginationType": "full_numbers"
        });
    });

    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>
