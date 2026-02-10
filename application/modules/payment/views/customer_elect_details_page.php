<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 07/12/14
 * Time: 01:32 م
 */


$count = 0;
$delete_details_url=base_url('payment/customers/delete_details');

?>

<div class="tbl_container">
    <table class="table" id="customers_detailsTbl" data-container="container">
        <thead>
        <tr>
            <th style="width: 200px">رقم اشتراك الكهرباء</th>
            <th>ملاحظات</th>
        </tr>
        </thead>

        <tbody>

        <?php if(count($elect_details) <= 0) : ?>
        <tr>
            <td>
                <input type="hidden" name="SER[]" value="0" />
                <input name="elect_no[]" data-val="false" data-val-required="حقل مطلوب"     class="form-control"  id="txt_elect_no<?=$count?>" />
            </td>
            <td>
                <input name="notes[]" class="form-control" id="txt_notes<?=$count?>" />
            </td>
        </tr>

        <?php else: $count = -1; ?>
        <?php endif; ?>

        <?php foreach($elect_details as $row) :?>
            <?php $count++; ?>
            <tr>
                <td>
                    <input type="hidden" name="SER[]" value="<?=$row['SER_SEQ']?>" />
                    <input name="elect_no[]" data-val="false" data-val-required="حقل مطلوب"     class="form-control"  id="txt_elect_no<?=$count?>" value="<?=$row['ELECT_NO']?>" />
                </td>

                <td>
                    <input name="notes[]" class="form-control" id="txt_notes<?=$count?>" value="<?=$row['NOTES']?>" />
                </td>
            </tr>

        <?php endforeach;?>

        </tbody>
        <tfoot>
        <tr>
            <th>
                <?php if (count($elect_details) <=0 || ( isset($can_edit)?$can_edit:false)) : ?>
                    <a onclick="javascript:addRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php endif; ?>
            </th>
            <th></th>

        </tr></tfoot>
    </table>
</div>


<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>
