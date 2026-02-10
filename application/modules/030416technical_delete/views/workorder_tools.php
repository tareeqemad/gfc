<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 09/07/15
 * Time: 01:23 م
 */

$count=0;


?>

<div class="tb_container">
    <table class="table" id="toolsTbl" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 8%">رقم الصنف</th>
            <th style="width: 25%">اسم الصنف</th>
            <th style="width: 7%">الوحدة</th>
            <th style="width: 8%">العدد</th>
            <th style="width: 100px">  الحالة  </th>
            <th></th>
            <th style="width: 50px;"></th>
        </tr>
        </thead>

        <tbody>

        <?php if(count($details) <= 0) {  // ادخال ?>
            <tr>
                <td><i class="glyphicon glyphicon-sort" /></i></td>
                <td>
                    <input type="hidden" name="t_ser[]" value="0" />

                    <input  type="text" class="form-control" name="class_id[]"  id="h_txt_class_id_<?= $count ?>" >
                </td>
                <td>
                    <input name="class_name[]" readonly data-val="true" data-val-required="required" class="form-control"  id="txt_class_id_<?=$count?>" />
                </td>
                <td>

                    <input class="form-control"  type="hidden" id="unit_txt_class_id_<?=$count?>" name="d_class_unit[<?=$count?>]">
                    <input class="form-control"  readonly type="text" id="unit_name_txt_class_id_<?=$count?>"  >
                </td>
                <td>
                    <input name="class_count[]" class="form-control" id="count_txt_class_id_<?=$count?>" />
                </td>
                <td>
                    <select name="class_type[]" class="form-control valid">
                        <option  value="1">جديد</option>
                        <option value="2">مستعمل</option>
                    </select>
                </td>
                <td></td>
                <td data-action="delete"><a  href="javascript:;" onclick="javascript:restInputs($(this).closest('tr'));"><i class="icon icon-trash delete-action"></i> </a></td>
            </tr>

        <?php
        }else if(count($details) > 0) { // تعديل
            $count = -1;
            foreach($details as $row) {
                ?>
                <tr>
                    <td><?=++$count+1?></td>
                    <td>
                        <input type="hidden" name="t_ser[]" value="<?=$row['SER']?>" />
                        <input  type="text" name="class_id[]"  class="form-control" value="<?=$row['CLASS_ID']?>" id="h_txt_class_id_<?= $count ?>" >
                    </td>

                    <td>
                        <input name="class_name[]" readonly value="<?=$row['CLASS_ID_NAME']?>" class="form-control"  id="txt_class_id_<?=$count?>" />
                    </td>

                    <td>
                        <input class="form-control"  type="hidden" value="<?=$row['CLASS_UNIT']?>"  id="unit_txt_class_id_<?=$count?>" name="d_class_unit[<?=$count?>]">
                        <input class="form-control"  readonly value="<?=$row['CLASS_UNIT_NAME']?>" type="text" id="unit_name_txt_class_id_<?=$count?>"  >

                    </td>

                    <td>
                        <input name="class_count[]" class="form-control" id="count_txt_class_id_<?=$count?>" value="<?=$row['CLASS_COUNT']?>" />
                    </td>
                    <td>

                        <select name="class_type[]" class="form-control">
                            <option <?= $row['CLASS_TYPE'] == 1?'selected' : '' ?> value="1" >جديد</option>
                            <option <?= $row['CLASS_TYPE'] == 2?'selected' : '' ?> value="2">مستعمل</option>
                        </select>


                    </td>
                    <td></td>
                    <td data-action="delete">

                        <?php if ( $can_edit && HaveAccess(base_url('technical/WorkOrder/delete_tools')) ) : ?>
                            <a href="javascript:;" onclick="javascript:delete_details(this,<?= $row['SER'] ?>,'<?= base_url('technical/WorkOrder/delete_tools') ?>');"><i class="icon icon-trash delete-action"></i> </a>
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
            <th></th>
            <th>
                <?php if (count($details) <=0 || ( (isset($can_edit)?$can_edit:false)  )) { ?>
                    <a onclick="javascript:add_row(this,'input',false);" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
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
