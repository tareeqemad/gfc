<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 2019-04-23
 * Time: 9:22 AM
 */

$count = 1;
?>
<div class="modal-header" style="background-color: #538CC2; color: #fff;">
    <h4 class="modal-title">الطلبة الجامعيين</h4>
</div>
<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم السند</th>
            <th>رقم الموظف</th>
            <th>اسم الموظف</th>
            <th>رقم هوية المعال</th>
            <th>اسم المعال</th>
            <th>المؤهل العلمي للمعال</th>
            <th>تاريخ الإلتحاق بالجامعة</th>
            <th>تاريخ التخرج من الجامعة</th>
            <th>تاريخ بداية الإحتساب</th>
            <th>تاريخ نهاية الإحتساب</th>
            <th>المدخل</th>
            <th>حالة الإعتماد</th>
            <th>اسم المعتمد</th>
            <th>تاريخ الاعتماد</th>
            <th>عرض</th>

        </tr>
        </thead>
        <tbody>
        <?php foreach($page_rows as $row) :?>
            <tr>
                <td><?=$count?></td>
                <td><?=$row['SER_D']?></td>
                <td><?=$row['EMP_NO']?></td>
                <td><?=$row['EMP_NO_NAME']?></td>
                <td><?=$row['IDNO_RELATIVE']?></td>
                <td><?=$row['IDNO_RELATIVE_NAME']?></td>
                <td><?=$row['QUALIFICATION_TYPE_NAME']?></td>
                <td><?=$row['JOIN_DATE']?></td>
                <td><?=$row['GRADUATION_DATE']?></td>
                <td><?=$row['FROM_DATE']?></td>
                <td><?=$row['TO_DATE']?></td>
                <td title="<?=$row['ENTRY_USER_NAME']?>"><?=get_short_user_name($row['ENTRY_USER_NAME'])?></td>
                <td><?=$row['ADOPT_NAME']?></td>
                <td title="<?=$row['ADOPT_USER_NAME']?>"><?=get_short_user_name($row['ADOPT_USER_NAME'])?></td>
                <td><?=$row['ADOPT_DATE']?></td>
                <td><a style="cursor: pointer" onclick="add_new_std_2(<?=$row['SER_D']?>)" ><i class="glyphicon glyphicon-new-window"></i></a></td>
            </tr>
            <?php
            $count++;
        endforeach;
        ?>
        </tbody>
    </table>
</div>