<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 01/08/15
 * Time: 01:56 م
 */

$count = 0;

?>

<div class="tbl_container">
    <table class="table" id="customers_detailsTbl2" data-container="container">
        <thead>
        <tr>
            <th style="width: 550px">رقم الحساب </th>
            <th style="width: 200px">نوع الحساب</th>
            <th style="width: 70px">حذف</th>
            <th></th>
        </tr>
        </thead>

        <tbody>

        <?php if(count($accounts_details) <= 0) : ?>
            <tr>
                <td>
                    <input type="hidden" name="A_SER[]" value="0" />
                    <input name="customer_accounts_name[]" readonly class="form-control"  id="txt_customer_accounts_id<?=$count?>" />
                    <input type="hidden" name="customer_accounts_id[]" id="h_txt_customer_accounts_id<?=$count?>" />
                </td>
                <td>
                    <select name="account_det_type[]" data-val="1" class="form-control" id="txt_account_det_type<?=$count?>" /></select>
                </td>
                <td><i class="glyphicon glyphicon-remove delete_account"></i></td>
                <td></td>
            </tr>

        <?php else: $count = -1; ?>
        <?php endif; ?>

        <?php foreach($accounts_details as $row) :?>
            <?php $count++; ?>
            <tr>
                <td>
                    <input type="hidden" name="A_SER[]" value="<?=$row['A_SER']?>" />
                    <input name="customer_accounts_name[]" readonly class="form-control"  id="txt_customer_accounts_id<?=$count?>" value="<?=$row['CUSTOMER_ACCOUNTS_ID_NAME']?>" />
                    <input type="hidden" name="customer_accounts_id[]" id="h_txt_customer_accounts_id<?=$count?>" value="<?=$row['CUSTOMER_ACCOUNTS_ID']?>" />
                </td>
                <td>
                    <select name="account_det_type[]" class="form-control" id="txt_account_det_type<?=$count?>" data-val="<?=$row['ACCOUNT_TYPE']?>" /></select>
                </td>
                <td><?=(isset($can_edit)?$can_edit:false)?'<i class="glyphicon glyphicon-remove delete_account"></i>':''?></td>
                <td></td>
            </tr>

        <?php endforeach;?>

        </tbody>
        <tfoot>
        <tr>
            <th>
                <?php if (count($accounts_details) <=0 || ( isset($can_edit)?$can_edit:false)) : ?>
                    <a onclick="javascript:addRow_2();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php endif; ?>
            </th>
            <th></th>
            <th></th>
        </tr>
        </tfoot>
    </table>
</div>


<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>
