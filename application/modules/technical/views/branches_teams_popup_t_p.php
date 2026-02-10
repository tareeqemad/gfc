<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 28/02/16
 * Time: 11:15 ص
 */

$count = 1;

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th  ><input type="checkbox"  class="group-checkable" data-set="#page_tb .checkboxes"/></th>
            <th>#</th>
            <th>هوية الموظف</th>
            <th>اسم الموظف</th>
            <th> الوظيفة</th>
            <th> الجوال</th>
        </tr>
        </thead>
        <tbody>

        <?php foreach($page_rows as $row) :?>
            <tr ondblclick="javascript:show_row_details('<?=$row['CUSTOMER_ID']?>','<?=$row['CUSTOMER_ID_NAME']?>','<?=$row['JAWWAL']?>',false);">
                <td><input type="checkbox" class="checkboxes" data-val=' <?=json_encode($row)?>' value="<?= $row['SER'] ?>"/></td>

                <td><?=$count?></td>
                <td><?=$row['CUSTOMER_ID']?></td>
                <td><?=$row['CUSTOMER_ID_NAME']?></td>
                <td><?=$row['WORKER_JOB_NAME']?></td>
                <td><?=$row['JAWWAL']?></td>
                <?php $count++ ?>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }
</script>
