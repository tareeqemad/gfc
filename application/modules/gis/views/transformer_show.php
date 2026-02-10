<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 02/02/19
 * Time: 01:19 م
 */
$MODULE_NAME='gis';
$TB_NAME="transformer_controller";
$count = $offset;
?>
<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
        <th>م</th>
        <th>قدرة المحول</th>
        <th>كود المحول</th>
        <th>كود عامود ض.ع</th>
        <th>اسم المحول/ المحولات باللغة العربية</th>
        <th>اسم المحول/ المحولات باللغة الإنجليزية</th>
            <?php (base_url("gis/transformer_controller/get_transformer_info/"))  ?> <th>عرض/تحرير</th> <?php  ?>
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
            <td><?=@$row['TR_RATING_KVA']?></td>
            <td><?=@$row['TRANS_MATERIAL_ID']?></td>
            <td><?=@$row['MV_POLE_CODE']?></td>
            <td><?=@$row['TRANSFORMER_NAME_AR']?></td>
            <td><?=@$row['TRANSFORMER_NAME_EN']?></td>
            <?php (base_url("gis/transformer_controller/get_transformer_info/"))?>
            <td>  <a href="<?=base_url("gis/transformer_controller/get_transformer_info/{$row['ID']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>
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


