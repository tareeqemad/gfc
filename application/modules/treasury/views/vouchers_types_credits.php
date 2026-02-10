<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 15/10/14
 * Time: 12:14 م
 */
$count = 1;

?>
<?php if(count($rows) > 0): ?>
    <table class="table" id="voucher_details2Tbl" data-container="container">
        <thead>
        <tr>
            <th  style="width: 20px;" >#</th>
            <th>نوع السند</th>
            <th>المبلغ</th>

        </tr>
        </thead>
        <tbody>

        <?php foreach($rows as $row): ?>
            <tr>
                <td><?= $count ?></td>
                <td><?= $row['VOUCHER_TYPE_NAME'] ?></td>
                <td><?= $row['CREDIT'] ?></td>
                <?php $count++ ?>
            </tr>
        <?php endforeach; ?>


        </tbody>




    </table>

    <script>
        if (typeof initFunctions == 'function') {
            initFunctions();
        }
    </script>

<?php endif;?>