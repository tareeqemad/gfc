<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 13/09/15
 * Time: 12:13 م
 */

?>

<table class="table" id="exp_rev_history_tb" data-container="container">
    <thead>
    <tr>
        <th>م</th>
        <th>العام</th>
        <th>المقدر</th>
        <th>الفعلي</th>
    </tr>
    </thead>
    <tbody>

    <?php
    $count=0;
    foreach ($history_total as $row){
        $count++;
        echo "
            <tr>
                <td>$count</td>
                <td>".$row['YYEAR']."</td>
                <td >".number_format($row['ESTIMATED_VALUE_SUM'],2)."</td>
                <td >".number_format($row['ACTUAL_VALUE_SUM'],2)."</td>
            </tr>";
    }
    ?>
    </tbody>
</table>
