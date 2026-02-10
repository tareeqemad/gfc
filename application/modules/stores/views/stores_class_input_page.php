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
$archive_url=base_url("archive/archive/archive_doc");
$get_archive_url=base_url("archive/archive/get_id");
$count = 1;

?>

<table class="table" id="stores_class_input_tb" data-container="container">
    <thead>
    <tr>
        <th  >#</th>
        <th>م</th>
        <th>تاريخ سند الإدخال </th>
        <th>رقم أمر التوريد</th>
        <th> رقم إرسالية المورد</th>
        <th> اسم المخزن </th>
        <th>اسم المورد </th>
        <th>حالة السند</th>
      <?php if (HaveAccess($archive_url)){ ?>
          <th> أرشيف السند</th> <?php } ?>

          </tr>
    </thead>

    <tbody>
    <?php  foreach($get_all as $row) {  $count++; ?>
        <tr   ondblclick="javascript:get_to_link('<?= base_url('stores/stores_class_input/get_id/').'/'.$row['CLASS_INPUT_ID'].'/'.( isset($action)?$action.'/':'').('1') ?>');"
              class="case_<?= $row['CLASS_INPUT_ID'] ?>" >

            <td><input type="checkbox" class="checkboxes" value="<?=$row['CLASS_INPUT_ID'] ?>" /></td>
            <td><?=$count?></td>
            <td><?= $row['CLASS_INPUT_DATE'] ?></td>
            <td><?= $row['ORDER_ID'] ?></td>
            <td><?= $row['SEND_ID2'] ?></td>
            <td><?= $row['STORE_NAME'] ?></td>

            <td><?= $row['CUST_NAME'] ?></td>
            <td><?php if ($row['CLASS_INPUT_CASE']==0) echo "ملغى"; else if ($row['CLASS_INPUT_CASE']==1) echo "مدخل";  if ($row['CLASS_INPUT_CASE']==2) echo "معتمد";?></td>

            <?php if (HaveAccess($archive_url)){ ?>
            <td><?php if ($row['ARCHIVE_NUM']==0) {?>
                <li><a onclick="javascript:archive(4,'<?=$row['CLASS_INPUT_ID']?>');" href="javascript:;"><i class="glyphicon glyphicon-upload"></i></a> </li>
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
        $('#stores_class_input_tb').dataTable({
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
