<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 09/02/19
 * Time: 12:38 م
 */

$MODULE_NAME='gis';
$TB_NAME="Room_Controller";
$count = $offset;

?>
<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>

            <th>م</th>
            <th>رقم الغرفة</th>
            <th>تاريخ انشاء الغرفة</th>
            <th>نوع الغرفة حسب المكان </th>

            <?php (base_url("gis/Room_Controller/get_Room_info/"))  ?> <th>عرض/تحرير</th> <?php  ?>
            <?php
            $count++;
            ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach($page_rows as $row) :

            ?>

            <tr>
                <td><?=$count?></td>
                <td><?=@$row['ROOM__CODE']?></td>
                <td><?=@$row['ROOM_CONST']?></td>
                <td><?=@$row['ROOM_TYPE']?></td>

                <?php (base_url("gis/Room_Controller/get_Room_info/"))?>
                <td>  <a href="<?=base_url("gis/Room_Controller/get_Room_info/{$row['ID1']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>
                <?php ?>

                <?php $count++ ?>
            </tr>
        <?php endforeach;?>

        </tbody>
    </table>
</div>
<script type="text/javascript">
    /*  $(document).ready(function() {
     $('#help_ticket').dataTable({
     "lengthMenu": [ [10,20,30,40,50,100, -1], [10,20,30,40,50,100, "الكل"] ],
     "sPaginationType": "full_numbers"
     });
     });*/

    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>



