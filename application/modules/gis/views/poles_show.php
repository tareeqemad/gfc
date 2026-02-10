<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 09/12/18
 * Time: 11:07 ص
 */
$MODULE_NAME='gis';
$TB_NAME="main";
$count = $offset;
$t=time();
$r =rand(10,99);
$c=$t.$r;

?>
<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>

            <th>م</th>
            <th>رقم العامود</th>
            <th>نوع العامود</th>
            <th>ارتفاع العامود</th>
            <th>حالة العامود</th>
            <th>نوع القاعدة</th>
            <th>حالة القاعدة</th>
            <th>تأريض الأرض</th>

            <?php (base_url("gis/main/get_poles_info/"))  ?> <th>عرض/تحرير</th> <?php  ?>
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
                                <td><?=@$row['POLE_MATERIAL_ID']?></td>
                                <td><?=@$row['MV_POLE_TYPE']?></td>
                                <td><?=@$row['MV_POLE_HEIGHT']?></td>
                                <td><?=@$row['MV_POLE_CONDITION']?></td>
                                <td><?=@$row['BASE_TYPE']?></td>
                                <td><?=@$row['BASE_CONDITION']?></td>
                                <td><?=@$row['POLE_EARTHING']?></td>

            <?php (base_url("gis/main/get_poles_info/"))?>
                <td>  <a href="<?=base_url("gis/main/get_poles_info/{$row['POLE_MATERIAL_ID']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>
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
