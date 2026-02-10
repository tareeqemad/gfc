<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 21/06/16
 * Time: 10:13 ص
 */

if($browser=='Firefox')
    $range_class='form-control';
else
    $range_class='';

$get_ask_mark_url=base_url("hr/evaluation_emps_start/get_ask_mark");
?>

    <div class="tbl_container">
        <table class="table" id="page_tb" data-container="container">
            <thead>
            <tr>
                <th style="width: 3%">#</th>
                <th style="width: 37%">السؤال</th>
                <th style="width: 33%">التقييم</th>
                <th style="width: 23%">الوصف</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($page_rows as $row) :?>
                <tr data-marks='<?=modules::run($get_ask_mark_url, $row['EVALUATION_ELEMENT_ID']);?>'>
                    <td><?=$row['EVALUATION_ELEMENT_ORDER']?>
                    <input type="hidden" name="element_id[]" value="<?=$row['EVALUATION_ELEMENT_ID']?>" />
                    <input type="hidden" name="efa_id[]" value="<?=$row['EFA_ID']?>" />
                    <input type="hidden" name="eval_form_type[]" value="1" />
                    <input type="hidden" name="eval_form_id[]" value="<?=$FORM_ID?>" />
                    </td>
                    <td style="text-align: right"><?=$row['EVALUATION_ELEMENT_NAME']?></td>
                    <td>
                        <input style="float: right; width: 85%" class="<?=$range_class?>" name="mark[]" type="range" min="0" max="100" step="1" value="74" />
                        <input style="float: right; width: 11%; margin-right: 2%; text-align: center" class="form-control" readonly type="text" value="74">
                    </td>
                    <td><input class="form-control mark_desc" readonly type="text" value=""></td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>