<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 09/02/19
 * Time: 09:16 ص
 */
$MODULE_NAME='gis';
$TB_NAME="Customers_Controller";
$count = $offset;

?>
<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>

        <th>م</th>
        <th>رقم الاشتراك</th>
        <th>اسم الامشترك</th>
        <th>نوع الاشتراك</th>
        <th>نوع عداد المشترك</th>
        <th>رقم العداد</th>
            <?php (base_url("gis/Customers_Controller/get_Customers_info/"))  ?> <th>عرض/تحرير</th> <?php  ?>
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
            <td><?=@$row['CUSTOMER_SUBSCRIPTION_NO']?></td>
            <td><?=@$row['CUSTOMER_NAME']?></td>
            <td><?=@$row['SUBSCRIPTION_TYPE']?></td>
            <td><?=@$row['SUBSCRIPTION_METER_TYPE']?></td>
            <td><?=@$row['METER_NO']?></td>
            <?php (base_url("gis/Customers_Controller/get_Customers_info/"))?>
            <td>  <a href="<?=base_url("gis/Customers_Controller/get_Customers_info/{$row['ID_PK']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>
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



