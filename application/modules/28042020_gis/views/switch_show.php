<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 27/01/19
 * Time: 12:03 م
 */
$MODULE_NAME='gis';
$TB_NAME="switch_controller";
$count = $offset;

?>
<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>

            <th>م</th>
            <th>كود سكينة ض.ع رقم 1</th>
            <th> كود العامود</th>
            <th>كود السكينة </th>
            <th>الشركة المصنعة لسكينة ض.ع </th>
            <th>عملية التشغيل لسكينة</th>
            <th> نوع التحكم في سكينة</th>
            <th> نوع عازل سكينة</th>

            <?php (base_url("gis/switch_controller/get_switch_info/"))  ?> <th>عرض/تحرير</th> <?php  ?>
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
            <td><?=@$row['ISOLATING_SWITCH_CODE']?></td>
            <td><?=@$row['POLE_MATERIAL_ID']?></td>
            <td><?=@$row['SWITCH_MATERIAL_ID']?></td>
            <td><?=@$row['IS_MANUFACTURER']?></td>
            <td><?=@$row['IS_OPERATION']?></td>
            <td><?=@$row['IS_CONTROL']?></td>
            <td><?=@$row['IS_INSULATOR']?></td>

            <?php (base_url("gis/switch_controller/get_switch_info/"))?>
            <td>  <a href="<?=base_url("gis/switch_controller/get_switch_info/{$row['ID']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>
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
