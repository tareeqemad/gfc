<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 23/01/2020
 * Time: 10:16 ص
 */

?>

<table class="table" id="revenue_adopt_tb" data-container="container">
    <thead>
    <tr>
        <th title="تحديد الكل"> <input type="checkbox" id="select-all" /> </th>
        <th>م</th>
        <th>البند</th>
        <th>عدد الاشهر</th>
        <th>الكمية الاجمالية</th>
        <th>السعر الاجمالي</th>
        <th>الحالة <input type="checkbox" id="cancel-all" /></th>
        <th>التفاصيل</th>
        <th>المرفقات</th>
    </tr>
    </thead>

    <tbody>
    <?php
    $count=0;
    $adopt_emp_no= null;
    $sum = 0;
    foreach ($get_total as $row){
        $sum+= $row['PRICE'];
        $count++;
        if ($row['ADOPT']== 1 ) {
            $check= "<input name='adopt_no[]' type='checkbox' class='checkboxes' value='{$row['INO']}' />";
        }else{
            $check= '';
        }
        if ($row['ADOPT']== 3 ) {
            $cancel_check= "<input name='cancel_adopt[]' type='checkbox' class='checkboxes_cancel' value='{$row['INO']}' />";
        }else{
            $cancel_check= '';
        }
        echo "
                    <tr>
                        <td>$check</td>
                        <td>$count</td>
                        <td>{$row['ITEM_NAME']}</td>
                        <td>{$row['CNT_MONTHS']}</td>
                        <td>{$row['CCOUNT']}&nbsp;&nbsp;( {$row['ITEM_UNIT']} )</td>
                        <td>".number_format($row['PRICE'],2)."</td>
                        <td>".record_case($row['ADOPT']).$cancel_check."</td>
                        <td><a onclick=\"javascript:revenue_details_get({$row['ITEM_NO']}, '".trim(preg_replace('/\s\s+/', ' ', $row['ITEM_NAME']))."', {$row['ADOPT']}, {$adopt_emp_no});\" href='javascript:;'><i class='glyphicon glyphicon-th-list'></i>عرض التفاصيل</a> </td>";
        if($row['ATTACHMENT_CNT'])
            echo "<td><a onclick=\"javascript:attachment_get({$row['ITEM_NO']});\" href='javascript:;'><i class='glyphicon glyphicon-file'></i>{$row['ATTACHMENT_CNT']}</a> </td>";
        else
            echo "<td></td>";
        echo "</tr>";
    }
    ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3"></th>
            <th></th>
            <th> الاجمالي</th>
            <th><?php echo  number_format($sum,2); ?></th>
            <th></th>
            <th colspan="2"></th>
        </tr>
    </tfoot>


</table>

<script type="text/javascript">
    if (typeof initFunctions == 'function') {
        initFunctions();
    }

    $('#select-all').click(function(event) {
        var chk= false;
        if(this.checked) {
            chk= true;
        }
        $('.checkboxes:checkbox').each(function() {
            this.checked = chk;
        });
    });
    $('#cancel-all').click(function(event) {
        var chk= false;
        if(this.checked) {
            chk= true;
        }
        $('.checkboxes_cancel:checkbox').each(function() {
            this.checked = chk;
        });
    });


</script>
