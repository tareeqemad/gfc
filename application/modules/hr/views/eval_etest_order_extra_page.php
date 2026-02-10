<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 27/10/16
 * Time: 10:38 ص
 */

if($browser=='Firefox')
    $range_class='form-control';
else
    $range_class='';

?>

<div class="tbl_container">
    <table class="table" id="extra_page_tb" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 30%">السؤال</th>
            <th style="width: 20%">التقييم</th>
            <th style="width: 17%"></th>
            <th style="width: 17%">الانجاز</th>
            <th style="width: 17%">ملاحظات</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($page_rows as $row) :?>
            <tr data-marks=''>
                <td><?=$row['ELEMENT_ORDER']?>
                    <input type="hidden" name="element_id[]" value="<?=$row['EEXTRA_ELEMENT_ID']?>" />
                    <input type="hidden" name="efa_id[]" value="" />
                    <input type="hidden" name="eval_form_type[]" value="2" />
                    <input type="hidden" name="eval_form_id[]" value="<?=$FORM_ID?>" />
                </td>
                <td style="text-align: right"><?=$row['ELEMENT_NAME']?></td>
                <td>
                    <input style="float: right; width: 85%" class="<?=$range_class?>" name="mark[]" type="range" min="0" max="100" step="1" value="74" />
                    <input style="float: right; width: 11%; margin-right: 2%; text-align: center" class="form-control" readonly type="text" value="74">
                </td>
                <td><input style="display: none" class="form-control mark_desc" readonly type="text" value=""></td>
                <td><input class="form-control" name="achievement[]" type="text" value=""></td>
                <td><input class="form-control" name="hint[]" type="text" value=""></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
