<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 26/12/15
 * Time: 12:49 م
 */

$count=0;
?>

<div class="tb_container">
    <table class="table" id="details_tb" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 550px">المحول</th>
            <th style="width: 70px">حذف</th>
            <th></th>
        </tr>
        </thead>

        <tbody>

        <?php if(count($details) <= 0) {  // ادخال ?>
        <tr>
            <td><i class="glyphicon glyphicon-sort" /></i></td>
            <td>
                <input type="hidden" name="ser[]" value="0" />
                <input class="form-control" name="adapter_serial_name[]" readonly id="txt_adapter_serial<?=$count?>" />
                <input  type="hidden" name="adapter_serial[]" id="h_txt_adapter_serial<?= $count ?>" />
            </td>
            <td><i class="glyphicon glyphicon-remove delete_adapter"></i></td>
            <td></td>
        </tr>

        <?php
        }else if(count($details) > 0) { // تعديل
        $count = -1;
        foreach($details as $row) {
        ?>
        <tr>
            <td><?=++$count+1?></td>
            <td>
                <input type="hidden" name="ser[]" value="<?=$row['SER']?>" />
                <input name="adapter_serial_name[]" value="<?=$row['ADAPTER_SERIAL_NAME']?>" class="form-control" readonly id="txt_adapter_serial<?=$count?>" />
                <input  type="hidden" name="adapter_serial[]" value="<?=$row['ADAPTER_SERIAL']?>" id="h_txt_adapter_serial<?= $count ?>" >
            </td>
            <td><?=(isset($can_edit)?$can_edit:false)?'<i class="glyphicon glyphicon-remove delete_adapter"></i>':''?></td>
            <td></td>
        </tr>
        <?php
        }
        }
        ?>

        </tbody>
        <tfoot>
        <tr>
            <th></th>
            <th>
                <?php if (count($details) <=0 || ( (isset($can_edit)?$can_edit:false)  )) { ?>
                    <a onclick="javascript:addRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php } ?>
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
