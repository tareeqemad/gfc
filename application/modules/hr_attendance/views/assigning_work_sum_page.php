<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 24/03/19
 * Time: 11:20 ص
 */

$count = 1;

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم الموظف</th>
            <th>اسم الموظف</th>
            <th>اجمالي الساعات</th>
            <th>المقر</th>
        </tr>
        </thead>
        <tbody>

        <?php foreach($page_rows as $row) :?>
        <tr ondblclick="javascript:show_row_details('');" >
            <td><?=$count?></td>
            <td><?=$row['EMP_NO']?></td>
            <td><?=$row['EMP_NO_NAME']?></td>
            <td><?=$row['S_CALC_DURATION']?></td>
            <td><?=$row['BRANCH_NAME']?></td>
            <?php
            $count++;
            ?>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>
