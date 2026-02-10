<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/28/14
 * Time: 8:46 AM
 */


$count = $offset;
?>
<div class="tbl_container">
    <table class="table" id="BreakersTbl" data-container="container">
        <thead>
        <tr>

            <th style="width: 50px">#</th>
            <th>اسم القاطع</th>

            <th style="width: 250px">المقر</th>
            <th></th>

        </tr>
        </thead>
        <tbody>
        <?php if ($page > 1): ?>
            <tr>
                <td colspan="11" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>

            </tr>
        <?php endif; ?>
        <?php foreach ($Breakerss as $row) : ?>
            <tr <?= (!$show_only) ? "ondblclick=\"javascript:get_to_link('" . base_url("technical/Breakers/get_id/{$row['BREAKER_ID']}") . "');\" " : "" ?>  >

                <td><?= $count ?></td>
                <td><?= $row['BREAKER_NAME'] ?></td>

                <td><?= $row['BRANCH_ID_NAME'] ?></td>

                <td></td>
                <?php $count++ ?>
            </tr>
        <?php endforeach; ?>
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