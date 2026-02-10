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

            <th style="width: 20px" >#</th>
            <th   > البيان</th>
            <th style="width: 200px"> من تاريخ</th>
            <th style="width: 150px">الي تاريخ</th>

            <th style="width: 80px"></th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="11" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>

            </tr>
        <?php endif; ?>
        <?php foreach($rows as $row) :?>
            <tr  ondblclick="javascript:get_to_link('<?=  base_url("technical/Fast_workorder/get/{$row['SER']}/{$action}")  ?>');"    >

                <td><?= $count ?></td>
                <td><?= $row['TITLE'] ?></td>
                <td><?= $row['FROM_DATE'] ?></td>
                <td><?= $row['TO_DATE'] ?></td>

                <td>

                </td>

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