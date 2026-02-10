<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 23/11/14
 * Time: 09:16 ص
 */
?>

<table class="table" id="voucher_detailsTbl" data-container="container">
    <thead>
    <tr>

        <th style="width: 20px">#</th>
        <th style="width: 200px">الرسوم</th>

        <th style="width: 200px"> حساب الإيراد -دائن </th>

        <th style="width: 200px">المبلغ</th>
        <th></th>
    </tr>
    </thead>
    <tbody>

    <tr>

        <td><?= $count ?></td>
        <td><input type="text" readonly   class="form-control "/></td>
        <td>
            <input type="hidden" name="fees_type[]" >
            <input type="text"  readonly name="credit_account_id_name" id="txt_credit_account_id_name"  class="form-control "/>
            <input type="hidden" name="credit_account_id[]" id="txt_credit_account_id" />
        </td>
        <td>
            <input type="text"  name="credit[]" id="txt_credit"  class="form-control "/>

        </td>

        <td> </td>
        <?php $count++ ?>
    </tr>

    </tbody>
</table>



