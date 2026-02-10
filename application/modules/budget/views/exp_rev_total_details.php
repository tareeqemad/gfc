<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 28/10/14
 * Time: 08:34 ص
 */

$TB_NAME= 'exp_rev_'.$page;

if($page=='sections'){
    $name= 'الفصل';
    $col= 'SECTION';
    $rows= $sec_total;
}elseif($page=='items'){
    $name= 'البند';
    $col= 'ITEM';
    $rows= $itm_total;
    $department= 1;

}else
    die('خطأ في النظام');



if($department=='' or $department== null)
    $total_update= true;
else
    $total_update= false;


if($page=='items' and $branch!= null and HaveAccess( base_url("budget/exp_rev_total/attachment_get") ))
    $show_attc= true;
else
    $show_attc= false;

$s_amendment= 0;
$s_final_balance= 0;


?>

<table class="table" id="<?=$TB_NAME?>_tb" data-container="container">
    <thead>
    <tr>
        <th>م</th>
        <th><?=$name?></th>
        <?php if($page=='items') echo "<th>الكمية</th>"; ?>
        <th>الاجمالي</th>
        <?php if($total_update) echo "<th>الاجمالي المعتمد</th>"; ?>
        <?php if($total_update) echo "<th>قيمة التعديل </th>"; ?>
        <?php if($total_update) echo "<th>الاجمالي النهائي</th>"; ?>
        <?php if($page=='sections') echo "<th>التفاصيل</th> <th>سابقة</th><th>المقرات</th>"; ?>
        <?php if($show_attc) echo "<th>المرفقات</th>"; ?>
    </tr>
    </thead>
    <tbody>
    <?php
    $count=0;
    foreach ($rows as $row){
        $count++;
        if($total_update){
            $s_amendment+= $row['AMENDMENT'];
            $s_final_balance+= $row['FINAL_BALANCE'];
        }

        echo "
                    <tr>
                        <td>$count</td>
                        <td id='tds-{$row[$col.'_NO']}'>{$row['T_SER']}:{$row[$col.'_NAME']}</td>";
                if($page=='items')
                    echo "<td class='total2'>".number_format($row['CNT'])."</td>";
                echo    "<td class='total'>".number_format($row['TOTAL'],2)."</td>";
                if($total_update) echo "<td class='total2'>".number_format($row['TOTAL_UPDATE'],2)."</td>";
                if($total_update) echo "<td class=''>".number_format($row['AMENDMENT'],2)."</td>";
                if($total_update) echo "<td class=''>".number_format($row['FINAL_BALANCE'],2)."</td>";
                if($page=='sections')
                       echo "<td><a href='javascript:;' onclick='javascript: exp_rev_total_get_itm({$row['SECTION_NO']}, {$adopt},{$type});'> <i class='glyphicon glyphicon-list'></i>البنود</a></td>".
                            "<td><a href='javascript:;' onclick='javascript:exp_rev_total_get_history({$row['SECTION_NO']});'> <i class='glyphicon glyphicon-list'></i>تاريخية</a></td>".
        "<td><a href='javascript:;' onclick='javascript:exp_rev_total_get_bran_total_sec({$row['SECTION_NO']}, {$adopt});'> <i class='glyphicon glyphicon-list'></i>المقرات</a></td>";
                if($show_attc and $row['ATTACHMENT_CNT']!=0)
                    echo "<td><a href='javascript:;' onclick='javascript:attachment_get({$row['ITEM_NO']});'> <i class='glyphicon glyphicon-file'></i>{$row['ATTACHMENT_CNT']}</a></td>";
                echo    "</tr>";
    }
    ?>
    </tbody>

    <tfoot>
    <tr>
        <th></th>
        <?php
        if($page=='items')
            echo "<th>الاجمالي</th> <th class='total2'></th>";
        else
            echo "<th>الاجمالي</th>";
        ?>
        <th class='total'></th>
        <?php if($total_update) echo "<th class='total2'></th>"; ?>
        <?php if($total_update) echo "<th class=''>".number_format($s_amendment,2)."</th>"; ?>
        <?php if($total_update) echo "<th class=''>".number_format($s_final_balance,2)."</th>"; ?>
        <?php if($page=='sections') echo "<th></th><th></th><th></th>"; ?>
        <?php if($show_attc) echo "<th></th>"; ?>
    </tr>
    </tfoot>

</table>

<script type="text/javascript" >
    $(document).ready(function() {
        totals('<?=$TB_NAME?>_tb');
    });
</script>
