<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 08/10/18
 * Time: 12:48 م
 */

$MODULE_NAME= 'purchases';
$TB_NAME= 'vouchers';
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
?>

<table class="table" id="vouchers_tb" data-container="container">
    <thead>
    <tr>
        <th  >#</th>
        <th>م</th>
        <th>رقم الإيصال</th>
        <th>اسم المورد بالإيصال</th>
        <th>رقم المورد </th>
        <th> اسم المورد</th>
        <th> رقم طلب الشراء</th>
        <th>الحالة</th>


    </tr>
    </thead>

    <tbody>
    <?php
    $count=0;
    foreach ($get_all as $row){
        if($row['SER']>0 && $row['ADOPT']==2 ) $X=""; else  $X="disabled";
        $count++;
        echo "<tr data-id='{$row['ENTRY_SER']}' data-var='{$row['VOUCHER_ID']}'";
        if(HaveAccess($get_url) and HaveAccess($edit_url))
            echo "ondblclick='javascript:vouchers_get({$row['ENTRY_SER']},{$row['VOUCHER_ID']});'";
        echo ">
                <td><input type='checkbox'  class='checkboxes' ".$X." value='{$row['SER']}' /></td>
                <td>$count</td>
                <td>{$row['ID']}</td>
                <td>{$row['CUST_NAME']}</td>
                <td>{$row['CUSTOMER_ID']}</td>
                <td>{$row['CUSTOMER_NAME']}</td>
                <td>{$row['PURCHASE_ORDER_NUM']}</td>
                <td>{$row['ADOPT_NAME']}</td>
                     ";

        echo "</tr>";
    }
    ?>
    </tbody>

</table>

<script type="text/javascript">
    /*  $(document).ready(function() {
     $('#advertising_tb').dataTable({
     "lengthMenu": [ [10,20,30,40,50,100, -1], [10,20,30,40,50,100, "الكل"] ],
     "sPaginationType": "full_numbers"
     });
     });

     if (typeof initFunctions == 'function') {
     initFunctions();
     }*/
</script>
