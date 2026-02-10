<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 19/03/16
 * Time: 09:43 ص
 */

$count=0;

?>

<div class="tb_container">
    <table class="table" id="details_tb" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th id="adapter_name" style="width: 15%"> المحول </th>
            <th style="width: 5%">من ساعة</th>
            <th style="width: 5%">الى ساعة</th>
            <th style="width: 25%">ملاحظات</th>
            <th style="width: 3%">حذف</th>
        </tr>
        </thead>

        <tbody>

        <?php if(count($details) <= 0) {  // ادخال ?>
            <tr>
                <td><i class="glyphicon glyphicon-sort" /></i></td>
                <td>
                    <input type="hidden" name="ser[]" value="0" />
                    <input type="hidden" name="adapter_serial[]" id="h_txt_adapter_serial<?=$count?>" />
                    <input name="adapter_name[]" readonly data-val="true" data-val-required="required" class="form-control"  id="txt_adapter_serial<?=$count?>" />
                </td>

                <td>
                    <input name="from_hour[]" placeholder="07:00" class="form-control" id="txt_from_hour<?=$count?>" maxlength="5" />
                </td>
                <td>
                    <input name="to_hour[]" placeholder="21:30" class="form-control"  id="txt_to_hour<?=$count?>" maxlength="5" />
                </td>
                <td>
                    <input name="notes[]" class="form-control" id="txt_notes<?=$count?>" />
                </td>

                <td><i class="glyphicon glyphicon-remove delete_adapter"></i></td>
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
                        <input type="hidden" name="adapter_serial[]" value="<?=$row['ADAPTER_SERIAL']?>" id="h_txt_adapter_serial<?=$count?>" />
                        <input name="adapter_name[]" readonly value="<?=$row['ADAPTER_SERIAL_NAME']?>" class="form-control"  id="txt_adapter_serial<?=$count?>" />
                    </td>

                    <td>
                        <input name="from_hour[]" value="<?=$row['FROM_HOUR']?>" class="form-control"  id="txt_from_hour<?=$count?>" maxlength="5"  />
                    </td>
                    <td>
                        <input name="to_hour[]" value="<?=$row['TO_HOUR']?>" class="form-control"  id="txt_to_hour<?=$count?>" maxlength="5" />
                    </td>
                    <td>
                        <input name="notes[]" class="form-control" id="txt_notes<?=$count?>" value="<?=$row['NOTES']?>" />
                    </td>

                    <td><?=(isset($can_edit)?$can_edit:false)?'<i class="glyphicon glyphicon-remove delete_adapter"></i>':''?></td>
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
                <?php if (count($details) <=0 || ( (isset($can_edit)?$can_edit:false) and  $adopt==1 )) { ?>
                    <a onclick="javascript:addRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php } ?>
            </th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        </tfoot>
    </table>
</div>
