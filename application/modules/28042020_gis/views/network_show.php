<?php
/*
 * Created by PhpStorm.
 * User: ljasser
 * Date: 09/12/18
 * Time: 11:07 ص
 */
$MODULE_NAME='gis';
$TB_NAME="net_controller";
$count = $offset;
?>
<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>م</th>
            <th> نوع التركيب</th>
            <th>نوع مادة الموصل للفازات</th>
            <th>مساحة مقطع ونوع الفازات</th>
            <th>رقم عامود/غرفة البداية - خاص ببرنامج GIS</th>
            <th>رقم عامود/غرفة النهاية - خاص ببرنامج GIS</th>
            <?php (base_url("gis/net_controller/get_network_info/"))?> <th>عرض/تحرير</th>
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

                <td><?=@$row['NETWORK_INST_TYPE']?></td>
                <td><?=@$row['PHASES_COND_MATERIAL']?></td>
                <td><?=@$row['PHASE_COND_TYPE']?></td>
                <td><?=@$row['S_MV_POLE_ROOM_CODE']?></td>
                <td><?=@$row['E_MV_POLE_ROOM_CODE']?></td>
                <?php (base_url("gis/net_controller/get_poles_info/"))?>
                <td>  <a href="<?=base_url("gis/net_controller/get_network_info/{$row['ID1']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>
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
