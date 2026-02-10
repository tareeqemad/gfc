<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/16/14
 * Time: 10:16 AM
 */

$count = 1;

function row_class($flag){
    if($flag==1){
        return 'case_6';
    }else{
        return '';
    }
}

?>

<table class="table" id="staffTbl" data-container="container">
    <thead>
    <tr>
        <th  ><input type="checkbox"  class="group-checkable" data-set="#staffTbl .checkboxes"/></th>
        <th  >#</th>
        <th >الرقم الوظيفي</th>
        <th  >الاسم </th>
        <th  >المسمى الوظيفي </th>
        <th  >طبيعة العمل </th>

    </tr>
    </thead>
    <tbody>
    <?php foreach($employees as $emp) :?>
        <tr class="<?=row_class($emp['FLAG'])?>">
            <td><input type="checkbox" class="checkboxes" value="<?= $emp['NO'] ?>"/></td>
            <td><?= $count ?></td>
            <td><?= $emp['NO'] ?></td>
            <td><?= $emp['NAME'] ?></td>
            <td><?= $emp['W_NO_ADMIN_NAME'] ?></td>
            <td><?= $emp['WORK_DESC'] ?></td>
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