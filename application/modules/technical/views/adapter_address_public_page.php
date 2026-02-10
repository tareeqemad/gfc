<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * project: Ahmed Barakat
 * Date: 8/28/14
 * Time: 8:46 AM
 */

$count = $offset;

?>

<div class="tbl_container">
    <table class="table" id="projectTbl" data-container="container">
        <thead>
        <tr>

            <th style="width: 20px;" >#</th>

            <th style="width: 120px;" >الفرع</th>
            <th>المكان</th>


        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="11" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>

            </tr>
        <?php endif; ?>
        <?php foreach($rows as $row) :?>
            <tr   ondblclick="javascript:address_select('<?= $row['ID'] ?>','<?= $row['ADDRESS'] ?>','<?= $row['X_GIS'] ?>','<?= $row['Y_GIX'] ?>');">

                <td><?= $count ?></td>

                <td><?= $row['BRANCH_NAME'] ?></td>
                <td><?= $row['ADDRESS'] ?></td>


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