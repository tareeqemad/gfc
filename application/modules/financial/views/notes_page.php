<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 16/12/14
 * Time: 03:33 م
 */

$count = 0;
?>

<?php if(count($rows) > 0 ) : ?>

<div class="tbl_container">
    <table class="table" id="chains_detailsTbl" data-container="container">
        <thead>
        <tr>
            <th style="width: 20px;"  > #</th>
            <th style="width: 200px;" >المدخل</th>
            <th style="width: 80px;" >التاريخ</th>
            <th>الملاحظة  </th>

        </tr>
        </thead>
        <tbody>



        <?php foreach($rows as $row) :?>
            <?php $count++; ?>
            <tr>
                <td>
                    <?= $count ?>
                </td>
                <td>
                    <?= $row['ENRTY_USER_NAME'] ?>
                </td>
                <td>
                    <?= $row['ENTRY_DATE'] ?>
                </td>

                <td>  <?= $row['NOTES'] ?> </td>
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

<?php endif; ?>