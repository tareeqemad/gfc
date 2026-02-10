<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/16/14
 * Time: 10:16 AM
 */


$count = 1;
?>
<button class="btn btn-warning dropdown-toggle" onclick="$('#historyTbl').tableExport({type:'excel',escape:'false'});" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>

<table class="table" id="historyTbl" data-container="container">
    <thead>
    <tr>

        <th  >#</th>
        <th  ><input type="checkbox"  class="group-checkable" data-set="#historyTbl .checkboxes"/></th>
        <th  > السنة</th>
        <th  >المقدر </th>
        <th  >إعادة تقدير النصف الأول </th>
        <th  >إعادة تقدير النصف الثاني </th>
        <th  >الفعلي </th>

    </tr>
    </thead>
    <tbody>
    <?php foreach($history as $row) :?>
        <tr ondblclick="javascript:history_get(<?= $row['YYEAR'] ?>,<?= $row['ITEM_NO'] ?>);">

            <td><?= $count ?></td>
            <td><input type="checkbox" class="checkboxes" value="<?= $row['YYEAR'] ?>"/></td>
            <td><?= $row['YYEAR'] ?></td>
            <td><?= n_format($row['ESTIMATED_VALUE']) ?></td>
            <td><?= n_format($row['RE_ESTIMATE_F']) ?></td>
            <td><?= n_format($row['RE_ESTIMATE_L']) ?></td>
            <td><?=  n_format($row['ACTUAL_VALUE'])  ?></td>
            <?php $count++ ?>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>


<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>