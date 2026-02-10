<?php

$count=1;
?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم السند</th>
            <th>رقم الموظف</th>
            <th>اسم الموظف</th>
            <th>تاريخ التكليف</th>
            <th>اليوم</th>
            <th>نوع المهمة</th>
            <th>المحافظة</th>
            <th>عدد الاتجاهات</th>
            <th>الوجهة</th>
            <th>عدد الركاب</th>
            <th>وقت المغادرة المتوقع</th>
            <th>وقت الوصول المتوقع</th>
            <th>الحالة</th>
            <th>اسم ملغي الطلب</th>
            <th>المعتمد</th>
            <th>انصراف المدير</th>
            <th>المقر</th>
            <th>القسم/الدائرة</th>
            <th>تاريخ الادخال</th>
            <th>المدخل</th>
            <th>حالة الطلب</th>
            <th>وقت الالغاء</th>
            <th>سبب الغاء المهمة</th>
        </tr>
        </thead>
        <tbody>


        <?php foreach($page_rows as $row) :?>

            <tr ondblclick="javascript:show_row_details('<?=$row['SER']?>');" >

                <td><?=$count?></td>
                <td><?=$row['SER']?></td>
                <td><?=$row['EMP_NO']?></td>
                <td title="<?='الأعمال/ '.$row['WORK_REQUIRED']?>"><?=$row['EMP_NO_NAME']?></td>
                <td><?=$row['ASS_START_DATE']?></td>
                <td><?=$row['DAY_AR']?></td>
                <td><?=$row['TASK_TYPE_NAME']?></td>
                <td><?=$row['GOVERNORATE_ID_NAME']?></td>
                <td><?=$row['DIRECTIONS_NO']?></td>
                <td><?=$row['DESTINATION_TYPE_NAME']?></td>
                <td><?=$row['PASSENGERS_NO']?></td>
                <td><?=$row['EXPECTED_LEAVE_TIME']?></td>
                <td><?=$row['EXPECTED_ARRIVAL_TIME']?></td>
                <td><?=$row['ADOPT_NAME']?></td>
                <td><?=$row['CANCEL_USER_NAME']?></td>
                <td title="<?=$row['ADOPT_USER_20_NAME']?>"><?=get_short_user_name($row['ADOPT_USER_20_NAME'])?></td>
                <td><?=$row['ADOPT_USER_20_LEAVE_TIME']?></td>
                <td><?=$row['BRANCH_ID_NAME']?></td>
                <td><?=$row['EMP_HEAD_DEPARTMENT_NAME']?></td>
                <td title="<?=$row['ENTRY_DATE_TIME']?>"><?=$row['ENTRY_DATE']?></td>
                <td title="<?=$row['ENTRY_USER_NAME']?>"><?=get_short_user_name($row['ENTRY_USER_NAME'])?></td>
                <td><?=$row['CAR_ADOPT_NAME']?></td>
                <td><?=$row['CANCEL_DATE_TIME']?></td>
                <td><?=$row['CANCEL_REASON_NAME']?></td>
                <?php
                $count++;
                ?>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links(); ?>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }
    if (typeof show_page == 'undefined'){
        document.getElementById("page_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }
</script>
