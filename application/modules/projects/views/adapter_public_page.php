<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/28/14
 * Time: 8:46 AM
 */

$count = $offset;
?>
<div class="tbl_container">
<table class="table" id="adapterTbl" data-container="container">
            <thead>
            <tr>
                <th  ><input type="checkbox"  class="group-checkable" data-set="#adapterTbl .checkboxes"/></th>
                <th  >#</th>
                <th >اسم المحول </th>
                <th  >قدرة المحول KVA</th>
                <th >  اسم علمى للمحول</th>
                <th style="width: 150px"> رمز المحول</th>
                <th style="width: 250px">المقر</th>

            </tr>
            </thead>
            <tbody>
            <?php if($page > 1): ?>
                <tr>
                    <td colspan="11" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>

                </tr>
            <?php endif; ?>
            <?php foreach($adapters as $row) :?>
    <tr ondblclick="javascript:adapter_select('<?= $row['ADAPTER_SERIAL'] ?>','<?= $row['ADAPTER_NAME'] ?>');"  >
        <td><input type="checkbox" class="checkboxes"  data-val=' <?=json_encode($row)?>' value="<?= $row['ADAPTER_SERIAL'] ?>"/></td>
        <td><?= $count ?></td>
        <td><?= $row['ADAPTER_NAME'] ?></td>
        <td><?= $row['POWER_ADAPTER'] ?></td>
        <td><?= $row['POWER_ADAPTER_SC'] ?></td>
        <td><?= $row['GIS_ID'] ?></td>
        <td><?= $row['BRANCH_NAME'] ?></td>

        <?php $count++ ?>
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



</script>