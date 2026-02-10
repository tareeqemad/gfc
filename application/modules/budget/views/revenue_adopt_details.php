<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 14/10/14
 * Time: 10:15 ص
 */

?>

<table class="table" id="revenue_tb" data-container="container">
    <thead>
    <tr>
        <th>م</th>
        <th>الشهر</th>
        <th>الكمية</th>
        <th>السعر</th>
        <th>الاجمالي</th>
        <th>ملاحظات</th>
        <th>المدخل</th>
    </tr>
    </thead>

    <tbody>
    <?php
    $count=0;
    foreach ($get_list as $row){
        $count++;
        echo "
                <tr>
                    <td>$count</td>
                    <td>".months($row['MMONTH'])."</td>
                    <td>{$row['CCOUNT']}</td>
                    <td>{$row['PRICE']}</td>
                    <td>".number_format($row['CCOUNT']*$row['PRICE'],2)."</td>
                    <td>{$row['NOTES']}</td>
                    <td>".get_user_name($row['ENTRY_USER'])."</td>
                </tr>
            ";
    }
    ?>
    </tbody>

</table>
