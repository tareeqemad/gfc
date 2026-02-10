<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 09/07/15
 * Time: 01:23 م
 */

$count=0;
$details= $details_3;

?>

<div class="tb_container">
    <table class="table" id="details_tb_3" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 8%">رقم الصنف</th>
            <th style="width: 20%">اسم الصنف</th>
            <th style="width: 7%">الوحدة</th>
            <th style="width: 8%">العدد</th>
        </tr>
        </thead>

        <tbody>

        <?php if(count($details) <= 0) {  // ادخال ?>
            <tr>
                <td><i class="glyphicon glyphicon-sort" /></i></td>
                <td>
                    <input type="hidden" name="t_ser[]" value="0" />
                    <input class="form-control" name="class[]" id="i_txt_class_id<?=$count?>" />
                    <input  type="hidden" name="class_id[]"  id="h_txt_class_id<?= $count ?>" >
                </td>
                <td>
                    <input name="class_name[]" readonly data-val00="true" data-val-required="required" class="form-control"  id="txt_class_id<?=$count?>" />
                </td>
                <td>
                    <input name="class_unit[]" disabled class="form-control" id="unit_name_txt_class_id<?=$count?>" />
                </td>
                <td>
                    <input name="class_count[]" class="form-control" id="txt_class_count<?=$count?>" />
                </td>
            </tr>

        <?php
        }else if(count($details) > 0) { // تعديل
            $count = -1;
            foreach($details as $row) {
                ?>
                <tr>
                    <td><?=++$count+1?></td>
                    <td>
                        <input type="hidden" name="t_ser[]" value="<?=$row['T_SER']?>" />
                        <input name="class[]" value="<?=$row['CLASS_ID']?>" class="form-control"  id="i_txt_class_id<?=$count?>" />
                        <input  type="hidden" name="class_id[]" value="<?=$row['CLASS_ID']?>" id="h_txt_class_id<?= $count ?>" >
                    </td>

                    <td>
                        <input name="class_name[]" readonly value="<?=$row['CLASS_ID_NAME']?>" class="form-control"  id="txt_class_id<?=$count?>" />
                    </td>

                    <td>
                        <input name="class_unit[]" disabled value="<?=$row['CLASS_UNIT_NAME']?>" class="form-control" id="unit_name_txt_class_id<?=$count?>" />
                    </td>

                    <td>
                        <input name="class_count[]" class="form-control" id="txt_class_count<?=$count?>" value="<?=$row['CLASS_COUNT']?>" />
                    </td>
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
                    <a onclick="javascript:addRow_3();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php } ?>
            </th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        </tfoot>
    </table>
</div>
