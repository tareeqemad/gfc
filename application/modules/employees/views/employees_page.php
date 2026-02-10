<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * employee: Ahmed Barakat
 * Date: 8/28/14
 * Time: 8:46 AM
 */

$count = 1;
?>

<table class="table" id="employeeTbl" data-container="container">
            <thead>
            <tr>
                <th  ><input type="checkbox"  class="group-checkable" data-set="#employeeTbl .checkboxes"/></th>
                <th  >#</th>
                <th >الرقم الوظيفي</th>

                <th >الاسم </th>

            </tr>
            </thead>
            <tbody>
            <?php foreach($employees as $employee) :?>
    <tr ondblclick="javascript:employee_get('<?= $employee['NO'] ?>');">
        <td><input type="checkbox" class="checkboxes" value="<?= $employee['NO'] ?>"/></td>
        <td><?= $count ?></td>
        <td><?= $employee['NO'] ?></td>
        <td><?= $employee['NAME'] ?></td>

        <?php $count++ ?>
    </tr>
<?php endforeach;?>
     </tbody>
        </table>
        <?php echo $this->pagination->create_links(); ?>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>