<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 14/12/14
 * Time: 09:31 ص
 */

$MODULE_NAME= 'stores';
$TB_NAME= 'invoice_class_input';
//$TB_NAME2= 'constant_details';
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");

$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit"); //
//$get_details_url =base_url("$MODULE_NAME/$TB_NAME2/get_page");
$count = 1;
$archive_url=base_url("archive/archive/archive_doc");
$get_archive_url=base_url("archive/archive/get_id");
$financial_chain_url=base_url("financial/financial_chains/get");
?>

<table class="table" id="invoice_class_input_tb" data-container="container">
    <thead>
    <tr>
        <th  >#</th>
        <th>م</th>
        <th>رقم الفاتورة</th>
        <th>تاريخ الفاتورة</th>
        <th>تاريخ السند </th>
        <th>رقم أمر التوريد</th>
        <th> اسم المخزن </th>
        <th>اسم المورد </th>
        <th>رقم القيد</th>
        <th> قيمة الخصم </th>
        <th> مبلغ الفاتورة </th>
        <th>الحالة</th>
        <?php if (HaveAccess($archive_url)){ ?>
            <th> أرشيف السند</th> <?php } ?>
    </tr>
    </thead>

    <tbody>
    <?php  foreach($get_all as $row) {  $count++; ?>
        <tr   ondblclick="javascript:get_to_link('<?= base_url('stores/invoice_class_input/get_id/').'/'.$row['CLASS_INPUT_ID'].'/'.( isset($action)?$action.'/':'').(isset($case)?$case:'3') ?>');"
              class="case_<?= $row['CLASS_INPUT_ID'] ?>" >

            <td><input type="checkbox" class="checkboxes" value="<?=$row['CLASS_INPUT_ID'] ?>" /></td>
            <td><?=$count?></td>
            <td><?= $row['INVOICE_ID'] ?></td>
            <td><?= $row['INVOICE_DATE'] ?></td>
            <td><?= $row['CLASS_INPUT_DATE'] ?></td>
            <td><?= $row['ORDER_ID'] ?></td>
            <td><?= $row['STORE_NAME'] ?></td>
            <td><?= $row['CUST_NAME'] ?></td>
            <td> <a target="_blank" href="<?php //if (HaveAccess($financial_chain_url))
                echo $financial_chain_url."/".$row['QEED_NO']."/index?type=4"; ?>"><?=$row['QEED_NO']?><a></td>
            <td><?= $row['DISCOUNT_VAL'] ?></td>
            <td><?= $row['INVOICE_VAL'] ?></td>
            <td><?php if($row['INVOICE_CASE']==0) echo "ملغي"; else if($row['INVOICE_CASE']==1) echo "مدخل"; else if($row['INVOICE_CASE']==2) echo "معتمد";  else if($row['INVOICE_CASE']==3) echo "مدقق";?></td>
            <?php if (HaveAccess($archive_url)){ ?>
            <td><?php
                if ($row['ARCHIVE_NUM']==0) {?>
                    <li><a onclick="javascript:archive(3,'<?=$row['CLASS_INPUT_ID']?>');" href="javascript:;"><i class="glyphicon glyphicon-upload"></i></a> </li>
                <?php } else {?>
                <?php  if (HaveAccess($get_archive_url)) { ?> <li><a    onclick="javascript:get_to_link('<?= $get_archive_url.'/'.$row['ARCHIVE_NUM'] ?>');" href="javascript:;"><i class="glyphicon glyphicon-file"></i></a> </li><?php } ?>

                <?php } ?>
            </td>

            <?php } ?>
         </tr>
    <?php } ?>




    </tbody>

</table>

<script type="text/javascript">
    $(document).ready(function() {
        $('#invoice_class_input_tb').dataTable({
            "lengthMenu": [ [10,20,30,40,50,100, -1], [10,20,30,40,50,100, "الكل"] ],
            "sPaginationType": "full_numbers"
        });
    });
    function archive(file_typex,file_nox){

        if(confirm('هل تريد إتمام العملية ؟')){
            var h= '';

            get_data('<?php echo $archive_url ;?>',{file_type:file_typex,file_no:file_nox },function(data){
                if(data )
                    console.log(data);

                setTimeout(function(){
                    get_to_link('<?= base_url('archive/archive/get_id')?>/'+data);

                }, 1000);

            },'html');

        }

    }

    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>
