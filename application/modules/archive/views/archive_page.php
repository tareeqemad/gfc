<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 14/02/15
 * Time: 12:08 م
 */
$MODULE_NAME= 'archive';
$TB_NAME= 'archive';


$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");
$count = 1;

function getUrl($file_type,$file_no){
    $url="";
    if ($file_type==1)
        $url="";
    else  if ($file_type==2)
        $url=base_url("projects/projects/get/$file_no/index");
    else  if ($file_type==3)
        $url=base_url("stores/invoice_class_input/get_id/$file_no/edit/1");
    else  if ($file_type==4)
        $url=base_url("stores/stores_class_input/get_id/$file_no/edit/1");

    return   $url;
}
?>

<table class="table" id="archive_tb" data-container="container">
    <thead>
    <tr>
        <th  >#</th>
        <th>م</th>
        <th> تاريخ المعاملة  </th>
        <th>نوع المعاملة</th>
        <th>رقم المعاملة</th>
        <th>المقر</th>
        <th>عرض الأرشيف</th>
        <th>عرض المصدر</th>


    </tr>
    </thead>

    <tbody>
    <?php  foreach($get_all as $row) {  $count++; ?>
        <tr  class="case_<?= $row['FILE_ID'] ?>" >

            <td><input type="checkbox" class="checkboxes" value="<?=$row['FILE_ID'] ?>" /></td>
            <td><?=$count?></td>
            <td><?= $row['FILE_DATE'] ?></td>
            <td><?= $row['FILE_TYPE_NAME'] ?></td>
            <td><?= $row['FILE_NO'] ?></td>
            <td><?= $row['BRANCH_NAME'] ?></td>
            <td><li><a onclick="javascript:get_to_link('<?= base_url('archive/archive/get_id/').'/'.$row['FILE_ID'] ?>');" href="javascript:;"><i class="glyphicon glyphicon-download"></i></a> </li>

            <td><li><a onclick="javascript:get_to_link('<?= getUrl($row['FILE_TYPE'],$row['FILE_NO'])?>');" href="javascript:;"><i class="glyphicon glyphicon-download"></i></a> </li>
            </td>




        </tr>
    <?php } ?>




    </tbody>

</table>

<script type="text/javascript">
    $(document).ready(function() {
        $('#archive_tb').dataTable({
            "lengthMenu": [ [10,20,30,40,50,100, -1], [10,20,30,40,50,100, "الكل"] ],
            "sPaginationType": "full_numbers"
        });
    });

    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>
