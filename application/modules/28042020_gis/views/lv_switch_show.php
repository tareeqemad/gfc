<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 07/02/19
 * Time: 12:18 م
 */
$MODULE_NAME='gis';
$TB_NAME="LV_Switches_controller";
$count = $offset;

?>
<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>

        <th>م</th>
        <th>كود السكينة</th>
        <th>اتجاه السكينة </th>
        <th>نوع السكينة </th>
        <th>حالة السكينة</th>
        <th>نوع ومقطع الكابل المغذي للسكينة </th>
            <?php (base_url("gis/LV_Switches_controller/get_lv_switch_info/"))  ?> <th>عرض/تحرير</th> <?php  ?>
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
            <td><?=@$row['SWITCH_MATERIAL_ID']?></td>
            <td><?=@$row['LTL_SWITCH_DIRECTION']?></td>
            <td><?=@$row['LTL_SWITCH_TYPE']?></td>
            <td><?=@$row['LTL_SWITCH_INNER_CABLE_SIZE']?></td>
            <td><?=@$row['LTL_SWITCH_CONDITION']?></td>
            <?php (base_url("gis/LV_Switches_controller/get_lv_switch_info/"))?>
            <td>  <a href="<?=base_url("gis/LV_Switches_controller/get_lv_switch_info/{$row['ID']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>
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


