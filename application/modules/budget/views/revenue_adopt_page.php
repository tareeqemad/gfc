<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 14/10/14
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
        <th>الحالة</th>
        <th>التفاصيل</th>
        <th>المرفقات</th>
    </tr>
    </thead>

    <tbody>
    <?php
    $count=0;
    $adopt_emp_no= null;
    foreach ($get_total as $row){
        $count++;
        if($row['ADOPT']==$adopt-1 or ($row['ADOPT']== $adopt and $row['ADOPT_EMP_NO']== $user_id ) ){
            $check= "<input name='item_no[]' type='checkbox' class='checkboxes' value='{$row['ITEM_NO']}' />";
        }else{
            $check= '';
        }
        if($row['ADOPT']== $adopt){
            $adopt_emp_no= $row['ADOPT_EMP_NO'];
        }else{
            $adopt_emp_no= 0;
        }
                echo "
                    <tr>
                        <td>$check</td>
                        <td>$count</td>
                        <td>{$row['ITEM_NAME']}</td>
                        <td>{$row['CNT_MONTHS']}</td>
                        <td>{$row['CCOUNT']}&nbsp;&nbsp;( {$row['ITEM_UNIT']} )</td>
                        <td>".number_format($row['PRICE'],2)."</td>
                        <td>".record_case($row['ADOPT'])."</td>
                        <td><a onclick=\"javascript:revenue_details_get({$row['ITEM_NO']}, '".trim(preg_replace('/\s\s+/', ' ', $row['ITEM_NAME']))."', {$row['ADOPT']}, {$adopt_emp_no});\" href='javascript:;'><i class='glyphicon glyphicon-th-list'></i>عرض التفاصيل</a> </td>";
                if($row['ATTACHMENT_CNT'])
                    echo "<td><a onclick=\"javascript:attachment_get({$row['ITEM_NO']});\" href='javascript:;'><i class='glyphicon glyphicon-file'></i>{$row['ATTACHMENT_CNT']}</a> </td>";
                else
                    echo "<td></td>";
                echo "</tr>";
    }
    ?>
    </tbody>


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

</script>
