<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 07/02/19
 * Time: 08:32 ص
 */
$MODULE_NAME='gis';
$TB_NAME="LV_Network_controller";
$count = $offset;

?>
<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>

        <th>م</th>
        <th>كود الشبكة - خاص ببرنامج GIS</th>
        <th>نوع الشبكة</th>
        <th>نوع مادة الشبكة</th>
        <th>مساحة مقطع ونوع الشبكة</th>
        <th>ملكية الشبكة</th>
        <th>طول الشبكة</th>
            <?php (base_url("gis/LV_Network_controller/get_LV_Network_info/"))  ?> <th>عرض/تحرير</th> <?php  ?>
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
            <td><?=@$row['NETWORK_CODE']?></td>
            <td><?=@$row['NETWORK_TYPE']?></td>
            <td><?=@$row['PHASES_CONDUCTORS_MATERIAL']?></td>
            <td><?=@$row['LV_NETWORK_TYPE']?></td>
            <td><?=@$row['NETWORK_PROPERTY']?></td>
            <td><?=@$row['LV_NETWORK_LENGTH_M']?></td>
            <?php (base_url("gis/main/get_poles_info/"))?>
            <td>  <a href="<?=base_url("gis/LV_Network_controller/get_LV_Network_info/{$row['ID_PK']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>
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


