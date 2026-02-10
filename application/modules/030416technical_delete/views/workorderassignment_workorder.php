<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 09/07/15
 * Time: 11:22 ص
 */

$count=0;
$loads_url = base_url('technical/Worker_Order_Loads/index/');

?>

<div class="tb_container">
    <table class="table" id="workorderTbl" data-container="container">
        <thead>
        <tr>
            <th style="width: 20px">#</th>
            <th style="width: 400px"> أمر العمل</th>
            <th style="width: 300px">الإجراء</th>
       <!--     <th style="width: 100px">  وقت البداية </th>
            <th style="width: 100px">  وقت النهاية </th>-->
            <th style=""> الملاحظات </th>
            <th style="width: 90px"></th>
            <th style="width: 50px"></th>
        </tr>
        </thead>

        <tbody>

        <?php if(count($details) <= 0) {  // ادخال ?>
            <tr>
                <td><i class="glyphicon glyphicon-sort" /></i></td>
                <td>
                    <input type="hidden" name="w_ser[]" value="0" />
                    <input type="hidden" name="work_order_id[]"  id="h_txt_work_order_id_<?=$count?>" />
                    <input type="text" readonly name="work_order_code[]"  id="h_txt_work_order_code_<?=$count?>" class="form-control col-md-2" />
                    <input  readonly   id="txt_work_order_id_<?=$count?>" class="form-control col-md-10" />
                </td>
                <td>
                    <textarea name="action_procedure[]" rows="5"  class="form-control" id="txt_action_procedure_<?=$count?>" ></textarea>
                </td>
              <!--  <td>

                    <input name="action_start[]"  data-type="date"  data-date-format="DD/MM/YYYY"  class="form-control" id="action_start_txt_work_order_id_<?/*=$count*/?>" />
                </td>

                <td>
                    <input name="action_end[]"  data-type="date"  data-date-format="DD/MM/YYYY"  class="form-control" id="action_end_txt_work_order_id_<?/*=$count*/?>" />
                </td>
-->
                <td>
                    <input name="w_hints[]" class="form-control" id="txt_hints_<?=$count?>" />
                </td>
                <td>

                    <a onclick="javascript:;"  class="_loads btn-xs btn-success" style="display: none;" href="javascript:;">قياس الأحمال</a>
                </td>

                <td data-action="delete"></td>
            </tr>

        <?php
        }else if(count($details) > 0) { // تعديل
            $count = -1;
            foreach($details as $row) {
                ?>
                <tr>
                    <td><?=++$count+1?></td>
                    <td>
                        <input type="hidden" name="w_ser[]" value="<?=$row['SER']?>" />
                        <input type="hidden" name="work_order_id[]" value="<?=$row['WORK_ORDER_ID']?>" data-type="<?=$row['REQUEST_TYPE']?>" id="h_txt_work_order_id_<?=$count?>" />
                        <input type="text" readonly name="work_order_code[]"  id="h_txt_work_order_code_<?=$count?>"  value="<?=$row['WORK_ORDER_CODE']?>" class="form-control col-md-2" />
                        <input  readonly value="<?= $row['WORKORDER_TITLE'] ?>"  id="txt_work_order_id_<?=$count?>" class="form-control col-md-10" />
                    </td>
                    <td>
                        <textarea name="action_procedure[]"  rows="5"  class="form-control" id="txt_action_procedure_<?=$count?>" >
                            <?=$row['ACTION_PROCEDURE']?>
                        </textarea>
                    </td>
                   <!-- <td>

                        <input name="action_start[]" value="<?/*=$row['ACTION_START']*/?>" data-type="date"  data-date-format="DD/MM/YYYY"  class="form-control" id="action_start_txt_work_order_id_<?/*=$count*/?>" />
                    </td>

                    <td>
                        <input name="action_end[]" value="<?/*=$row['ACTION_END']*/?>" data-type="date"  data-date-format="DD/MM/YYYY"  class="form-control" id="action_start_txt_work_order_id_<?/*=$count*/?>"/>
                    </td>
-->
                    <td>
                        <input name="w_hints[]" value="<?=$row['HINTS']?>" class="form-control" id="txt_hints_<?=$count?>" />
                    </td>
                    <td>
                        <a onclick="javascript:<?=$row['REQUEST_TYPE'] == 5? "_showReport('{$loads_url}/{$row['WORK_ORDER_ID']}',true)" : 'display: none;'?>;"  class="_loads btn-xs btn-success" style="<?=$row['REQUEST_TYPE'] == 5? '' : 'display: none;'?>" href="javascript:;">قياس الأحمال</a>
                    </td>

                    <td data-action="delete">

                        <?php if ( $can_edit && HaveAccess(base_url('technical/WorkOrderAssignment/delete_WOrder'))  ) : ?>
                            <a href="javascript:;" onclick="javascript:delete_details(this,<?= $row['SER'] ?>,'<?= base_url('technical/WorkOrderAssignment/delete_WOrder') ?>');"><i class="icon icon-trash delete-action"></i> </a>
                        <?php endif; ?>


                    </td>
                </tr>
            <?php
            }
        }
        ?>

        </tbody>
        <tfoot>
        <tr>
            <th ></th>
            <th>
                <?php if (count($details) <=0 || ( (isset($can_edit)?$can_edit:false)  )) { ?>
                    <a onclick="javascript:add_row(this,'input',false);" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php } ?>
            </th>
            <th colspan="3"></th>
            <th></th>
        </tr>
        </tfoot>
    </table>
</div>
