<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 06/02/19
 * Time: 09:41 ص
 */
$MODULE_NAME='gis';
$TB_NAME="LV_POLE_controller";
$count = $offset;

?>
<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>

        <th>م</th>
        <th>رقم العمود</th>
        <th>نوع العامود</th>
        <th>مقاس العامود</th>
        <th>ارتفاع العامود</th>
        <th>ملكية العامود</th>
        <th>حالة العامود</th>
        <th>تأريض العامود</th>
            <?php (base_url("gis/LV_POLE_controller/get_LV_Poles_info/"))  ?> <th>عرض/تحرير</th> <?php  ?>
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
            <td><?=@$row['POLE_MATERLAL_ID']?></td>
            <td><?=@$row['POLE_TYPE']?></td>
            <td><?=@$row['POLE_SIZE']?></td>
            <td><?=@$row['POLE_HEIGHT']?></td>
            <td><?=@$row['POLE_PROPERTY']?></td>
            <td><?=@$row['POLE_CONDITION']?></td>
            <td><?=@$row['POLE_EARTH']?></td>
            <?php (base_url("gis/main/get_poles_info/"))?>
            <td>  <a href="<?=base_url("gis/LV_POLE_controller/get_LV_Poles_info/{$row['ID_PK']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>
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

