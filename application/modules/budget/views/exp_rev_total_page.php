<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 27/10/14
 * Time: 09:17 ص
 */

$TB_NAME= 'exp_rev_total';
function adopt_case($n){
    if($n==1)
        return 'معتمد';
    else
        return 'غير معتمد';
}

if($department=='' or $department== null)
    $total_update= true;
else
    $total_update= false;

$s_amendment= 0;
$s_final_balance= 0;

?>
<div style="clear: both" ></div>

<button type="button" class="btn btn-warning" onclick="$('#exp_rev_total_tb').tableExport({type:'excel',escape:'false'});"> تصدير الابواب لاكسل </button>

<table class="table" id="<?=$TB_NAME?>_tb" data-container="container">
    <thead>
    <tr>
        <th>م</th>
        <th>الباب</th>
        <th>الاجمالي</th>
        <?php if($total_update) echo "<th>الاجمالي المعتمد</th>"; ?>
        <?php if($total_update) echo "<th>قيمة التعديل </th>"; ?>
        <?php if($total_update) echo "<th>الاجمالي النهائي</th>"; ?>
        <th>الحالة</th>
        <?php  echo "<th> إجمالي المقرات</th>"; ?>
        <th>التفاصيل</th>
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
                        <td id='tdc-{$row['CHAPTER_NO']}{$row['ADOPT']}'>{$row['CHAPTER_NAME']}</td>
                        <td class='total'>".number_format($row['TOTAL'],2)."</td>";
            if($total_update) echo "<td class='total2'>".number_format($row['TOTAL_UPDATE'],2)."</td>";
            if($total_update) echo "<td class=''>".number_format($row['AMENDMENT'],2)."</td>";
            if($total_update) echo "<td class=''>".number_format($row['FINAL_BALANCE'],2)."</td>";
                echo    "<td>".adopt_case($row['ADOPT'])."</td>
                    <td><a href='javascript:;' onclick='javascript:{$TB_NAME}_get_bran_total_ch({$row['CHAPTER_NO']}, {$row['ADOPT']});'> <i class='glyphicon glyphicon-list'></i>المقرات</a></td>
                        <td><a href='javascript:;' onclick='javascript:{$TB_NAME}_get_sec({$row['CHAPTER_NO']}, {$row['ADOPT']});'> <i class='glyphicon glyphicon-list'></i>الفصول</a></td>
                    </tr>
                ";
        }
    ?>
    </tbody>

    <tfoot>
    <tr>
        <th></th>
        <th>الاجمالي</th>
        <th class='total'></th>
        <?php if($total_update) echo "<th class='total2'></th>"; ?>
        <?php if($total_update) echo "<th class=''>".number_format($s_amendment,2)."</th>"; ?>
        <?php if($total_update) echo "<th class=''>".number_format($s_final_balance,2)."</th>"; ?>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    </tfoot>

</table>

<script type="text/javascript" >
    $(document).ready(function() {
        totals('<?=$TB_NAME?>_tb');
    });
</script>
