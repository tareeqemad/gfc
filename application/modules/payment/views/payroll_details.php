<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 13/01/15
 * Time: 09:54 ص
 */
$count = 0;
$d_sum = 0;
$c_sum = 0;

?>

<div class="tbl_container">
    <table class="table" id="chains_detailsTbl" data-container="container">
        <thead>
        <tr>
            <th >رقم الحساب</th>
            <th  > الحساب </th>
            <th style="width: 150px"  >المدين </th>
            <th  style="width: 150px" > الدائن </th>
            <th ></th>
        </tr>
        </thead>

        <tbody>


        <?php if(isset($rows)): ?>
            <?php foreach($rows as $row) :?>
                <?php $count++;
                $d_sum +=$row['BEBIT_VALUE'];
                $c_sum +=$row['CREDIT_VALUE'];
                ?>
                <tr>
                    <td>
                        <input type="hidden" readonly name="root_account_id[]" value="<?= $row['ROOT_ACCOUNT'] ?>" id="h_account_<?= $count ?>"  class="form-control">
                        <input type="text" readonly name="account_id[]" value="<?= $row['ACCOUNT_ID'] ?>" id="h_account_<?= $count ?>"  class="form-control">
                    </td>
                    <td>
                        <input name="account_name[]" readonly  class="form-control"   id="payment_value_<?= $count ?>" value="<?= $row['ACCOUNT_ID_NAME'] ?>">
                    </td>
                    <td>
                        <input name="debit[]" readonly class="form-control"  value="<?= $row['BEBIT_VALUE'] ?>" type="text"  id="debit_<?= $count ?>"  >
                    </td>
                    <td>
                        <input name="credit[]" readonly class="form-control"  value="<?= $row['CREDIT_VALUE'] ?>" type="text"  id="credit_<?= $count ?>"  >
                    </td>
                    <td> </td>
                </tr>

            <?php endforeach;?>
        <?php endif; ?>


        <?php if(isset($details)): ?>
            <?php foreach($details as $row) :?>
                <?php $count++;
                $d_sum +=$row['V_SUM_VALUE_1'];
                $c_sum +=$row['V_SUM_VALUE_0'];
                ?>
                <tr>
                    <td>
                        <input type="hidden" readonly name="root_account_id[]" value="<?= $row['ROOT_ACCOUNT'] ?>" id="h_account_<?= $count ?>"  class="form-control">
                        <input type="text" readonly name="account_id[]" value="<?= $row['ACCOUNT_ID_B'] ?>" id="h_account_<?= $count ?>"  class="form-control">
                    </td>
                    <td>
                        <input name="account_name[]" readonly  class="form-control"   id="payment_value_<?= $count ?>" value="<?= $row['ACCOUNT_NAME'] ?>">
                    </td>
                    <td>
                        <input name="debit[]" readonly class="form-control"  value="<?= $row['V_SUM_VALUE_1'] ?>" type="text"  id="debit_<?= $count ?>"  >
                    </td>
                    <td>
                        <input name="credit[]" readonly class="form-control"  value="<?= $row['V_SUM_VALUE_0'] ?>" type="text"  id="credit_<?= $count ?>"  >
                    </td>
                    <td> </td>
                </tr>

            <?php endforeach;?>
        <?php endif; ?>

        </tbody>
        <tfoot>
        <tr>
            <th rowspan="1" class="align-right" colspan="2">

            </th>
            <th  ><?= $d_sum?> </th>
            <th  ><?= $c_sum?> </th>
            <th rowspan="1" class="align-right" colspan="5"></th>




        </tr></tfoot>
    </table>
</div>




<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }


</script>