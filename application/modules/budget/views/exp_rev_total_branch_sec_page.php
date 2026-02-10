<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 22/09/16
 * Time: 09:54 ص
 */


$TB_NAME= 'exp_rev_total_sec_branch';



if($department=='' or $department== null)
    $total_update= true;
else
    $total_update= false;


$s_amendment= 0;
$s_final_balance= 0;

?>

<table class="table" id="<?=$TB_NAME?>_tb" data-container="container">
    <thead>
    <tr>
        <th>م</th>
        <th>الفرع</th>
        <th>الاجمالي</th>
        <?php if($total_update) echo "<th>الاجمالي المعتمد</th>"; ?>
        <?php if($total_update) echo "<th>قيمة التعديل </th>"; ?>
        <?php if($total_update) echo "<th>الاجمالي النهائي</th>"; ?>
    </tr>
    </thead>
    <tbody>
    <?php
    $count=0;
    foreach ($chp_total as $row){
        $count++;
        if($total_update){
            $s_amendment+= $row['AMENDMENT'];
            $s_final_balance+= $row['FINAL_BALANCE'];
        }

        echo "
                    <tr>
                        <td>$count</td>
                        <td id='td-{$row['BRANCH']}'>{$row['BRANCH_NAME']}</td>";
        echo    "<td class='total'>".number_format($row['TOTAL'],2)."</td>";
        if($total_update) echo "<td class='total2'>".number_format($row['TOTAL_UPDATE'],2)."</td>";
        if($total_update) echo "<td class=''>".number_format($row['AMENDMENT'],2)."</td>";
        if($total_update) echo "<td class=''>".number_format($row['FINAL_BALANCE'],2)."</td>";
        echo    "</tr>";
    }
    ?>
    </tbody>

    <tfoot>
    <tr>
        <th></th>
        <th>الإجمالي</th>
        <th class='total'></th>
        <?php if($total_update) echo "<th class='total2'></th>"; ?>
        <?php if($total_update) echo "<th class=''>".number_format($s_amendment,2)."</th>"; ?>
        <?php if($total_update) echo "<th class=''>".number_format($s_final_balance,2)."</th>"; ?>

    </tr>
    </tfoot>

</table>

<script type="text/javascript" >
    $(document).ready(function() {
        totals('<?=$TB_NAME?>_tb');
    });
</script>