<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 27/09/16
 * Time: 11:42 ص
 */


$TB_NAME= 'exp_rev_total';
function adopt_case($n){
    if($n==1)
        return 'معتمد';
    else
        return 'غير معتمد';
}
function calc($no1,$no2){
    if ($no1<0) return ($no1+$no2);
        else return ($no1-$no2);
}
if($department=='' or $department== null)
    $total_update= true;
else
    $total_update= false;

$s_amendment_rev= 0;
$s_final_balance_rev= 0;
$s_total_rev=0;
$s_total_update_rev=0;

$s_amendment_exp= 0;
$s_final_balance_exp= 0;
$s_total_exp=0;
$s_total_update_exp=0;

$s_amendment_ban= 0;
$s_final_balance_ban= 0;
$s_total_ban=0;
$s_total_update_ban=0;
?>
<br>
<div class="btn-info"  style="font: bold;text-align: center">الإيرادات </div>
<div style="clear: both" ></div>
<br>
<button type="button" class="btn btn-warning" onclick="$('#exp_rev_total_rev_tb').tableExport({type:'excel',escape:'false'});"> تصدير الابواب لاكسل </button>
<br>
<div style="clear: both" ></div>
<table class="table" id="<?=$TB_NAME?>_rev_tb" data-container="container">
    <thead>
    <tr>
        <th>م</th>
        <th style="width: 250px;">الباب</th>
        <th>الاجمالي</th>
        <?php if($total_update) echo "<th>الاجمالي المعتمد</th>"; ?>
        <?php if($total_update) echo "<th>قيمة التعديل </th>"; ?>
        <?php if($total_update) echo "<th>الاجمالي النهائي</th>"; ?>

    </tr>
    </thead>
    <tbody>
    <?php
    $count=0;
    foreach ($chp_total_rev as $row){
        $count++;
        if($total_update){
            $s_amendment_rev+= $row['AMENDMENT'];
            $s_final_balance_rev+= $row['FINAL_BALANCE'];
            $s_total_rev+= $row['TOTAL'];
            $s_total_update_rev+= $row['TOTAL_UPDATE'];
        }

        echo "
                    <tr>
                        <td>$count</td>
                        <td id='td-{$row['CHAPTER_NO']}{$row['ADOPT']}'>{$row['CHAPTER_NAME']}</td>
                        <td class='total'>".number_format($row['TOTAL'],2)."</td>";
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
        <th>الاجمالي</th>
        <th class='total'><?=number_format($s_total_rev,2)?></th>
        <?php if($total_update) echo "<th class='total2'>".number_format($s_total_update_rev,2)."</th>"; ?>
        <?php if($total_update) echo "<th class=''>".number_format($s_amendment_rev,2)."</th>"; ?>
        <?php if($total_update) echo "<th class=''>".number_format($s_final_balance_rev,2)."</th>"; ?>

    </tr>
    </tfoot>

</table>
<br>
<div style="clear: both" ></div>
<div class="btn-info"  style="font: bold;text-align: center">النفقات </div>
<div style="clear: both" ></div>
<br>
<button type="button" class="btn btn-warning" onclick="$('#exp_rev_total_exp_tb').tableExport({type:'excel',escape:'false'});"> تصدير الابواب لاكسل </button>
<br>
<table class="table" id="<?=$TB_NAME?>_exp_tb" data-container="container">
    <thead>
    <tr>
        <th>م</th>
        <th style="width: 250px;">الباب</th>
        <th>الاجمالي</th>
        <?php if($total_update) echo "<th>الاجمالي المعتمد</th>"; ?>
        <?php if($total_update) echo "<th>قيمة التعديل </th>"; ?>
        <?php if($total_update) echo "<th>الاجمالي النهائي</th>"; ?>

    </tr>
    </thead>
    <tbody>
    <?php
    $count=0;
    foreach ($chp_total_exp as $row){
        $count++;
        if($total_update){
            $s_amendment_exp+= $row['AMENDMENT'];
            $s_final_balance_exp+= $row['FINAL_BALANCE'];
            $s_total_exp+= $row['TOTAL'];
            $s_total_update_exp+= $row['TOTAL_UPDATE'];
        }

        echo "
                    <tr>
                        <td>$count</td>
                        <td id='td-{$row['CHAPTER_NO']}{$row['ADOPT']}'>{$row['CHAPTER_NAME']}</td>
                        <td class='total'>".number_format($row['TOTAL'],2)."</td>";
        if($total_update) echo "<td class='total2'>".number_format($row['TOTAL_UPDATE'],2)."</td>";
        if($total_update) echo "<td class=''>".number_format($row['AMENDMENT'],2)."</td>";
        if($total_update) echo "<td class=''>".number_format($row['FINAL_BALANCE'],2)."</td>";
        echo    " </tr> ";
    }
    ?>
    </tbody>

    <tfoot>
    <tr>
        <th></th>
        <th>الاجمالي</th>
        <th class='total'><?=number_format($s_total_exp,2)?></th>
        <?php if($total_update) echo "<th class='total2'>".number_format($s_total_update_exp,2)."</th>"; ?>
        <?php if($total_update) echo "<th class=''>".number_format($s_amendment_exp,2)."</th>"; ?>
        <?php if($total_update) echo "<th class=''>".number_format($s_final_balance_exp,2)."</th>"; ?>

    </tr>
    </tfoot>

</table>


<br>
<div class="btn-info"  style="font: bold;text-align: center">الفائض و العجز </div>
<div style="clear: both" ></div>
<br>
<button type="button" class="btn btn-warning" onclick="$('#exp_rev_total_rev_exp_tb').tableExport({type:'excel',escape:'false'});"> تصدير الابواب لاكسل </button>
<br>
<div style="clear: both" ></div>
<table class="table" id="<?=$TB_NAME?>_rev_exp_tb" data-container="container">
    <thead>
    <tr>
        <th>م</th>
        <th style="width: 250px;" >النوع</th>
        <th>الاجمالي</th>
        <?php if($total_update) echo "<th>الاجمالي المعتمد</th>"; ?>
        <?php if($total_update) echo "<th>قيمة التعديل </th>"; ?>
        <?php if($total_update) echo "<th>الاجمالي النهائي</th>"; ?>

    </tr>
    </thead>
    <tbody>


                    <tr>
                        <td>1</td>
                        <td>إجمالي الإيرادات</td>
                        <td class='total'><?=number_format($s_total_rev,2)?></td>
                        <?php   if($total_update) echo "<td class='total2'>".number_format($s_total_update_rev,2)."</td>";
        if($total_update) echo "<td class=''>".number_format($s_amendment_rev,2)."</td>";
        if($total_update) echo "<td class=''>".number_format($s_final_balance_rev,2)."</td>"; ?>

                    </tr>

                    <tr>
                        <td>2</td>
                        <td>إجمالي النفقات</td>
                        <td class='total'><?=number_format($s_total_exp,2)?></td>
                        <?php   if($total_update) echo "<td class='total2'>".number_format($s_total_update_exp,2)."</td>";
                        if($total_update) echo "<td class=''>".number_format($s_amendment_exp,2)."</td>";
                        if($total_update) echo "<td class=''>".number_format($s_final_balance_exp,2)."</td>"; ?>

                    </tr>


    </tbody>

    <tfoot>
    <tr>
        <th></th>
        <th>الاجمالي</th>
        <th class='total'><?=number_format(($s_total_rev-$s_total_exp),2)?></th>
        <?php if($total_update) echo "<th class='total2'>".number_format(($s_total_update_rev-$s_total_update_exp),2)."</th>"; ?>
        <?php if($total_update) echo "<th class=''>".number_format(($s_amendment_rev-$s_amendment_exp),2)."</th>"; ?>
        <?php if($total_update) echo "<th class=''>".number_format(($s_final_balance_rev-$s_final_balance_exp),2)."</th>"; ?>

    </tr>
    </tfoot>

</table>

<br>
<div style="clear: both" ></div>
<div class="btn-info"  style="font: bold;text-align: center">تسهيلات بنكية </div>
<div style="clear: both" ></div>
<br>
<button type="button" class="btn btn-warning" onclick="$('#exp_rev_total_ban_tb').tableExport({type:'excel',escape:'false'});"> تصدير الابواب لاكسل </button>
<br>
<table class="table" id="<?=$TB_NAME?>_ban_tb" data-container="container">
    <thead>
    <tr>
        <th>م</th>
        <th style="width: 250px;">الباب</th>
        <th>الاجمالي</th>
        <?php if($total_update) echo "<th>الاجمالي المعتمد</th>"; ?>
        <?php if($total_update) echo "<th>قيمة التعديل </th>"; ?>
        <?php if($total_update) echo "<th>الاجمالي النهائي</th>"; ?>

    </tr>
    </thead>
    <tbody>
    <?php
    $count=0;
    foreach ($chp_total_ban as $row){
        $count++;
        if($total_update){
            $s_amendment_ban+= $row['AMENDMENT'];
            $s_final_balance_ban+= $row['FINAL_BALANCE'];
            $s_total_ban+= $row['TOTAL'];
            $s_total_update_ban+= $row['TOTAL_UPDATE'];
        }

        echo "
                    <tr>
                        <td>$count</td>
                        <td id='td-{$row['CHAPTER_NO']}{$row['ADOPT']}'>{$row['CHAPTER_NAME']}</td>
                        <td class='total'>".number_format($row['TOTAL'],2)."</td>";
        if($total_update) echo "<td class='total2'>".number_format($row['TOTAL_UPDATE'],2)."</td>";
        if($total_update) echo "<td class=''>".number_format($row['AMENDMENT'],2)."</td>";
        if($total_update) echo "<td class=''>".number_format($row['FINAL_BALANCE'],2)."</td>";
        echo    " </tr> ";
    }
    ?>
    </tbody>

    <tfoot>
    <tr>
        <th></th>
        <th>الاجمالي</th>
        <th class='total'><?=number_format($s_total_ban,2)?></th>
        <?php if($total_update) echo "<th class='total2'>".number_format($s_total_update_ban,2)."</th>"; ?>
        <?php if($total_update) echo "<th class=''>".number_format($s_amendment_ban,2)."</th>"; ?>
        <?php if($total_update) echo "<th class=''>".number_format($s_final_balance_ban,2)."</th>"; ?>

    </tr>
    </tfoot>

</table>

<br>
<div class="btn-info"  style="font: bold;text-align: center"> إجماليات الموازنة </div>
<div style="clear: both" ></div>
<br>
<button type="button" class="btn btn-warning" onclick="$('#exp_rev_total_rev_exp_ban_tb').tableExport({type:'excel',escape:'false'});"> تصدير الابواب لاكسل </button>
<br>
<div style="clear: both" ></div>
<table class="table" id="<?=$TB_NAME?>_rev_exp_ban_tb" data-container="container">
    <thead>
    <tr>
        <th>م</th>
        <th style="width: 250px;" >النوع</th>
        <th>الاجمالي</th>
        <?php if($total_update) echo "<th>الاجمالي المعتمد</th>"; ?>
        <?php if($total_update) echo "<th>قيمة التعديل </th>"; ?>
        <?php if($total_update) echo "<th>الاجمالي النهائي</th>"; ?>

    </tr>
    </thead>
    <tbody>

<?php
$s_total_rev_exp_s=$s_total_rev-$s_total_exp;
$s_total_update_rev_exp_s=$s_total_update_rev-$s_total_update_exp;
$s_amendment_rev_exp_s=$s_amendment_rev-$s_amendment_exp;
$s_final_balance_rev_exp_s=$s_final_balance_rev-$s_final_balance_exp;
?>

    <tr>
        <td>1</td>
        <td>إجمالي الفائض و العجز</td>
        <td class='total'><?=number_format($s_total_rev_exp_s,2)?></td>
        <?php   if($total_update) echo "<td class='total2'>".number_format($s_total_update_rev_exp_s,2)."</td>";
        if($total_update) echo "<td class=''>".number_format($s_amendment_rev_exp_s,2)."</td>";
        if($total_update) echo "<td class=''>".number_format($s_final_balance_rev_exp_s,2)."</td>"; ?>

    </tr>

    <tr>

        <td>2</td>
        <td>إجمالي التسهيلات البنكية</td>
        <td class='total'><?=number_format($s_total_ban,2)?></td>
        <?php   if($total_update) echo "<td class='total2'>".number_format($s_total_update_ban,2)."</td>";
        if($total_update) echo "<td class=''>".number_format($s_amendment_ban,2)."</td>";
        if($total_update) echo "<td class=''>".number_format($s_final_balance_ban,2)."</td>"; ?>

    </tr>


    </tbody>

    <tfoot>
    <tr>
        <th></th>
        <th>الاجمالي</th>
        <th class='total'><?=number_format(calc($s_total_rev_exp_s,$s_total_ban),2)?></th>
        <?php if($total_update) echo "<th class='total2'>".number_format(calc($s_total_update_rev_exp_s,$s_total_update_ban),2)."</th>"; ?>
        <?php if($total_update) echo "<th class=''>".number_format(calc($s_amendment_rev_exp_s,$s_amendment_ban),2)."</th>"; ?>
        <?php if($total_update) echo "<th class=''>".number_format(calc($s_final_balance_rev_exp_s,$s_final_balance_ban),2)."</th>"; ?>

    </tr>
    </tfoot>

</table>
