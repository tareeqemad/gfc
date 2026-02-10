<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 01/11/14
 * Time: 02:59 م
 */

$TB_NAME= 'exp_rev_update';
$delete_url= base_url("budget/exp_rev_update/delete");
if($adopt==4 and HaveAccess($delete_url))
    $delete= 1;
else
    $delete= 0;

?>

<table class="table" id="<?=$TB_NAME?>_tb" data-container="container">
    <thead>
    <tr>
        <th>م</th>
        <th>الفصل</th>
        <th>التقدير</th>
        <th>تنسيب الموازنة العامة</th>
        <?php
        if($adopt==5 or $adopt==6) echo "<th>اعتماد المدير العام</th>";
        if($adopt==6) echo "<th>اعتماد مجلس الادارة</th>";
        ?>
        <th>ملاحظات</th>
        <th>الحالة</th>
        <th>التفاصيل</th>
        <th>الاجراء</th>
        <?php
        if($delete) echo "<th>ارجاع</th>";
        ?>
    </tr>
    </thead>
    <tbody>
    <?php
    $count=0;
    $s_total=0;
    $s_total_update=0;
    foreach ($get_list as $row){
        $count++;
        $s_total+=$row['TOTAL'];
        $s_total_update+=$row['TOTAL_UPDATE'];

        if($adopt==4 and $row['ADOPT']==3)
            $disabled= '';
        else
            $disabled= 'disabled';

        if($adopt==$row['ADOPT']+1){
            $action=1;
            $action_btn= "<a class='adopt_sec' href='javascript:;' onclick='javascript:{$TB_NAME}_adopt({$row['NO']},$action);'> <i class='glyphicon glyphicon-ok'></i>اعتماد</a>";
        }
        elseif($adopt==$row['ADOPT']){
            $action=0;
            $action_btn= "<a href='javascript:;' onclick='javascript:{$TB_NAME}_adopt({$row['NO']},$action);'> <i class='glyphicon glyphicon-remove'></i>الغاء اعتماد</a>";
        }else{
            $action_btn= "";
        }


        echo "
                <tr id='tr-{$row['NO']}'>
                    <td>$count</td>
                    <td id='td-{$row['SECTION_NO']}'>{$row['SECTION_NAME']}</td>
                    <td>".number_format($row['TOTAL'],2)."</td>
                    <td><input type='number' $disabled name='total_update' id='txt_total_update' value='{$row['TOTAL_UPDATE']}' title='".number_format($row['TOTAL_UPDATE'],2)."' maxlength='15' min='0' max='999999999999999' style='width:120px;' ></td>";
        if($adopt==5 or $adopt==6) echo "<td><input type='text' disabled value='".number_format($row['TOTAL_UPDATE'],2)."' style='width:100px;' ></td>";
        if($adopt==6) echo "<td><input type='text' disabled value='".number_format($row['TOTAL_UPDATE'],2)."' style='width:100px;' ></td>";
                echo "<td><input type='text' $disabled name='note' id='txt_note' value='{$row['NOTE']}' title='{$row['NOTE']}' maxlength='300' ></td>
                    <td>".record_case($row['ADOPT'])."</td>
                    <td><a href='javascript:;' onclick='javascript:{$TB_NAME}_get_details({$row['SECTION_NO']});'> <i class='glyphicon glyphicon-list'></i>الدوائر</a></td>
                    <td>{$action_btn}</td>";
        if($delete and $row['ADOPT']==3) echo "<td><a href='javascript:;' onclick='javascript:{$TB_NAME}_delete({$row['NO']});'> <i class='glyphicon glyphicon-remove'></i></a></td>";
        echo        "</tr>";
    }
    ?>
    </tbody>

    <tfoot>
        <tr>
            <th></th>
            <th></th>
            <th><?=number_format($s_total,2)?></th>
            <th><?=number_format($s_total_update,2)?></th>
            <?=($adopt==5 or $adopt==6)? '<th>'.number_format($s_total_update,2).'</th>':'' ?>
            <?=($adopt==6)? '<th>'.number_format($s_total_update,2).'</th>':'' ?>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <?=($delete)? '<th></th>':'' ?>
        </tr>
    </tfoot>

</table>
