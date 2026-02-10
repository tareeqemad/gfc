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
<?php if (count($vouchers_details) ): ?>
<div class="tbl_container">
    <table class="table" id="voucher_detailsTbl" data-container="container">
        <thead>
        <tr>

            <th  >#</th>


            <th style="width: 400px"> حساب الإيراد -دائن </th>

            <th >المبلغ</th>
            <th></th>

        </tr>
        </thead>
        <tbody>

        <?php foreach($vouchers_details as $voucher) :?>
            <tr>

                <td><?= $count ?></td>

                <td>

                    <input type="text" value="<?= $voucher['CREDIT_ACCOUNT_ID_NAME'] ?>" readonly  class="form-control "/>
                 </td>
                <td>
                    <input type="text" value="<?= $voucher['CREDIT'] ?>" readonly    class="form-control "/>

                </td>

                <td> </td>
                <?php $count++ ?>
            </tr>
        <?php endforeach;?>
        </tbody>


    </table>
</div>


<?php endif ; ?>

<?php if (count($vouchers_details2) ): ?>
    <div class="tbl_container">
        <table class="table" id="voucher_details2Tbl" data-container="container">
            <thead>
            <tr>

                <th  >#</th>


                <th style="width: 400px"> حساب الإيراد -دائن </th>

                <th >المبلغ</th>
                <th></th>

            </tr>
            </thead>
            <tbody>

            <?php foreach($vouchers_details2 as $voucher2) :?>
                <tr>

                    <td><?= $count ?></td>

                    <td>

                        <input type="text" value="<?= $voucher2['ACCOUNT_ID_NAME'] ?>" readonly  class="form-control "/>
                    </td>
                    <td>
                        <input type="text" value="<?= $voucher2['CREDIT'] ?>" readonly    class="form-control "/>

                    </td>

                    <td> </td>
                    <?php $count++ ?>
                </tr>
            <?php endforeach;?>
            </tbody>


        </table>
    </div>


<?php endif ; ?>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>