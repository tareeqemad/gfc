<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 09/02/19
 * Time: 10:53 ص
 */
$MODULE_NAME='gis';
$TB_NAME="Customer_cables_Controller";
$count = $offset;

?>
<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>

            <th>م</th>
            <th>نوع ومساحة مقطع كابل المشترك</th>
            <th>طول كابل مشترك الفاز ونول  </th>
            <th>رقم العمود </th>
            <th>اسم المحول بالعربية</th>

            <?php (base_url("gis/Customer_cables_Controller/get_Customer_cables_info/"))  ?> <th>عرض/تحرير</th> <?php  ?>
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
                <td><?=@$row['CABLE_TYPE']?></td>
                <td><?=@$row['CABLE_LENGTH']?></td>
                <td><?=@$row['POLE_CODE']?></td>
                <td><?=@$row['TRANSFORMER_NAME_AR']?></td>

                <?php (base_url("gis/Customer_cables_Controller/get_Customer_cables_info/"))?>
                <td>  <a href="<?=base_url("gis/Customer_cables_Controller/get_Customer_cables_info/{$row['ID_PK']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>
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



